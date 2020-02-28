/**
 * Precompiled Nunjucks template: vf-hero.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-hero"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<section class=\"vf-hero vf-hero--very-easy\">\n  <div class=\"vf-hero__content\">\n";
if(runtime.contextOrFrameLookup(context, frame, "vf_hero_heading")) {
output += " <h2 class=\"vf-hero__heading\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_heading"), env.opts.autoescape);
output += "</h2>";
;
}
output += "    <p class=\"vf-hero__text\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_text"), env.opts.autoescape);
output += "<a class=\"vf-link\" href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_link"), env.opts.autoescape);
output += "\"> </a></p>\n  </div>\n  <article class=\"vf-summary | vf-summary--profile vf-summary--profile-r\" data-embl-js-conditional-edit=\"4473\">\n              <a href=\"http://www.ebi.ac.uk/about/people/helen-parkinson\" class=\"vf-summary__image\" target=\"_blank\" rel=\"noopener noreferrer\">\n          <img class=\"vf-summary__image vf-summary__image--avatar\" src=\"http://dev.content.embl.org//sites/default/files/styles/medium/public/persons/CP-60002372?itok=vKJ1sABp\" alt=\"image of Helen Parkinson\">\n        </a>\n      <h3 class=\"vf-summary__title\">Team Leader Samples Phenotypes &amp; Ontolog</h3>\n              <p class=\"vf-summary__text\">\n          <a href=\"http://www.ebi.ac.uk/about/people/helen-parkinson\" class=\"vf-summary__link\" target=\"_blank\" rel=\"noopener noreferrer\">\n            Helen Parkinson\n          </a>\n        </p>\n      <p class=\"vf-summary__email\">\n        <a href=\"mailto:parkinso@ebi.ac.uk\" class=\"vf-summary__link vf-summary__link--secondary\">parkinso@ebi.ac.uk</a>\n      </p>\n      <a class=\"vf-text vf-text--body-r vf-link embl-conditional-edit\" href=\"https://dev.content.embl.org/node/4473\" target=\"_blank\" rel=\"noopener noreferrer\">Edit</a>\n    </article>\n</section>\n";
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
;
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
