/**
 * Precompiled Nunjucks template: embl-grid.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["embl-grid"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.contextOrFrameLookup(context, frame, "context")) {
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"embl_grid__type");
frame.set("embl_grid__type", t_1, true);
if(frame.topLevel) {
context.setVariable("embl_grid__type", t_1);
}
if(frame.topLevel) {
context.addExport("embl_grid__type", t_1);
}
var t_2;
t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"embl_grid__hairline");
frame.set("embl_grid__hairline", t_2, true);
if(frame.topLevel) {
context.setVariable("embl_grid__hairline", t_2);
}
if(frame.topLevel) {
context.addExport("embl_grid__hairline", t_2);
}
var t_3;
t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"embl_grid__modifier");
frame.set("embl_grid__modifier", t_3, true);
if(frame.topLevel) {
context.setVariable("embl_grid__modifier", t_3);
}
if(frame.topLevel) {
context.addExport("embl_grid__modifier", t_3);
}
;
}
output += "\n<div class=\"embl-grid";
if(runtime.contextOrFrameLookup(context, frame, "embl_grid__type") == "centered") {
output += " embl-grid--has-centered-content";
;
}
else {
if(runtime.contextOrFrameLookup(context, frame, "embl_grid__type") == "sidebar") {
output += " embl-grid--has-sidebar";
;
}
;
}
if(runtime.contextOrFrameLookup(context, frame, "embl_grid__hairline")) {
output += " embl-grid-has-sidebar--hairline";
;
}
if(runtime.contextOrFrameLookup(context, frame, "embl_grid__modifier")) {
output += " | ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "embl_grid__modifier"), env.opts.autoescape);
;
}
output += "\">\n";
(parentTemplate ? function(e, c, f, r, cb) { cb(""); } : context.getBlock("embl_grid__content"))(env, context, frame, runtime, function(t_5,t_4) {
if(t_5) { cb(t_5); return; }
output += t_4;
output += "</div>\n";
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
function b_embl_grid__content(env, context, frame, runtime, cb) {
var lineno = 12;
var colno = 5;
var output = "";
try {
var frame = frame.push(true);
cb(null, output);
;
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
b_embl_grid__content: b_embl_grid__content,
root: root
};

})();
})();
