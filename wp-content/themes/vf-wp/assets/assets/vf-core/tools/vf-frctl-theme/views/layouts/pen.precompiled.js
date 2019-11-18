/**
 * Precompiled Nunjucks template: pen.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["pen"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "request")),"isPjax")?"layouts/pjax.njk":"layouts/frame.njk"), true, "pen", false, function(t_3,t_2) {
if(t_3) { cb(t_3); return; }
parentTemplate = t_2
for(var t_1 in parentTemplate.blocks) {
context.addBlock(t_1, parentTemplate.blocks[t_1]);
}
env.getTemplate("macros/image.njk", false, "pen", false, function(t_5,t_4) {
if(t_5) { cb(t_5); return; }
t_4.getExported(function(t_6,t_4) {
if(t_6) { cb(t_6); return; }
context.setVariable("img", t_4);
env.getTemplate("macros/status.njk", false, "pen", false, function(t_8,t_7) {
if(t_8) { cb(t_8); return; }
t_7.getExported(function(t_9,t_7) {
if(t_9) { cb(t_9); return; }
context.setVariable("status", t_7);
env.getTemplate("macros/errors.njk", false, "pen", false, function(t_11,t_10) {
if(t_11) { cb(t_11); return; }
t_10.getExported(function(t_12,t_10) {
if(t_12) { cb(t_12); return; }
context.setVariable("errors", t_10);
output += "\n";
var t_13;
t_13 = {"title": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"title")};
frame.set("page", t_13, true);
if(frame.topLevel) {
context.setVariable("page", t_13);
}
if(frame.topLevel) {
context.addExport("page", t_13);
}
output += "\n";
var t_14;
t_14 = (lineno = 9, colno = 24, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 9, colno = 49, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["preview",{"handle": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"handle")}]))]));
frame.set("previewUrl", t_14, true);
if(frame.topLevel) {
context.setVariable("previewUrl", t_14);
}
if(frame.topLevel) {
context.addExport("previewUrl", t_14);
}
output += "\n";
(parentTemplate ? function(e, c, f, r, cb) { cb(""); } : context.getBlock("content"))(env, context, frame, runtime, function(t_16,t_15) {
if(t_16) { cb(t_16); return; }
output += t_15;
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})})})})})})})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
function b_content(env, context, frame, runtime, cb) {
var lineno = 11;
var colno = 3;
var output = "";
try {
var frame = frame.push(true);
output += "\n";
if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"env")),"server") && runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"env")),"sync")) {
var t_17;
t_17 = false;
frame.set("rendered", t_17, true);
if(frame.topLevel) {
context.setVariable("rendered", t_17);
}
if(frame.topLevel) {
context.addExport("rendered", t_17);
}
;
}
else {
var t_18;
t_18 = env.getFilter("async").call(context, (lineno = 16, colno = 35, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"render"), "entity[\"render\"]", context, [null,runtime.contextOrFrameLookup(context, frame, "renderEnv"),{"preview": true,"collate": true}])),true);
frame.set("rendered", t_18, true);
if(frame.topLevel) {
context.setVariable("rendered", t_18);
}
if(frame.topLevel) {
context.addExport("rendered", t_18);
}
if(env.getFilter("isError").call(context, runtime.contextOrFrameLookup(context, frame, "rendered"))) {
var t_19;
t_19 = runtime.contextOrFrameLookup(context, frame, "rendered");
frame.set("error", t_19, true);
if(frame.topLevel) {
context.setVariable("error", t_19);
}
if(frame.topLevel) {
context.addExport("error", t_19);
}
var t_20;
t_20 = (function() {
var output = "";
output += runtime.suppressValue((lineno = 19, colno = 50, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "errors")),"renderError"), "errors[\"renderError\"]", context, ["component",runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"message")])), env.opts.autoescape);
;
return output;
})()
;
frame.set("renderError", t_20, true);
if(frame.topLevel) {
context.setVariable("renderError", t_20);
}
if(frame.topLevel) {
context.addExport("renderError", t_20);
}
var t_21;
t_21 = false;
frame.set("rendered", t_21, true);
if(frame.topLevel) {
context.setVariable("rendered", t_21);
}
if(frame.topLevel) {
context.addExport("rendered", t_21);
}
;
}
;
}
output += "\n<div class=\"Pen\" data-behaviour=\"pen\" id=\"pen-";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"id"), env.opts.autoescape);
output += "\">\n\n";
context.getBlock("penContent")(env, context, frame, runtime, function(t_23,t_22) {
if(t_23) { cb(t_23); return; }
output += t_22;
output += "\n</div>\n\n";
cb(null, output);
});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
function b_penContent(env, context, frame, runtime, cb) {
var lineno = 26;
var colno = 5;
var output = "";
try {
var frame = frame.push(true);
output += "\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/pen/header.njk", false, "pen", false, function(t_25,t_24) {
if(t_25) { cb(t_25); return; }
callback(null,t_24);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_27,t_26) {
if(t_27) { cb(t_27); return; }
callback(null,t_26);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
output += "\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/pen/preview.njk", false, "pen", false, function(t_29,t_28) {
if(t_29) { cb(t_29); return; }
callback(null,t_28);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_31,t_30) {
if(t_31) { cb(t_31); return; }
callback(null,t_30);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
output += "\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/pen/browser.njk", false, "pen", false, function(t_33,t_32) {
if(t_33) { cb(t_33); return; }
callback(null,t_32);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_35,t_34) {
if(t_35) { cb(t_35); return; }
callback(null,t_34);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
output += "\n";
cb(null, output);
})})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
b_content: b_content,
b_penContent: b_penContent,
root: root
};

})();
})();
