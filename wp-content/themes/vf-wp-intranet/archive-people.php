<?php

get_header();
global $wp;
$pageURL = home_url( $wp->request );

?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack | vf-content">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>
    <h2 class="vf-intro__subheading" style="margin-bottom: 2rem;">Find anyone at EMBL.
    </h2>
    <p class="vf-intro__text">Search for a person in the <strong>People</strong> tab by name or job title. Or you can
      use the <strong>Teams</strong> tab to find people by research area or organisational group.</p>

  </div>
</section>
<section id="load-container" class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--200">
  <div></div>
  <div id="load"></div>
</section>
<div id="search-content">
  <div id="tabsSection"
    class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 | containerBottomPadding">
    <div></div>

    <div>
      <div class="vf-tabs | vf-u-margin__bottom--400">
        <ul class="vf-tabs__list" data-vf-js-tabs>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--1" onclick="ClearFields();">Person</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--2" onclick="ClearFields();">Team</a>
          </li>
        </ul>
      </div>
      <div class="vf-tabs-content" data-vf-js-tabs-content>
        <section class="vf-tabs__section" id="vf-tabs__section--1">
          <div class="vf-form__item">
            <input onkeypress="setTimeout(emblPeoplePagesGroupLinkAssign, 2000)" name="input" id="search"
              class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
              data-group="data-group-1" data-name="my-filter-1" data-path=".people-search" type="text" value=""
              placeholder="Search by name or job title" data-clear-btn-id="name-clear-btn">
          </div>
          <button style="display: none;" type="button" id="name-clear-btn"
            class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
            <span class="vf-button__text">Reset</span>
          </button>
        </section>
        <section class="vf-tabs__section" id="vf-tabs__section--2">
          <div class="vf-form__item">
            <input onkeypress="setTimeout(emblPeoplePagesGroupLinkAssign, 2000)" name="input" id="search2"
              class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
              data-group="data-group-1" data-name="my-filter-1" data-path=".team-search" type="text" value=""
              placeholder="Search by organisational group" data-clear-btn-id="name-clear-btn">
          </div>
          <button style="display: none;" type="button" id="name-clear-btn"
            class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
            <span class="vf-button__text">Reset</span>
          </button>

          <fieldset class="vf-form__fieldset | vf-stack vf-stack--400 | vf-u-margin__top--200">
            <div class="vf-cluster vf-cluster--400">
              <div class="vf-cluster__inner">
                <div class="vf-form__item vf-form__item--radio">
                  <input id="teamasc" class="vf-form__radio" type="radio" data-jplist-control="radio-buttons-sort"
                    data-path=".team-search" data-group="data-group-1" data-order="asc" data-type="text"
                    data-name="sort1" name="sort1" /> <label for="teamasc" class="vf-form__label">Sort A-Z</label>
                </div>

                <div class="vf-form__item vf-form__item--radio">
                  <input id="teamdsc" class="vf-form__radio" type="radio" data-jplist-control="radio-buttons-sort"
                    data-path=".team-search" data-group="data-group-1" data-order="desc" data-type="text"
                    data-name="sort1" name="sort1" /> <label for="teamdsc" class="vf-form__label">Sort
                    Z-A</label>
                </div>
              </div>
            </div>
          </fieldset>
        </section>
      </div>
    </div>
    <div class="vf-content">
      <p><a href="https://hd-tqportal.embl.de/EMBL_LIVE_thankQ_Web/public/network/results.aspx">Find Alumni
        </a></p>
      <p><a href="https://www.embl.org/internal-information/departments-and-teams/">Departments and teams
        </a></p>
      <p><a href="https://www.embl.org/internal-information/documents/embls-organisational-chart/">EMBL’s organisational
          chart
        </a></p>
    </div>
  </div>

  <div id="mainData"
    class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--800 vf-u-padding__bottom--500 | vf-u-margin__bottom--800 | vf-u-display-none">
    <div></div>
    <div>
      <div data-jplist-group="data-group-1">
        <?php
    if(strpos($pageURL, 'wwwdev') !== false){
      $request = wp_remote_get( 'https://xs-db.test.embl.de/v3/public/search' );
    } else{
      $request = wp_remote_get( 'https://xs-db.embl.de/v3/public/search' );
    }
        
        if( is_wp_error( $request ) ) {
            return false; // Bail early
        }
        $body = wp_remote_retrieve_body( $request );
        $data = json_decode( $body );
        function sortByName($param1, $param2) {
          return strcmp($param1->displayName, $param2->displayName);
      }        
      usort($data, "sortByName");

        if( ! empty( $data ) ) {
            foreach( $data as $person ) { 
              // show people only with positions
              if (isset($person->title)) {
                if (!isset($person->department)) {
                  $person->department = "";
                };
                // if no photo show a placeholder
                if (!isset($person->photoUrl)) {
                  $person->photoUrl = "http://content.embl.org/sites/default/files/default_images/vf-icon--avatar.png";
                };
                // display a telephone number
                if (isset($person->primaryTelephoneNumber)) {
                  $telephoneNr = $person->primaryTelephoneNumber;
                }
                elseif (isset($person->telephoneNumber)) {
                  $telephoneNr = $person->telephoneNumber;
                }
                else {
                  $telephoneNr = null;
                }
                // create urls 
                $peopleBaseUrl = "https://www.embl.org/internal-information/people/";

                if (isset($person->personUrl)) {
                  $peopleSlug = basename($person->personUrl);
                  $peopleEndUrl = $peopleBaseUrl . $peopleSlug;
                  }
                else {
                  $peopleSlug = $person->oid;
                  $peopleEndUrl = $peopleBaseUrl . $peopleSlug;
                };  

                
                if (isset($person->personKnownAs)) {
                  $personName = $person->personKnownAs;
                }
                else {
                  $personName = $person->displayName;
                }

                echo '<article class="vf-profile vf-profile--medium vf-profile--inline | vf-u-margin__bottom--600" data-jplist-item>
                <img class="vf-profile__image" src="' . $person->photoUrl . '" alt="" loading="lazy">';

                echo '<h3 class="vf-profile__title | people-search"><a href="' . $peopleEndUrl . '" class="vf-profile__link">' . $personName . '</a></h3>';

                echo '<p class="vf-profile__job-title | people-search">' . $person->title . '</p>';

                echo '<p class="vf-profile__text | team-search"><a data-embl-js-group-link="' . $person->department . '" class="vf-link"
                href="https://www.embl.org/internal-information/?s=' . $person->department . '&post_type=any">' . $person->department . '</a></p>';

                echo '<p class="vf-profile__email | vf-u-margin__top--100">
                <a href="mailto:' . $person->mail . '"
                class="vf-profile__link vf-profile__link--secondary">' . $person->mail . '</a>
                </p>';

                if (!empty($telephoneNr)) {
                echo '<p class="vf-profile__phone vf-u-margin__bottom--100">
                <a href="tel:' . $telephoneNr . '"
                class="vf-profile__link vf-profile__link--secondary">' . $telephoneNr . '</a>
                </p>'; }

                if (!empty($person->roomNumber)) {
                echo '<p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--0">
                <span>Location: </span>' . $person->roomNumber . '
                </p>'; }

                echo '<p class="vf-profile__text | vf-u-margin__top--100 | vf-u-margin__bottom--200">
                ' . $person->personDutystation . '
                </p>';

                echo '<p class="people vf-u-display-none | used-for-filtering">People</p>
                </article>'; 
                  
                } 
            }
        }
        ?>
        <!-- no results control -->
        <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
          data-name="no-results">
          <p class="vf-summary__text">
            No matching people found
          </p>
        </article>
      </div>
      <?php include(locate_template('partials/paging-controls.php', false, false)); ?>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">
  function ClearFields() {
    document.getElementById("name-clear-btn").click();
  }
  jplist.init();

</script>

<?php

get_footer();

?>
<script>
  function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 1000);

    function checkReady() {
      if (document.getElementsByTagName('body')[0] !== undefined) {
        window.clearInterval(intervalID);
        callback.call(this);
      }
    }
  }

  function show(id, value) {
    document.getElementById(id).style.display = value ? 'grid' : 'none';
  }

  onReady(function () {
    show('search-content', true);
    show('load', false);
    show('load-container', false);
  });

  // Lookup a group in the ontology by name
  function emblPeoplePagesGroupLink(numberOfChecks, numberOfChecksLimit) {
    // emblTaxonomy is a global variable fetched by embl-breadcumbs-lookup
    // we must wait for it to load

    if (typeof emblTaxonomy === "object") {
      if (emblTaxonomy.version != undefined) {
        emblPeoplePagesGroupLinkReady();
      } else {
        // console.log('retry')
        if (numberOfChecks <= numberOfChecksLimit) {
          setTimeout(function () {
            emblPeoplePagesGroupLinkAssign(numberOfChecks, numberOfChecksLimit);
          }, 2000); // give a second check if breadcumbs was slow to load
        }
      }
    }
  }

  // lookup a team in the onotology
  function emblOntologyFindTeamByName(teamName) {
    for (const key in emblTaxonomy.terms) {
      if (Object.hasOwnProperty.call(emblTaxonomy.terms, key)) {
        const element = emblTaxonomy.terms[key];
        if (teamName == element.name || teamName == element.name_display) {
          return element
        }
      }
    }

    return false;
  }

  // With the emblTaxonomy loaded, we can assign links to containers
  // <a data-embl-js-group-link="team name" href="#placeholder">
  function emblPeoplePagesGroupLinkAssign() {
    var emblPeoplePagesGroupLinkTarget = document.querySelectorAll("[data-embl-js-group-link]");

    if (emblPeoplePagesGroupLinkTarget.length === 0) {
      console.warn('There is no `[data-embl-js-group-link]` in which to insert the breadcrumbs; exiting');
      return false;
    }
    // console.log(emblTaxonomy)
    // console.log(emblPeoplePagesGroupLinkTarget)

    // process each link target found
    for (const key in emblPeoplePagesGroupLinkTarget) {
      if (Object.hasOwnProperty.call(emblPeoplePagesGroupLinkTarget, key)) {
        const element = emblPeoplePagesGroupLinkTarget[key];
        let team = emblOntologyFindTeamByName(element.dataset.emblJsGroupLink)

        if (team != false) {
          element.href = team.url;
        } else {
          console.warn('emblPeoplePagesGroupLinkAssign', 'No team match found, leaving default search in place')
        }
      }
    }
  }
  emblPeoplePagesGroupLinkAssign();

</script>
<script>
  $("input[name=input]").keyup(function () {
    if (this.value == '') {
      $("#mainData").addClass('vf-u-display-none');
      $("#tabsSection").addClass('containerBottomPadding');
    } else {
      $("#mainData").removeClass('vf-u-display-none');
      $("#tabsSection").removeClass('containerBottomPadding');
    }
  });

  $("#pagination_list").click(function () {
    emblPeoplePagesGroupLinkAssign();
  });

</script>
<style>
  .containerBottomPadding {
    padding-bottom: 5rem;
  }

</style>

