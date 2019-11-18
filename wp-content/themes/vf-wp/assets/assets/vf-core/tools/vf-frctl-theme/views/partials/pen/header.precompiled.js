/**
 * Precompiled Nunjucks template: header.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["header"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<h1 class=\"vf-page-header__heading | vf-text vf-text-heading-2 vf-text--has-tag\">\n  ";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"title"), env.opts.autoescape);
output += "\n  ";
output += runtime.suppressValue((lineno = 2, colno = 15, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "status")),"tag"), "status[\"tag\"]", context, [runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"status")])), env.opts.autoescape);
output += "\n</h1>\n";
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
