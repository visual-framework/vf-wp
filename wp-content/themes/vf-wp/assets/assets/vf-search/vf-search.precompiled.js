/**
 * Precompiled Nunjucks template: vf-search.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-search"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<form action=\"\" class=\"vf-form | vf-search\">\n  <div class=\"vf-form__item | vf-search__item\">\n    <label class=\"vf-form__label vf-u-sr-only | vf-search__label\" for=\"text\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "label"), env.opts.autoescape);
output += "</label>\n    <input type=\"text\" id=\"text\" class=\"vf-form__input | vf-search__input\">\n  </div>\n  <button type=\"submit\" class=\"vf-search__button | vf-button vf-button--primary\"> ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "button"), env.opts.autoescape);
output += "</button>\n</form>\n";
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
