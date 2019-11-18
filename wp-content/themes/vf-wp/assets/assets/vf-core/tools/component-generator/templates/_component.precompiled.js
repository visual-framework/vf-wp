/**
 * Precompiled Nunjucks template: _component.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["_component"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"<%= componentName %>\">\n  /* <%= componentName %> template file */\n</div>\n";
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
