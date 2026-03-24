/**
 * Precompiled Nunjucks template: vf-chatbot-dialog.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-chatbot-dialog"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-chatbot-dialog\" data-vf-js-chatbot-dialog>\n  <div class=\"vf-chatbot-dialog__content\">\n    <div class=\"vf-chatbot-dialog__header vf-u-margin__bottom--400\">\n      <h2 class=\"vf-chatbot-dialog__title\">";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "title"),"Close chat and delete conversation?"), env.opts.autoescape);
output += "</h2>\n      <button class=\"vf-chatbot-dialog__close\" data-vf-js-dialog-close aria-label=\"Close dialog\">\n        <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\">\n          <path d=\"M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z\"/>\n        </svg>\n      </button>\n    </div>\n\n    <div class=\"vf-chatbot-dialog__body vf-u-margin__bottom--800\">\n      <p class=\"vf-text vf-text-body--3\">";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "message"),"Are you sure you want to close the chat? <br>Your current conversation history will be permanently deleted."), env.opts.autoescape);
output += "</p>\n    </div>\n\n    <div class=\"vf-chatbot-dialog__actions\">\n      <button class=\"vf-chatbot-dialog__button vf-chatbot-dialog__button--outline\" data-vf-js-dialog-cancel>\n        ";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "cancelLabel"),"Keep chat open"), env.opts.autoescape);
output += "\n      </button>\n      <button class=\"vf-chatbot-dialog__button vf-chatbot-dialog__button--primary\" data-vf-js-dialog-confirm>\n        ";
output += runtime.suppressValue(env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "confirmLabel"),"Close and delete"), env.opts.autoescape);
output += "\n      </button>\n    </div>\n  </div>\n</div>\n";
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
