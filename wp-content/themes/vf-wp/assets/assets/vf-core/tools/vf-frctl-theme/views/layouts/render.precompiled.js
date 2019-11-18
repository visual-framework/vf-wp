/**
 * Precompiled Nunjucks template: render.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["render"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(!runtime.contextOrFrameLookup(context, frame, "entity")) {
output += runtime.suppressValue((lineno = 0, colno = 27, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "throw"), "throw", context, [404,"Component '" + runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "request")),"params")),"handle") + "' not found."])), env.opts.autoescape);
;
}
var t_1;
t_1 = env.getFilter("async").call(context, (lineno = 2, colno = 31, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"render"), "entity[\"render\"]", context, [null,runtime.contextOrFrameLookup(context, frame, "renderEnv"),{"preview": runtime.contextOrFrameLookup(context, frame, "withLayout"),"collate": runtime.contextOrFrameLookup(context, frame, "withCollation")}])),true);
frame.set("rendered", t_1, true);
if(frame.topLevel) {
context.setVariable("rendered", t_1);
}
if(frame.topLevel) {
context.addExport("rendered", t_1);
}
output += "\n";
if(env.getFilter("isError").call(context, runtime.contextOrFrameLookup(context, frame, "rendered"))) {
env.getTemplate("layouts/frame.njk", true, "render", false, function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
parentTemplate = t_3
for(var t_2 in parentTemplate.blocks) {
context.addBlock(t_2, parentTemplate.blocks[t_2]);
}
var t_5;
t_5 = runtime.contextOrFrameLookup(context, frame, "rendered");
frame.set("error", t_5, true);
if(frame.topLevel) {
context.setVariable("error", t_5);
}
if(frame.topLevel) {
context.addExport("error", t_5);
}
var t_6;
t_6 = {"title": "Error rendering component " + runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "request")),"params")),"handle")};
frame.set("page", t_6, true);
if(frame.topLevel) {
context.setVariable("page", t_6);
}
if(frame.topLevel) {
context.addExport("page", t_6);
}
(parentTemplate ? function(e, c, f, r, cb) { cb(""); } : context.getBlock("content"))(env, context, frame, runtime, function(t_8,t_7) {
if(t_8) { cb(t_8); return; }
output += t_7;
})});
}
else {
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "rendered"), env.opts.autoescape);
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
function b_content(env, context, frame, runtime, cb) {
var lineno = 10;
var colno = 3;
var output = "";
try {
var frame = frame.push(true);
env.getTemplate("macros/errors.njk", false, "render", false, function(t_10,t_9) {
if(t_10) { cb(t_10); return; }
t_9.getExported(function(t_11,t_9) {
if(t_11) { cb(t_11); return; }
context.setVariable("errors", t_9);
output += runtime.suppressValue((lineno = 10, colno = 84, runtime.callWrap(runtime.memberLookup((t_9),"renderError"), "errors[\"renderError\"]", context, ["component",runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"message"),runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"stack")])), env.opts.autoescape);
cb(null, output);
})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
b_content: b_content,
root: root
};

})();
})();
