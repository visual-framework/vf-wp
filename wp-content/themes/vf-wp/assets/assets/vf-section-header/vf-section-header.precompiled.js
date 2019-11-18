/**
 * Precompiled Nunjucks template: vf-section-header.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-section-header"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-section-header\">\n  <h2 class=\"vf-section-header__heading\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "section_title"), env.opts.autoescape);
output += "</h2>\n</div>\n";
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
