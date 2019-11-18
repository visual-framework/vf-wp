/**
 * Precompiled Nunjucks template: vfwp-admin.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vfwp-admin"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vfwp-admin\">\n  /* vfwp-admin template file */\n</div>\n";
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
