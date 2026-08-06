/**
 * Precompiled Nunjucks template: vf-chatbot-standalone.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-chatbot-standalone"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<!-- Standalone Chatbot -->\n<div class=\"vf-content vf-chatbot-standalone-container\"\n     data-vf-js-chatbot-standalone-container\n     data-vf-chatbot-config=\"";
output += runtime.suppressValue(env.getFilter("escape").call(context, env.getFilter("dump").call(context, runtime.contextOrFrameLookup(context, frame, "config"))), env.opts.autoescape);
output += "\">\n\n  <div class=\"vf-chatbot-standalone__header\">\n    <div class=\"vf-chatbot-standalone__header-left\">\n";
(function(cb) {if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"selectorContext")) {
env.getExtension("render")["run"](context,"@vf-chatbot-selector",runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"selectorContext"), function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
cb()});
}
else {
output += "        <div class=\"vf-chatbot-selector\">\n          <div class=\"vf-chatbot-selector__title\">\n            <img src=\"";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"selectorContext")),"selector_logo_url"), env.opts.autoescape);
output += "\" alt=\"";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"selectorContext")),"selector_logo_title"), env.opts.autoescape);
output += "\">\n            <div class=\"vf-chatbot-selector__title-content\">\n              <span class=\"vf-chatbot-selector__main-text\">";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"selectorContext")),"selector_logo_title"), env.opts.autoescape);
output += "</span>\n            </div>\n          </div>\n        </div>\n";
cb();
}
})(function(t_3) {
if(t_3) { cb(t_3); return; }output += "    </div>\n  </div>\n\n  <div class=\"vf-chatbot-standalone | vf-u-background-color-ui--grey--light vf-u-margin__bottom--400\" data-vf-js-chatbot-standalone>\n    <div class=\"vf-chatbot-standalone__content\" data-vf-js-chatbot-standalone-content>\n\n      <!-- Welcome Screen -->\n";
(function(cb) {if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_welcome")) {
env.getExtension("render")["run"](context,"@vf-chatbot-welcome",{"welcome_logo": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"welcome_logo"),"welcome_logo_url": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"icons")),"main_logo_url"),"welcome_logo_alt": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"welcome_logo_alt"),"welcome_title": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"title"),"welcome_message": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"welcome_message"),"welcome_suggestions_title": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"welcome_suggestions_title"),"enable_welcome_suggestions": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_welcome_suggestions"),"welcome_max_suggestions": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"welcome_max_suggestions"),"qa_data_url": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"api")),"qa_data_url"),"enable_welcome_suggestions": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_welcome_suggestions"),"enable_qa_data_loading": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_qa_data_loading"),"enable_predefined_qa": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_predefined_qa"),"enable_fallback_responses": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_fallback_responses")}, function(t_5,t_4) {
if(t_5) { cb(t_5); return; }
output += runtime.suppressValue(t_4, true && env.opts.autoescape);
cb()});
}
else {
cb()}
})(function(t_6) {
if(t_6) { cb(t_6); return; }output += "\n      <!-- Messages Container -->\n      <div class=\"";
output += runtime.suppressValue((runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"behavior")),"show_scrollbar") == false?"vf-chatbot-standalone__messages-no-scrollbar":"vf-chatbot-standalone__messages"), env.opts.autoescape);
output += "\"           data-vf-js-chatbot-standalone-messages\n           data-auto-scroll=\"";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"behavior")),"auto_scroll"), env.opts.autoescape);
output += "\">\n        <!-- Messages will be added here dynamically -->\n      </div>\n\n      <!-- Disclaimer Banner -->\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"disclaimer") && runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_disclaimer")) {
output += "        <div class=\"vf-chatbot-standalone__disclaimer\" data-vf-js-chatbot-standalone-disclaimer>\n          <div class=\"vf-banner vf-banner--alert vf-banner--info\">\n            <div class=\"vf-banner__content\">\n              <p class=\"vf-banner__text\">";
output += runtime.suppressValue(env.getFilter("safe").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"disclaimer")), env.opts.autoescape);
output += "</p>\n              <button role=\"button\" aria-label=\"close notification banner\"\n                      class=\"vf-button vf-button--icon vf-button--dismiss | vf-banner__button\">\n                <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\">\n                  <title>dismiss banner</title>\n                  <path d=\"M14.3,12.179a.25.25,0,0,1,0-.354l9.263-9.262A1.5,1.5,0,0,0,21.439.442L12.177,9.7a.25.25,0,0,1-.354,0L2.561.442A1.5,1.5,0,0,0,.439,2.563L9.7,11.825a.25.25,0,0,1,0,.354L.439,21.442a1.5,1.5,0,0,0,2.122,2.121L11.823,14.3a.25.25,0,0,1,.354,0l9.262,9.263a1.5,1.5,0,0,0,2.122-2.121Z\"/>\n                </svg>\n              </button>\n            </div>\n          </div>\n        </div>\n";
;
}
output += "    </div>\n\n    <!-- Input Container -->\n    <div class=\"vf-chatbot-standalone__input-container\">\n      <div class=\"vf-chatbot-standalone__input-wrapper\">\n        <label class=\"vf-u-sr-only\"\n          id=\"vf-chatbot-standalone-input-label\"\n          for=\"vf-chatbot-standalone-input\">Ask me</label>\n        <textarea\n          id=\"vf-chatbot-standalone-input\"\n          aria-labelledby=\"vf-chatbot-standalone-input-label\"\n          class=\"vf-chatbot-standalone__input vf-form__textarea vf-u-padding__left--400\"\n          placeholder=\"";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"input_placeholder"), env.opts.autoescape);
output += "\"\n          rows=\"1\"\n          data-vf-js-chatbot-standalone-input\n        ></textarea>\n        <button\n          class=\"vf-chatbot-standalone__send-button\"\n          aria-label=\"Send message\"\n          data-vf-js-chatbot-standalone-send\n        >\n          <img src=\"";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"icons")),"send_button"), env.opts.autoescape);
output += "\" alt=\"Send\">\n        </button>\n      </div>\n\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"footnote")) {
output += "      <div class=\"vf-chatbot-standalone__footnote vf-u-margin__top--200\" data-vf-js-chatbot-standalone-footnote>\n        ";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"footnote"), env.opts.autoescape);
output += "\n      </div>\n";
;
}
output += "    </div>\n\n    <!-- Templates -->\n";
(function(cb) {if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_feedback")) {
output += "    <template id=\"feedback-positive-template\">\n";
env.getExtension("render")["run"](context,"@vf-chatbot-feedback--positive", function(t_8,t_7) {
if(t_8) { cb(t_8); return; }
output += runtime.suppressValue(t_7, true && env.opts.autoescape);
output += "    </template>\n    <template id=\"feedback-negative-template\">\n";
env.getExtension("render")["run"](context,"@vf-chatbot-feedback--negative", function(t_10,t_9) {
if(t_10) { cb(t_10); return; }
output += runtime.suppressValue(t_9, true && env.opts.autoescape);
output += "    </template>\n";
cb()})});
}
else {
cb()}
})(function(t_11) {
if(t_11) { cb(t_11); return; }output += "\n    <template id=\"user-message-template\">\n";
env.getExtension("render")["run"](context,"@vf-chatbot-prompt",{"type": "user","avatar": {"src": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"icons")),"user_avatar"),"alt": "You","name": "You"},"content": "Hello!"}, function(t_13,t_12) {
if(t_13) { cb(t_13); return; }
output += runtime.suppressValue(t_12, true && env.opts.autoescape);
output += "    </template>\n\n    <template id=\"assistant-message-template\">\n";
env.getExtension("render")["run"](context,"@vf-chatbot-prompt",{"type": "assistant","avatar": {"src": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"icons")),"assistant_avatar"),"alt": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"title"),"name": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"title")},"content": "How can I help you?","sources": runtime.contextOrFrameLookup(context, frame, "sources"),"prompts": runtime.contextOrFrameLookup(context, frame, "prompts"),"allowFeedback": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_feedback")}, function(t_15,t_14) {
if(t_15) { cb(t_15); return; }
output += runtime.suppressValue(t_14, true && env.opts.autoescape);
output += "    </template>\n\n";
(function(cb) {if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"features")),"enable_typing_indicator")) {
output += "    <template id=\"loading-indicator-template\">\n";
env.getExtension("render")["run"](context,"@vf-chatbot-prompt",{"type": "assistant","isLoading": true,"avatar": {"src": runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"icons")),"assistant_avatar"),"alt": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"title"),"name": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"title")}}, function(t_17,t_16) {
if(t_17) { cb(t_17); return; }
output += runtime.suppressValue(t_16, true && env.opts.autoescape);
output += "    </template>\n";
cb()});
}
else {
cb()}
})(function(t_18) {
if(t_18) { cb(t_18); return; }output += "\n    <template id=\"action-prompts-template\">\n      <div class=\"vf-chatbot-action-prompts vf-u-margin__top--400\">\n        <div class=\"vf-chatbot-action-prompts__list\" data-vf-js-action-prompts-list>\n          <!-- Individual prompts will be populated here -->\n        </div>\n      </div>\n    </template>\n\n    <template id=\"single-action-prompt-template\">\n";
env.getExtension("render")["run"](context,"@vf-chatbot-action-prompt",{"action_text": "","action_url": "#"}, function(t_20,t_19) {
if(t_20) { cb(t_20); return; }
output += runtime.suppressValue(t_19, true && env.opts.autoescape);
output += "    </template>\n  </div>\n</div>\n";
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})})})})})})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
