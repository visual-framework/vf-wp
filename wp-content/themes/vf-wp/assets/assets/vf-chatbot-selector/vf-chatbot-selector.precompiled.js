/**
 * Precompiled Nunjucks template: vf-chatbot-selector.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-chatbot-selector"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div\n  class=\"vf-chatbot-selector\"\n  data-vf-js-chatbot-selector\n";
if(runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")) {
output += "    data-routes-path=\"";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"routes"), env.opts.autoescape);
output += "\"\n    data-multiselect=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"multiSelect"),false), env.opts.autoescape);
output += "\"\n    data-max-multiselect=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"maxMultiSelect"),1), env.opts.autoescape);
output += "\"\n    data-show-search=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"showSearch"),true), env.opts.autoescape);
output += "\"\n    data-show-all-services=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"showAllServices"),false), env.opts.autoescape);
output += "\"\n    data-show-all-services-selected=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"showAllServicesSelected"),false), env.opts.autoescape);
output += "\"\n";
;
}
output += ">\n<button class=\"vf-chatbot-selector__title\" data-vf-js-selector-toggle>\n    <img src=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"selector_logo_url"),"../../assets/vf-chatbot/assets/vf-chatbot--icon-24x24-dark-green.svg"), env.opts.autoescape);
output += "\" alt=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"selector_logo_title"),"AI Assistant"), env.opts.autoescape);
output += "\">\n    <div class=\"vf-chatbot-selector__title-content vf-u-margin__left--200\">\n      <span class=\"vf-chatbot-selector__main-text\">";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"selector_logo_title"),"AI Assistant"), env.opts.autoescape);
output += "</span>\n      <span class=\"vf-chatbot-selector__title-text\">";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"title"),"Select option"), env.opts.autoescape);
output += "</span>\n    </div>\n    <span class=\"vf-chatbot-selector__chevron\">\n      <svg width=\"32\" height=\"31\" viewBox=\"0 0 32 31\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n        <g clip-path=\"url(#clip0_3647_8230)\">\n        <path d=\"M15.999 19.0975C15.7378 19.098 15.479 19.0468 15.2377 18.9468C14.9963 18.8469 14.7771 18.7001 14.5926 18.5151L8.32863 11.9279C8.21951 11.8137 8.13399 11.6791 8.07698 11.5318C8.01998 11.3845 7.99261 11.2274 7.99645 11.0695C8.00028 10.9116 8.03525 10.756 8.09934 10.6117C8.16342 10.4673 8.25537 10.337 8.36992 10.2283C8.48446 10.1195 8.61934 10.0344 8.76683 9.97791C8.91432 9.92139 9.07152 9.89454 9.2294 9.89889C9.38729 9.90325 9.54277 9.93872 9.68692 10.0033C9.83107 10.0678 9.96106 10.1602 10.0694 10.2751L15.7094 16.2143C15.7467 16.2537 15.7916 16.2851 15.8414 16.3066C15.8912 16.3281 15.9448 16.3391 15.999 16.3391C16.0533 16.3391 16.1069 16.3281 16.1567 16.3066C16.2065 16.2851 16.2514 16.2537 16.2886 16.2143L21.9286 10.2751C22.037 10.1602 22.167 10.0678 22.3112 10.0033C22.4553 9.93872 22.6108 9.90325 22.7687 9.89889C22.9266 9.89454 23.0838 9.92139 23.2312 9.97791C23.3787 10.0344 23.5136 10.1195 23.6282 10.2283C23.7427 10.337 23.8347 10.4673 23.8987 10.6117C23.9628 10.756 23.9978 10.9116 24.0016 11.0695C24.0055 11.2274 23.9781 11.3845 23.9211 11.5318C23.8641 11.6791 23.7786 11.8137 23.6694 11.9279L17.439 18.4991C17.2503 18.6888 17.0259 18.8394 16.7788 18.9421C16.5316 19.0448 16.2667 19.0976 15.999 19.0975Z\" fill=\"#707372\"/>\n        </g>\n        <defs>\n        <clipPath id=\"clip0_3647_8230\">\n        <rect width=\"16\" height=\"16\" fill=\"white\" transform=\"translate(8 6.5)\"/>\n        </clipPath>\n        </defs>\n      </svg>\n    </span>\n  </button>\n\n  <div class=\"vf-chatbot-selector__dropdown\" data-vf-js-selector-dropdown>\n\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"showSearch") && (env.getFilter("length").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"routes")) > runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"showSearchThreshold"))) {
output += "    <div class=\"vf-chatbot-selector__search\">\n      <label class=\"vf-u-sr-only\"\n        id=\"vf-chatbot-selector-search-label\"\n        for=\"vf-chatbot-selector-search\">Type to search</label>\n      <input type=\"text\" id=\"vf-chatbot-selector-search\" aria-labelledby=\"vf-chatbot-selector-search-label\" placeholder=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"placeholder"),"Search..."), env.opts.autoescape);
output += "\" data-vf-js-selector-search>\n    </div>\n";
;
}
output += "\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"multiSelect")) {
var t_1;
t_1 = env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "chatbotRoutes")),"maxMultiSelect"),3);
frame.set("maxSelect", t_1, true);
if(frame.topLevel) {
context.setVariable("maxSelect", t_1);
}
if(frame.topLevel) {
context.addExport("maxSelect", t_1);
}
output += "\n    <div class=\"vf-chatbot-selector__header\">\n      <span data-max-select=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "maxSelect"), env.opts.autoescape);
output += "\">Select up to ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "maxSelect"), env.opts.autoescape);
output += " services</span>\n      <a href=\"#\" class=\"vf-chatbot-selector__clear\" role=\"button\" data-vf-js-selector-clear>Clear all</a>\n    </div>\n";
;
}
output += "    <ul class=\"vf-chatbot-selector__list\" data-vf-js-chatbot-selector-list>\n      <!-- Routes will be populated dynamically via JavaScript -->\n    </ul>\n  </div>\n\n</div>\n";
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
