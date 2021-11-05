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
          }, 900); // give a second check if breadcumbs was slow to load
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
  
  document.addEventListener("DOMContentLoaded", emblPeoplePagesGroupLink(0,5));
  