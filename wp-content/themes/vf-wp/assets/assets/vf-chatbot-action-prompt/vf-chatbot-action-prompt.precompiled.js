/**
 * Precompiled Nunjucks template: vf-chatbot-action-prompt.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-chatbot-action-prompt"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-chatbot-action-prompt\">\n";
if(runtime.contextOrFrameLookup(context, frame, "action_url")) {
output += "    <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "action_url"), env.opts.autoescape);
output += "\"\n       class=\"vf-chatbot-action-prompt__link\"\n       role=\"button\"\n";
if(runtime.contextOrFrameLookup(context, frame, "action_target")) {
output += "target=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "action_target"), env.opts.autoescape);
output += "\"";
;
}
output += ">\n      ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "action_text"), env.opts.autoescape);
output += "\n    </a>\n";
;
}
else {
output += "    <button class=\"vf-chatbot-action-prompt__link\">\n      ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "action_text"), env.opts.autoescape);
output += "\n    </button>\n";
;
}
output += "</div>\n";
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
