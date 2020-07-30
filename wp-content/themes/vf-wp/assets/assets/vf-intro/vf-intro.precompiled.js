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
if((runtime.contextOrFrameLookup(context, frame, "intro_heading_href"))) {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "intro_heading_href"), env.opts.autoescape);
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
output += "</h1>";
env.getExtension("render")["run"](context,"@vf-lede",{"vf_lede_text": runtime.contextOrFrameLookup(context, frame, "vf_intro_lede")}, function(t_8,t_7) {
if(t_8) { cb(t_8); return; }
output += runtime.suppressValue(t_7, true && env.opts.autoescape);
frame = frame.push();
var t_11 = runtime.fromIterator(runtime.contextOrFrameLookup(context, frame, "vf_intro_text"));
runtime.asyncEach(t_11, 1, function(intro_text, t_9, t_10,next) {
frame.set("intro_text", intro_text);
frame.set("loop.index", t_9 + 1);
frame.set("loop.index0", t_9);
frame.set("loop.revindex", t_10 - t_9);
frame.set("loop.revindex0", t_10 - t_9 - 1);
frame.set("loop.first", t_9 === 0);
frame.set("loop.last", t_9 === t_10 - 1);
frame.set("loop.length", t_10);
output += "<p class=\"vf-intro__text\">";
output += runtime.suppressValue(env.getFilter("safe").call(context, intro_text), env.opts.autoescape);
output += "</p>";
next(t_9);
;
}, function(t_13,t_12) {
if(t_13) { cb(t_13); return; }
frame = frame.pop();
output += "</div>\n</section>\n";
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
