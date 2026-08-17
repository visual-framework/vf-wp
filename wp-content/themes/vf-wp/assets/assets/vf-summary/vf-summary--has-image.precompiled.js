/**
 * Precompiled Nunjucks template: vf-summary--has-image.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-summary--has-image"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.contextOrFrameLookup(context, frame, "context")) {
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"summary__href");
frame.set("summary__href", t_1, true);
if(frame.topLevel) {
context.setVariable("summary__href", t_1);
}
if(frame.topLevel) {
context.addExport("summary__href", t_1);
}
var t_2;
t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"summary__title");
frame.set("summary__title", t_2, true);
if(frame.topLevel) {
context.setVariable("summary__title", t_2);
}
if(frame.topLevel) {
context.addExport("summary__title", t_2);
}
var t_3;
t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"summary__text");
frame.set("summary__text", t_3, true);
if(frame.topLevel) {
context.setVariable("summary__text", t_3);
}
if(frame.topLevel) {
context.addExport("summary__text", t_3);
}
var t_4;
t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"summary__image");
frame.set("summary__image", t_4, true);
if(frame.topLevel) {
context.setVariable("summary__image", t_4);
}
if(frame.topLevel) {
context.addExport("summary__image", t_4);
}
var t_5;
t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"summary__image_alt");
frame.set("summary__image_alt", t_5, true);
if(frame.topLevel) {
context.setVariable("summary__image_alt", t_5);
}
if(frame.topLevel) {
context.addExport("summary__image_alt", t_5);
}
;
}
output += "\n\n<article class=\"vf-summary vf-summary--has-image\">\n  <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "summary__href"), env.opts.autoescape);
output += "\" class=\"vf-summary__link\">\n    <img class=\"vf-summary__image vf-summary__image--thumbnail\" src=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "summary__image"), env.opts.autoescape);
output += "\" alt=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "summary__image_alt"), env.opts.autoescape);
output += "\" loading=\"lazy\">\n  </a>\n  <h3 class=\"vf-summary__title\">\n    <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "summary__href"), env.opts.autoescape);
output += "\" class=\"vf-summary__link\">\n      ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "summary__title"), env.opts.autoescape);
output += "\n    </a>\n  </h3>\n  <p class=\"vf-summary__text\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "summary__text"), env.opts.autoescape);
output += "</p>\n</article>\n";
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
