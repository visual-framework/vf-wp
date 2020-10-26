/**
 * Precompiled Nunjucks template: vf-profile--test.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-profile--test"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<article class=\"vf-profile vf-profile--easy vf-profile--large vf-profile--inline\">\n  <img class=\"vf-profile__image\" src=\"http://content.embl.org//sites/default/files/styles/medium/public/persons/CP-60003456?itok=2x-sJ-v-\" alt=\"person\">\n  <h3 class=\"vf-profile__title | vf-u-margin__bottom--lg\">\n    <a href=\"https://www.embl.org/people/person/detlev-arendt/\" class=\"vf-profile__link\">Detlev Arendt</a>\n  </h3>\n  <div>\n    <a href=\"https://cordis.europa.eu/project/rcn/216235_en.html\" class=\"vf-link\">NeuralCellTypeEvo</a>\n    <span class=\"vf-u-type__text-body--5\"> 2018 – 2023</span>\n    <p class=\"vf-profile__text | vf-u-margin__bottom--sm\">Cellular innovation driving nervous system evolution</p>\n\n    <a href=\"https://cordis.europa.eu/project/rcn/216235_en.html\" class=\"vf-link\">NeuralCellTypeEvo</a>\n    <span class=\"vf-u-type__text-body--5\"> 2018 – 2023</span>\n    <p class=\"vf-profile__text\">Cellular innovation driving nervous system evolution</p>\n  </div>\n</article>\n";
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
