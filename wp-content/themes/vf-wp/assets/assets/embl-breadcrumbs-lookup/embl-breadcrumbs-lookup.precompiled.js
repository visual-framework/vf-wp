/**
 * Precompiled Nunjucks template: embl-breadcrumbs-lookup.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["embl-breadcrumbs-lookup"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<nav class=\"vf-breadcrumbs embl-breadcrumbs-lookup\" aria-label=\"Breadcrumb\" data-embl-js-breadcrumbs-lookup>\n  <div class=\"vf-list vf-list--inline | vf-breadcrumbs__list | embl-breadcrumbs-lookup--ghosting\"></div>\n</nav>";
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
