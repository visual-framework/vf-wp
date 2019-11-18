/**
 * Precompiled Nunjucks template: vf-page-header.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-page-header"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<header class=\"vf-page-header\">\n  <h1 class=\"vf-page-header__heading\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "heading"), env.opts.autoescape);
output += "</h1>\n  <span class=\"vf-page-header__sub-heading\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "subheading"), env.opts.autoescape);
output += "</span>\n</header>\n";
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
