/**
 * Precompiled Nunjucks template: components.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["components"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"components")),"size")) {
output += "  ";
output += runtime.suppressValue((lineno = 1, colno = 13, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "nav")),"tree"), "nav[\"tree\"]", context, [runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"components"),runtime.contextOrFrameLookup(context, frame, "entity"),runtime.contextOrFrameLookup(context, frame, "request")])), env.opts.autoescape);
output += "\n";
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
