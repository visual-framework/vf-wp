// Configuration to search EMBL.org with Swiftype
// Requires src/site/search/jquery.swiftype.search.js
$(function() {
    const searchQueryInput = document.querySelectorAll("[data-embl-search-input]"); // where we put the query
    const searchQuerySubmit = document.querySelectorAll("[data-embl-search-submit]")[0]; 
    var activeFacet = {};
  
    // ensure url always matches input box
    searchQueryInput[0].addEventListener("input", function(){
      // set the search query to the input box
      // $('.st-default-search-input').val(info['info']['page']['query']);
      // submit search
      // console.log("searchQuerySubmit", searchQuerySubmit)
      submitSearch();
    });
  
    // throttle search submission
    var delayTimer;
    function submitSearch() {
      clearTimeout(delayTimer);
      delayTimer = setTimeout(function() {
        saveSearch();
        if ($(".st-default-search-input").val().length >= 3) {
          handleVisibility();
          invokeSearch();
          // searchQuerySubmit.click();
        }
      }, 500); // Will do the ajax stuff after xxx ms
    }
  
    var customPreRenderFunction = function(info) {
      // console.log('here',info);
      var results = '';
      var results = '<p>' + info['info']['page']['total_result_count'] + ' results found for "' + info['info']['page']['query'] +'"';
      if (activeFacet.what) {
        results = results + ' in the category "' + activeFacet.what + '"';
      }
      results = results + '.</p>';
      // console.log('activeFacet',activeFacet)
      $('.st-info-container').html(results);
      // if no results
      if (Math.floor(info['record_count']) == 0) {
        $('.st-info-container').html('<!-- no results -->');      
      }
    }
    var customRenderFunction = function(document_type, item) {
      // show result filters
      // var searchResults = document.querySelectorAll('[data-embl-search-filters]')[0];
      // searchResults.classList.remove('vf-u-display-none');
  
      // console.log(document_type,item);
      item['title'] = item['title'].replace(" | EMBL.org", ""); // it doesn't make sense to always show `page title | embl.org`
  
      // Prepare image and handle when no image is present
      var itemImage = '',
          resultClass = '';
      if (item['image']) {
        itemImage = '<a href="' + item['url'] + '" class="vf-summary__image">' + 
          // note, we normalize the string with NFD, which works well with umlauts and wordpress, we may need to revisit this
          '<img class="vf-summary__image" src="' + decodeURI(item['image']).normalize('NFD') + '" alt="' + item['title'] + '">' +
          '</a>';
        resultClass = 'vf-summary--news'; // we drop this class to make it full width for no image
      }
  
      // make a summary if one does not exist
      if (item['highlight']['body']) {
        var summary = item['highlight']['body'];
      } else {
        var summary = item['body'].substring(0, 200);
      }
  
      var out = '<article class="vf-summary '+resultClass+'">' +
        // '<span class="vf-summary__date">' + item['published_at'] + '</span>' +
        itemImage +
        '<h3 class="vf-summary__title">' +
            '<a href="' + item['url'] + '" class="vf-summary__link">' +
                item['title'].replace(' | EMBL','') + // no need for `| EMBL suffix on urls`
            '</a>' +
        '</h3>' +
        '<p class="vf-summary__text">' +
          summary +
        '</p>' +
        '<div class="vf-summary__meta">' +
          '<a href="' + item['url'] + '" class="vf-summary__author vf-summary__link">' + item['url'] + '</a>' +
          '<span class="vf-summary__date vf-u-display-none">Score: ' + item['_score'] + '</span>' +
        '</div>'+
      '</article>';      
  
      return out;
    };
  
    // Handle search filter
    var facetSelect = document.querySelectorAll('[data-embl-search-facet]')[0];
    facetSelect.addEventListener('change', (event) => {
      if (event.target.value == 'all') {
        activeFacet = '{}';
      } else {
        activeFacet = {'what': event.target.value};
      }
      // console.log('activeFacet addEventListener',activeFacet)
      submitSearch();
    });
  
    // get any passed query value and use it
    // this should be more robust but is sufficient for our MVP
    // @param {string} [param] - param to look for `queryString`
    // @param {string} [args] - optional arguments to pass, otherwise will get from the URL
    // @todo: set url value on update
    function getFilterFromURL(param, args) {
      var value = '';
      args = window.location.search.substr(1) || '';
      value = args.split(param+'=')[1];
  
      if (args && value) {
        value = value.split('&')[0]; // make sure we also don't have another arg
        value = value.replace('%20',' '); // string to space
        value = value.replace('+',' '); // encoded spaces to space
      }
  
      if (value != '' && value != undefined) {
        if (param == 'activeFacet') {
          activeFacet = { 'what': value };
          facetSelect.value = value; // set select to url value
        } else if (param == 'searchQuery') {
          // set the default search value
          if ($(".st-default-search-input").val() == "") {
            $(".st-default-search-input").val(value);
            window.location.hash = "#stq="+value;
          }
        }
      }
    }
  
    // log query in url
    function updateQueryUrl(activeQuery, activeFacet) {
      // ?searchQuery=ken&activeFacet=People+directory#stq=ken&stp=1
      // console.log('activeFacet',activeFacet)
      if (history.pushState) {
        activeFacet = activeFacet || '';
        var hash = window.location.hash
  
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?searchQuery='+activeQuery+'&activeFacet='+activeFacet+hash;
        window.history.pushState({path:newurl},'',newurl);
      }  
      window.location.hash = '#stq='+$('.st-default-search-input').val();
    }
  
    // various state management
    function handleVisibility() {
      // console.log('activeFacet', activeFacet)
      // console.log('facetSelect',facetSelect.value)
      var currentFacet = facetSelect.querySelectorAll("input[value='"+activeFacet.what+"']");
      var webResultsContainer = document.querySelectorAll("[data-embl-search-web-results]"); 
      var alumniResultsContainer = document.querySelectorAll("[data-embl-search-alumni-results]"); 
      var alumniResultsContainerWrapper = document.querySelectorAll("[data-embl-search-alumni-results-wrapper]"); 
      var alumniResultsDivider = document.querySelectorAll("[data-embl-search-results-divider]");
      var alumniResultsInfo = document.querySelectorAll("[data-embl-search-alumni-info ]");
      
      var activeFacetLabel = "all";
  
      // ensure from facets are in sync with search performed
      if (currentFacet.length > 0) {
        currentFacet[0].checked = true;
        activeFacetLabel = currentFacet[0].value;
        // console.log('activeFacetLabel',activeFacetLabel)
      }
  
      // if "everything" is selected, show alumni and web results
      if (activeFacetLabel == "all") {
        webResultsContainer[0].classList.remove('vf-u-display-none')
        alumniResultsContainerWrapper[0].classList.remove('vf-u-display-none')
        alumniResultsDivider[0].classList.remove('vf-u-display-none')
        alumniResultsInfo[0].classList.add('vf-u-display-none')
      }
      // if alumni is active, hide the web results
      else if (activeFacetLabel == "Alumni") {
        webResultsContainer[0].classList.add('vf-u-display-none')
        alumniResultsContainerWrapper[0].classList.remove('vf-u-display-none')
        alumniResultsDivider[0].classList.add('vf-u-display-none')
        alumniResultsInfo[0].classList.remove('vf-u-display-none')
      } else {
        alumniResultsInfo[0].classList.add('vf-u-display-none')
        webResultsContainer[0].classList.remove('vf-u-display-none')
        alumniResultsContainerWrapper[0].classList.add('vf-u-display-none')
        alumniResultsDivider[0].classList.add('vf-u-display-none')
      }
    }
  
    function saveSearch() {
      // save the query
      updateQueryUrl($('.st-default-search-input').val(), activeFacet.what);
    }
  
    // do search
    function invokeSearch() {
  
      $('.st-default-search-input').swiftypeSearch({
        // https://github.com/swiftype/swiftype-search-jquery
        preRenderFunction: customPreRenderFunction,
        renderFunction: customRenderFunction,
        //fetchFields: {'books': ['title','body','published_on']},
        //searchFields: {'books': ['title']},
        perPage: 10,
        filters: {'page': activeFacet},
        resultContainingElement: '.st-search-container',
        engineKey: 'uhn1PwHVEgsdzhbgQUPz'
      });
    }
  
    getFilterFromURL('activeFacet'); // currently supports "People directory", "Jobs", "News", "all", 'Alumni"
    getFilterFromURL('searchQuery'); 
    // don't set if there's already a search query
    // if (window.location.hash.includes('#stq') == false) { 
    // }
    if ($(".st-default-search-input").val().length >= 3) {
      handleVisibility();
      invokeSearch();
    }
  });
  