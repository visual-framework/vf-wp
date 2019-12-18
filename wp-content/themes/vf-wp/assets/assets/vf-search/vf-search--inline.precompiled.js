/**
 * Precompiled Nunjucks template: vf-search--inline.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-search--inline"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<form action=\"\" class=\"vf-form | vf-search vf-search--inline\">\n  <div class=\"vf-form__item | vf-search__item\">\n    <label class=\"vf-form__label vf-u-sr-only | vf-search__label\" for=\"text\">Search</label>\n    <input type=\"text\" id=\"text\" class=\"vf-form__input | vf-search__input\">\n  </div>\n  <button type=\"submit\" class=\"vf-search__button | vf-button vf-button--primary\">Search</button>\n</form>\n";
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
