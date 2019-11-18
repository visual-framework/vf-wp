/**
 * Precompiled Nunjucks template: vf-card.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-card"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.contextOrFrameLookup(context, frame, "href")) {
var t_1;
t_1 = "a";
frame.set("tags", t_1, true);
if(frame.topLevel) {
context.setVariable("tags", t_1);
}
if(frame.topLevel) {
context.addExport("tags", t_1);
}
;
}
else {
var t_2;
t_2 = "div";
frame.set("tags", t_2, true);
if(frame.topLevel) {
context.setVariable("tags", t_2);
}
if(frame.topLevel) {
context.addExport("tags", t_2);
}
;
}
output += "\n\n";
output += "\n<";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
output += "\n";
if(runtime.contextOrFrameLookup(context, frame, "tags") == "a") {
output += " href=\"";
output += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "card_href")?runtime.contextOrFrameLookup(context, frame, "card_href"):"#"), env.opts.autoescape);
output += "\"";
;
}
output += "\n  ";
if(runtime.contextOrFrameLookup(context, frame, "id")) {
output += " id=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
output += "\"";
;
}
output += "\n  ";
output += "\n  class=\"vf-card";
if(runtime.contextOrFrameLookup(context, frame, "modifier")) {
output += " ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "modifier"), env.opts.autoescape);
;
}
if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
output += " | ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
;
}
output += "\">\n  <img src=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "image"), env.opts.autoescape);
output += "\" alt=\"\" class=\"vf-card__image\">\n  <div class=\"vf-card__content\">\n    <h3 class=\"vf-card__title\">\n      ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "title"), env.opts.autoescape);
output += "\n    </h3>\n    <p class=\"vf-card__text\">\n      ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "text"), env.opts.autoescape);
output += "\n    </p>\n  </div>\n</";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
output += ">\n";
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
