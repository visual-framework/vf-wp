/**
 * Precompiled Nunjucks template: vf-back-to-top.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-back-to-top"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "\n";
if(runtime.contextOrFrameLookup(context, frame, "context")) {
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"type");
frame.set("type", t_1, true);
if(frame.topLevel) {
context.setVariable("type", t_1);
}
if(frame.topLevel) {
context.addExport("type", t_1);
}
var t_2;
t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"text");
frame.set("text", t_2, true);
if(frame.topLevel) {
context.setVariable("text", t_2);
}
if(frame.topLevel) {
context.addExport("text", t_2);
}
var t_3;
t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"scrollToId");
frame.set("scrollToId", t_3, true);
if(frame.topLevel) {
context.setVariable("scrollToId", t_3);
}
if(frame.topLevel) {
context.addExport("scrollToId", t_3);
}
var t_4;
t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"example");
frame.set("example", t_4, true);
if(frame.topLevel) {
context.setVariable("example", t_4);
}
if(frame.topLevel) {
context.addExport("example", t_4);
}
;
}
output += "\n";
if(!runtime.contextOrFrameLookup(context, frame, "text")) {
var t_5;
t_5 = "Back to top";
frame.set("text", t_5, true);
if(frame.topLevel) {
context.setVariable("text", t_5);
}
if(frame.topLevel) {
context.addExport("text", t_5);
}
;
}
output += "\n";
(function(cb) {if(runtime.contextOrFrameLookup(context, frame, "example") == true) {
output += "  <!-- this HTML is for example use only -->\n  <div class=\"\" style=\"position: relative;\" id=\"top\">\n";
env.getExtension("render")["run"](context,"@vf-content", function(t_7,t_6) {
if(t_7) { cb(t_7); return; }
output += runtime.suppressValue(t_6, true && env.opts.autoescape);
cb()});
}
else {
cb()}
})(function(t_8) {
if(t_8) { cb(t_8); return; }output += "\n<div class=\"vf-back-to-top vf-back-top--";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "type"), env.opts.autoescape);
output += "\" data-vf-js-back-to-top ";
if(runtime.contextOrFrameLookup(context, frame, "type") === "floating") {
output += " data-vf-js-back-to-top-floating ";
;
}
output += ">\n  <a ";
if(runtime.contextOrFrameLookup(context, frame, "scrollToId")) {
output += "href=\"";
output += runtime.suppressValue("#" + runtime.contextOrFrameLookup(context, frame, "scrollToId"), env.opts.autoescape);
output += "\" data-scroll-to-id=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "scrollToId"), env.opts.autoescape);
output += "\" ";
;
}
output += "class=\"vf-button vf-button--secondary vf-button--sm\" aria-label=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "text"), env.opts.autoescape);
output += "\">\n    <svg class=\"vf-icon vf-icon--search-btn | vf-button__icon\" viewBox=\"0 0 140 140\" width=\"16\" height=\"16\">\n      <g transform=\"matrix(5.833333333333333,0,0,5.833333333333333,0,0)\">\n        <path d=\"M23.421,11.765,13.768.8A2.641,2.641,0,0,0,12,0a2.645,2.645,0,0,0-1.768.8L.579,11.765A1.413,1.413,0,1,0,2.7,13.632l7.45-8.466a.25.25,0,0,1,.437.166V22.587a1.413,1.413,0,1,0,2.826,0V5.332a.25.25,0,0,1,.438-.165L21.3,13.632a1.413,1.413,0,1,0,2.121-1.867Z\" fill=\"#3b6fb6\" stroke=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"0\"></path>\n      </g>\n    </svg>\n    ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "text"), env.opts.autoescape);
output += "\n  </a>\n</div>\n\n";
if(runtime.contextOrFrameLookup(context, frame, "example") == true) {
output += "  <!-- this HTML is for example use only -->\n  </div>\n";
;
}
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
return {
root: root
};

})();
})();
