/**
 * Precompiled Nunjucks template: vfwp-autocomplete.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vfwp-autocomplete"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vfwp-autocomplete\">\n  /* vfwp-autocomplete template file */\n</div>\n";
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
