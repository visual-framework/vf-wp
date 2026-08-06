/**
 * Precompiled Nunjucks template: vf-chatbot-welcome.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-chatbot-welcome"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div role=\"region\" aria-label=\"Chatbot welcome screen\" \n     class=\"vf-chatbot-welcome\"\n     data-vf-js-chatbot-welcome\n     data-max-questions=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "welcome_max_suggestions"),4), env.opts.autoescape);
output += "\"\n     data-enable-qa-data-loading=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "enable_qa_data_loading"),true), env.opts.autoescape);
output += "\"\n     data-enable-predefined-qa=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "enable_predefined_qa"),true), env.opts.autoescape);
output += "\"\n     data-enable-fallback-responses=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "enable_fallback_responses"),true), env.opts.autoescape);
output += "\"\n     data-qa-data-url=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "qa_data_url"), env.opts.autoescape);
output += "\">\n  <div class=\"vf-chatbot-welcome__content\">\n";
if(runtime.contextOrFrameLookup(context, frame, "welcome_logo")) {
output += "      <div class=\"vf-chatbot-welcome__logo\">\n        <img src=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "welcome_logo_url"), env.opts.autoescape);
output += "\" alt=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "welcome_logo_alt"),"AI assistant logo"), env.opts.autoescape);
output += "\">\n      </div>\n";
;
}
output += "    <h1 class=\"vf-chatbot-welcome__title\">";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "welcome_title"),"AI assistant"), env.opts.autoescape);
output += "</h1>\n    <div class=\"vf-chatbot-welcome__message\">\n      ";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "welcome_message"),"Welcome! I'm here to help"), env.opts.autoescape);
output += "\n    </div>\n  </div>\n";
if(runtime.contextOrFrameLookup(context, frame, "enable_welcome_suggestions")) {
output += "    <div class=\"vf-chatbot-welcome__suggestions\">\n";
if(runtime.contextOrFrameLookup(context, frame, "welcome_suggestions_title")) {
output += "        <p class=\"vf-chatbot-welcome__suggestions-title vf-u-margin__bottom--200\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "welcome_suggestions_title"), env.opts.autoescape);
output += "</p>\n";
;
}
output += "      <div class=\"vf-chatbot-welcome__suggestions-grid\" data-vf-js-chatbot-welcome-suggestions-grid>\n        <!-- Suggestions will be populated dynamically from qa.json using the template below -->\n      </div>\n    </div>\n";
;
}
output += "\n  <!-- Template for welcome suggestions using vf-chatbot-action-prompt -->\n  <template id=\"welcome-suggestion-template\">\n";
env.getExtension("render")["run"](context,"@vf-chatbot-action-prompt",{"action_text": "","action_url": "#"}, function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
output += "  </template>\n</div>\n";
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
