/**
 * Precompiled Nunjucks template: embl-logo.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["embl-logo"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "logo_href"), env.opts.autoescape);
output += "\" class=\"embl-logo";
if(runtime.contextOrFrameLookup(context, frame, "classname")) {
output += " ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "classname"), env.opts.autoescape);
;
}
output += "\">\n  <span class=\"vf-u-sr-only\" for=\"text\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "logo_text"), env.opts.autoescape);
output += "</span>\n</a>\n";
if(runtime.contextOrFrameLookup(context, frame, "deprecated_text")) {
output += "<!-- ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "deprecated_text"), env.opts.autoescape);
output += " -->";
;
}
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
