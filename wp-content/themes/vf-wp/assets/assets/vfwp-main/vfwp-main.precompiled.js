/**
 * Precompiled Nunjucks template: vfwp-main.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vfwp-main"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vfwp-main\">\n  /* vfwp-main template file */\n</div>\n";
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
