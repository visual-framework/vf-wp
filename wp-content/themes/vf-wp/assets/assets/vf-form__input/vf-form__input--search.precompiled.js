/**
 * Precompiled Nunjucks template: vf-form__input--search.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-form__input--search"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-form__item\">\n  <label class=\"vf-form__label\" for=\"search\">Search</label>\n  <input type=\"search\" id=\"search\" class=\"vf-form__input\" placeholder=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_placeholder"), env.opts.autoescape);
output += "\">\n</div>\n";
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
