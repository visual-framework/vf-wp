/**
 * Precompiled Nunjucks template: vf-intro.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-intro"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.contextOrFrameLookup(context, frame, "context")) {
output += "\n";
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"vf_intro_heading");
frame.set("vf_intro_heading", t_1, true);
if(frame.topLevel) {
context.setVariable("vf_intro_heading", t_1);
}
if(frame.topLevel) {
context.addExport("vf_intro_heading", t_1);
}
output += "\n";
var t_2;
t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"vf_intro_phase");
frame.set("vf_intro_phase", t_2, true);
if(frame.topLevel) {
context.setVariable("vf_intro_phase", t_2);
}
if(frame.topLevel) {
context.addExport("vf_intro_phase", t_2);
}
var t_3;
t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"vf_intro_heading_href");
frame.set("vf_intro_heading_href", t_3, true);
if(frame.topLevel) {
context.setVariable("vf_intro_heading_href", t_3);
}
if(frame.topLevel) {
context.addExport("vf_intro_heading_href", t_3);
}
output += "\n";
var t_4;
t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"vf_intro_lede");
frame.set("vf_intro_lede", t_4, true);
if(frame.topLevel) {
context.setVariable("vf_intro_lede", t_4);
}
if(frame.topLevel) {
context.addExport("vf_intro_lede", t_4);
}
output += "\n";
var t_5;
t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"vf_intro_text");
frame.set("vf_intro_text", t_5, true);
if(frame.topLevel) {
context.setVariable("vf_intro_text", t_5);
}
if(frame.topLevel) {
context.addExport("vf_intro_text", t_5);
}
var t_6;
t_6 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"intro_text");
frame.set("intro_text", t_6, true);
if(frame.topLevel) {
context.setVariable("intro_text", t_6);
}
if(frame.topLevel) {
context.addExport("intro_text", t_6);
}
output += "\n";
;
}
output += "\n<section class=\"vf-intro | embl-grid embl-grid--has-centered-content\">\n  <div><!-- empty --></div>\n  <div>\n\n  <h1 class=\"vf-intro__heading ";
if(runtime.contextOrFrameLookup(context, frame, "vf_intro_phase")) {
output += "vf-intro__heading--has-tag";
;
}
output += "\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_intro_heading"), env.opts.autoescape);
if((runtime.contextOrFrameLookup(context, frame, "vf_intro_phase"))) {
if((runtime.contextOrFrameLookup(context, frame, "vf_intro_heading_href")) || (runtime.contextOrFrameLookup(context, frame, "intro_heading_href"))) {
output += "      <a href=\"";
output += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "vf_intro_heading_href")) || (runtime.contextOrFrameLookup(context, frame, "intro_heading_href")), env.opts.autoescape);
output += "\" class=\"vf-badge vf-badge--primary vf-badge--phases\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_intro_phase"), env.opts.autoescape);
output += "</a>";
;
}
else {
output += "<span class=\"vf-badge vf-badge--primary vf-badge--phases\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_intro_phase"), env.opts.autoescape);
output += "</span>";
;
}
;
}
output += "</h1>\n\n";
(function(cb) {if(runtime.contextOrFrameLookup(context, frame, "vf_lede_text")) {
env.getExtension("render")["run"](context,"@vf-lede",{"vf_lede_text": runtime.contextOrFrameLookup(context, frame, "vf_intro_lede")}, function(t_8,t_7) {
if(t_8) { cb(t_8); return; }
output += runtime.suppressValue(t_7, true && env.opts.autoescape);
cb()});
}
else {
cb()}
})(function(t_9) {
if(t_9) { cb(t_9); return; }output += "\n";
(function(cb) {if(runtime.contextOrFrameLookup(context, frame, "vf_intro_text")) {
frame = frame.push();
var t_12 = runtime.fromIterator(runtime.contextOrFrameLookup(context, frame, "vf_intro_text"));
runtime.asyncEach(t_12, 1, function(intro_text, t_10, t_11,next) {
frame.set("intro_text", intro_text);
frame.set("loop.index", t_10 + 1);
frame.set("loop.index0", t_10);
frame.set("loop.revindex", t_11 - t_10);
frame.set("loop.revindex0", t_11 - t_10 - 1);
frame.set("loop.first", t_10 === 0);
frame.set("loop.last", t_10 === t_11 - 1);
frame.set("loop.length", t_11);
output += "<p class=\"vf-intro__text\">";
output += runtime.suppressValue(env.getFilter("safe").call(context, intro_text), env.opts.autoescape);
output += "</p>";
next(t_10);
;
}, function(t_14,t_13) {
if(t_14) { cb(t_14); return; }
frame = frame.pop();
cb()});
}
else {
cb()}
})(function(t_15) {
if(t_15) { cb(t_15); return; }output += "\n  </div>\n</section>\n";
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
