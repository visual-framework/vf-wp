/**
 * Precompiled Nunjucks template: panel-html.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["panel-html"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate("macros/render.njk", false, "panel-html", false, function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
t_1.getExported(function(t_3,t_1) {
if(t_3) { cb(t_3); return; }
context.setVariable("render", t_1);
if(!((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"preview") == "@preview--blocks") || (runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"preview") == "@preview--elements"))) {
output += "<h3 class=\"vf-text vf-text-heading--4\">Code example</h3>\n\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"isVariant") || runtime.memberLookup(((lineno = 4, colno = 43, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"variants"), "entity[\"variants\"]", context, []))),"size") == 1) {
output += "  <pre class=\"vf-code-example__pre\"><code class=\"Code Code--lang-html vf-code-example\">";
var t_4;
t_4 = env.getFilter("trim").call(context, (lineno = 6, colno = 36, runtime.callWrap(runtime.memberLookup((t_1),"entity"), "render[\"entity\"]", context, [env.getFilter("async").call(context, (lineno = 6, colno = 50, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"render"), "entity[\"render\"]", context, [null,runtime.contextOrFrameLookup(context, frame, "renderEnv"),{"preview": false,"collate": false},runtime.contextOrFrameLookup(context, frame, "entity")])),true)])));
frame.set("renderedItem", t_4, true);
if(frame.topLevel) {
context.setVariable("renderedItem", t_4);
}
if(frame.topLevel) {
context.addExport("renderedItem", t_4);
}
if(runtime.contextOrFrameLookup(context, frame, "renderedItem") != "") {
output += runtime.suppressValue(env.getFilter("trim").call(context, runtime.contextOrFrameLookup(context, frame, "renderedItem")), env.opts.autoescape);
output += "\n";
;
}
else {
output += runtime.suppressValue(env.getFilter("highlight").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"content"),"html"), env.opts.autoescape);
output += "\n";
;
}
output += "</code></pre>\n";
;
}
else {
frame = frame.push();
var t_7 = (lineno = 14, colno = 43, runtime.callWrap(runtime.memberLookup(((lineno = 14, colno = 35, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"variants"), "entity[\"variants\"]", context, []))),"items"), "the return value of (entity[\"variants\"])[\"items\"]", context, []));
if(t_7) {t_7 = runtime.fromIterator(t_7);
var t_6 = t_7.length;
for(var t_5=0; t_5 < t_7.length; t_5++) {
var t_8 = t_7[t_5];
frame.set("variant", t_8);
frame.set("loop.index", t_5 + 1);
frame.set("loop.index0", t_5);
frame.set("loop.revindex", t_6 - t_5);
frame.set("loop.revindex0", t_6 - t_5 - 1);
frame.set("loop.first", t_5 === 0);
frame.set("loop.last", t_5 === t_6 - 1);
frame.set("loop.length", t_6);
output += "<pre class=\"vf-code-example__pre\"><code class=\"Code Code--lang-html vf-code-example\">";
output += runtime.suppressValue("<span class=\"hljs-comment\">&lt;!-- " + runtime.memberLookup((t_8),"label") + " --&gt;</span>", env.opts.autoescape);
output += "\n";
output += runtime.suppressValue(env.getFilter("trim").call(context, (lineno = 16, colno = 16, runtime.callWrap(runtime.memberLookup((t_1),"entity"), "render[\"entity\"]", context, [env.getFilter("async").call(context, (lineno = 16, colno = 31, runtime.callWrap(runtime.memberLookup((t_8),"render"), "variant[\"render\"]", context, [null,runtime.contextOrFrameLookup(context, frame, "renderEnv"),{"preview": false,"collate": false}])),true)]))), env.opts.autoescape);
output += "\n</code></pre>\n";
;
}
}
frame = frame.pop();
;
}
;
}
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
