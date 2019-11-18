/**
 * Precompiled Nunjucks template: embl-content-hub-loader.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["embl-content-hub-loader"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"embl-content-hub-loader\">\n  <p>Below we do a sample import from the ContentHub:</p>\n  <link rel=\"import\" href=\"https://dev.beta.embl.org/api/v1/pattern.html?filter-content-type=article&filter-id=575&pattern=node-body&source=contenthub\" data-target=\"self\" data-embl-js-content-hub-loader>\n\n</div>\n";
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
