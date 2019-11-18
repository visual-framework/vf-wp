/**
 * Precompiled Nunjucks template: assets.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["assets"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.memberLookup(((lineno = 0, colno = 26, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"assets")),"visible"), "frctl[\"assets\"][\"visible\"]", context, []))),"length")) {
output += "  <h3 class=\"vf-text vf-text--l\">";
output += runtime.suppressValue((lineno = 1, colno = 45, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"get"), "frctl[\"get\"]", context, ["assets.label"])), env.opts.autoescape);
output += "</h3>\n";
frame = frame.push();
var t_3 = (lineno = 2, colno = 37, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"assets")),"visible"), "frctl[\"assets\"][\"visible\"]", context, []));
if(t_3) {t_3 = runtime.fromIterator(t_3);
var t_2 = t_3.length;
for(var t_1=0; t_1 < t_3.length; t_1++) {
var t_4 = t_3[t_1];
frame.set("item", t_4);
frame.set("loop.index", t_1 + 1);
frame.set("loop.index0", t_1);
frame.set("loop.revindex", t_2 - t_1);
frame.set("loop.revindex0", t_2 - t_1 - 1);
frame.set("loop.first", t_1 === 0);
frame.set("loop.last", t_1 === t_2 - 1);
frame.set("loop.length", t_2);
output += "    <a class=\"vf-link\" href=\"";
output += runtime.suppressValue((lineno = 3, colno = 36, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(env.getFilter("url").call(context, t_4)),runtime.contextOrFrameLookup(context, frame, "request")])), env.opts.autoescape);
output += "\">\n      <span>";
output += runtime.suppressValue(runtime.memberLookup((t_4),"label"), env.opts.autoescape);
output += "</span>\n    </a>\n";
;
}
}
frame = frame.pop();
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
