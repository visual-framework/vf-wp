/**
 * Precompiled Nunjucks template: vf-chatbot-prompt.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-chatbot-prompt"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-chatbot-message vf-chatbot-message--";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "type"), env.opts.autoescape);
if(runtime.contextOrFrameLookup(context, frame, "isLoading")) {
output += " vf-chatbot-message--loading";
;
}
output += "  vf-u-margin__top--400\">\n  <div class=\"vf-chatbot-message__avatar vf-u-margin__bottom--200\">\n";
if(runtime.contextOrFrameLookup(context, frame, "avatar") && runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "avatar")),"name") && runtime.contextOrFrameLookup(context, frame, "type") === "user") {
output += "      <span class=\"vf-chatbot-message__avatar-name\">";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "avatar")),"name"), env.opts.autoescape);
output += "</span>\n";
;
}
if(runtime.contextOrFrameLookup(context, frame, "avatar") && runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "avatar")),"src")) {
output += "      <img src=\"";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "avatar")),"src"), env.opts.autoescape);
output += "\" alt=\"";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "avatar")),"alt"), env.opts.autoescape);
output += "\">\n";
;
}
if(runtime.contextOrFrameLookup(context, frame, "avatar") && runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "avatar")),"name") && runtime.contextOrFrameLookup(context, frame, "type") === "assistant") {
output += "      <span class=\"vf-chatbot-message__avatar-name\">";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "avatar")),"name"), env.opts.autoescape);
output += "</span>\n";
;
}
output += "  </div>\n  <div class=\"vf-chatbot-message__content vf-u-padding--200\">\n";
if(runtime.contextOrFrameLookup(context, frame, "isLoading")) {
output += "      <div class=\"vf-chatbot-message__content-loading-dots\" aria-label=\"Loading\" role=\"status\">\n        <span class=\"vf-chatbot-message__dot\"></span>\n        <span class=\"vf-chatbot-message__dot\"></span>\n        <span class=\"vf-chatbot-message__dot\"></span>\n      </div>\n";
;
}
else {
output += "      <div class=\"vf-chatbot-message__content-prompt vf-u-padding__left--200  vf-u-padding__right--200\">\n        ";
output += runtime.suppressValue(env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "content")), env.opts.autoescape);
output += "\n      </div>\n";
;
}
output += "  </div>\n</div>\n";
if(runtime.contextOrFrameLookup(context, frame, "allowFeedback") && runtime.contextOrFrameLookup(context, frame, "allowFeedback") == true > 0) {
output += "  <div class=\"vf-chatbot-feedback vf-u-margin__top--200\" data-vf-js-chatbot-feedback></div>\n";
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
