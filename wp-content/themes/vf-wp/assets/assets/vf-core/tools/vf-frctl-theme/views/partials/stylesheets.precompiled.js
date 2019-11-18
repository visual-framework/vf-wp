/**
 * Precompiled Nunjucks template: stylesheets.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["stylesheets"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"env")),"server")) {
output += "<link rel=\"stylesheet\" href=\"";
output += runtime.suppressValue((lineno = 1, colno = 36, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, ["/css/styles.css"])), env.opts.autoescape);
output += "?cachebust=";
output += runtime.suppressValue((lineno = 1, colno = 87, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"get"), "frctl[\"theme\"][\"get\"]", context, ["version"])), env.opts.autoescape);
output += "\" type=\"text/css\">\n";
;
}
else {
output += "<link rel=\"stylesheet\" href=\"https://dev.assets.emblstatic.net/vf/develop/css/styles.css\" type=\"text/css\">\n";
;
}
frame = frame.push();
var t_3 = (lineno = 5, colno = 36, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"get"), "frctl[\"theme\"][\"get\"]", context, ["styles"]));
if(t_3) {t_3 = runtime.fromIterator(t_3);
var t_2 = t_3.length;
for(var t_1=0; t_1 < t_3.length; t_1++) {
var t_4 = t_3[t_1];
frame.set("stylesheet", t_4);
frame.set("loop.index", t_1 + 1);
frame.set("loop.index0", t_1);
frame.set("loop.revindex", t_2 - t_1);
frame.set("loop.revindex0", t_2 - t_1 - 1);
frame.set("loop.first", t_1 === 0);
frame.set("loop.last", t_1 === t_2 - 1);
frame.set("loop.length", t_2);
output += "<link rel=\"stylesheet\" href=\"";
output += runtime.suppressValue((lineno = 6, colno = 36, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [t_4])), env.opts.autoescape);
output += "?cachebust=";
output += runtime.suppressValue((lineno = 6, colno = 80, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"get"), "frctl[\"theme\"][\"get\"]", context, ["version"])), env.opts.autoescape);
output += "\" type=\"text/css\">\n";
;
}
}
frame = frame.pop();
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
