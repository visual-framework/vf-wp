/**
 * Precompiled Nunjucks template: vf-chatbot-feedback.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-chatbot-feedback"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-chatbot-feedback__form vf-u-margin__top--400\">\n  <div class=\"vf-chatbot-feedback__form-content vf-u-padding--400\">\n    <div class=\"vf-chatbot-feedback__form-content-header\">\n      <div class=\"vf-chatbot-feedback__title\">Tell us more (optional)</div>\n      <button role=\"button\" class=\"vf-chatbot-feedback__form-close vf-button vf-button--icon vf-button--dismiss | vf-banner__button\" type=\"button\" aria-label=\"Close feedback form\" data-vf-js-feedback-form-close>\n        <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\">\n          <title>dismiss banner</title>\n          <path d=\"M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z\" />\n        </svg>\n      </button>\n    </div>\n    <div class=\"vf-chatbot-feedback__options\">\n";
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"feedback_options") || runtime.contextOrFrameLookup(context, frame, "options");
frame.set("feedback_options", t_1, true);
if(frame.topLevel) {
context.setVariable("feedback_options", t_1);
}
if(frame.topLevel) {
context.addExport("feedback_options", t_1);
}
frame = frame.push();
var t_4 = runtime.contextOrFrameLookup(context, frame, "feedback_options");
if(t_4) {t_4 = runtime.fromIterator(t_4);
var t_3 = t_4.length;
for(var t_2=0; t_2 < t_4.length; t_2++) {
var t_5 = t_4[t_2];
frame.set("option", t_5);
frame.set("loop.index", t_2 + 1);
frame.set("loop.index0", t_2);
frame.set("loop.revindex", t_3 - t_2);
frame.set("loop.revindex0", t_3 - t_2 - 1);
frame.set("loop.first", t_2 === 0);
frame.set("loop.last", t_2 === t_3 - 1);
frame.set("loop.length", t_3);
output += "        <button class=\"vf-chatbot-feedback__option\" data-feedback-option=\"";
output += runtime.suppressValue(runtime.memberLookup((t_5),"id"), env.opts.autoescape);
output += "\">\n          ";
output += runtime.suppressValue(runtime.memberLookup((t_5),"label"), env.opts.autoescape);
output += "\n        </button>\n";
;
}
}
frame = frame.pop();
output += "    </div>\n    <label id=\"vf-chatbot-feedback-comment-title\" for=\"vf-chatbot-feedback-comment\" class=\"vf-chatbot-feedback__comment-title\">Comments</label>\n    <textarea\n        id=\"vf-chatbot-feedback-comment\"\n        aria-labelledby=\"vf-chatbot-feedback-comment-title\"\n        class=\"vf-chatbot-feedback__comment\"\n        rows=\"4\"\n      ></textarea>\n    <button type=\"submit\" class=\"vf-chatbot-feedback__submit vf-u-padding--200\" data-vf-js-feedback-submit>\n        Submit\n      </button>\n  </div>\n</div>\n";
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
