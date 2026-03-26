/**
 * Precompiled Nunjucks template: index.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["index"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate("layouts/vf-components.njk", true, "index", false, function(t_3,t_2) {
if(t_3) { cb(t_3); return; }
parentTemplate = t_2
for(var t_1 in parentTemplate.blocks) {
context.addBlock(t_1, parentTemplate.blocks[t_1]);
}
output += "\n";
(parentTemplate ? function(e, c, f, r, cb) { cb(""); } : context.getBlock("content"))(env, context, frame, runtime, function(t_5,t_4) {
if(t_5) { cb(t_5); return; }
output += t_4;
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
function b_content(env, context, frame, runtime, cb) {
var lineno = 2;
var colno = 3;
var output = "";
try {
var frame = frame.push(true);
output += "<div class=\"vf-grid\">\n  <div class=\"vf-grid__col--span-8\">\n    <h1>Chatbot Components</h1>\n    <p class=\"vf-lede\">Interactive AI chatbot components for Visual Framework</p>\n    <p>These components provide various chatbot UI elements and interactions:</p>\n\n    <h2>Main Components</h2>\n    <ul class=\"vf-list\">\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-standalone"), env.opts.autoescape);
output += "\">Standalone Chatbot</a> - A full-page chatbot interface\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-modal"), env.opts.autoescape);
output += "\">Modal Chatbot</a> - A pop-up chatbot dialog\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-fab"), env.opts.autoescape);
output += "\">Floating Action Button</a> - Trigger button for the modal chatbot\n      </li>\n    </ul>\n\n    <h2>Shared Components</h2>\n    <ul class=\"vf-list\">\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-prompt"), env.opts.autoescape);
output += "\">Chatbot Prompt</a> - Message bubbles\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-welcome"), env.opts.autoescape);
output += "\">Welcome Screen</a> - Initial chatbot greeting\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-feedback"), env.opts.autoescape);
output += "\">Feedback Controls</a> - Thumbs up/down elements\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-sources"), env.opts.autoescape);
output += "\">Sources Component</a> - Citation links\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-action-prompt"), env.opts.autoescape);
output += "\">Action Prompts</a> - Suggested actions\n      </li>\n      <li class=\"vf-list__item\">\n        <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/components/detail/vf-chatbot-selector"), env.opts.autoescape);
output += "\">Selector Component</a> - Service selection\n      </li>\n    </ul>\n\n    <h2>Integration Examples</h2>\n    <div class=\"vf-grid vf-grid__col-3\">\n      <div class=\"vf-card\">\n        <div class=\"vf-card__content\">\n          <h3 class=\"vf-card__title\">\n            <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/demos/chatbot-standalone"), env.opts.autoescape);
output += "\">Standalone Demo</a>\n          </h3>\n          <p class=\"vf-card__text\">Full page chatbot implementation</p>\n        </div>\n      </div>\n\n      <div class=\"vf-card\">\n        <div class=\"vf-card__content\">\n          <h3 class=\"vf-card__title\">\n            <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/demos/chatbot-modal"), env.opts.autoescape);
output += "\">Modal Demo</a>\n          </h3>\n          <p class=\"vf-card__text\">Pop-up chatbot with FAB trigger</p>\n        </div>\n      </div>\n\n      <div class=\"vf-card\">\n        <div class=\"vf-card__content\">\n          <h3 class=\"vf-card__title\">\n            <a href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/demos/chatbot-api-integration"), env.opts.autoescape);
output += "\">API Integration</a>\n          </h3>\n          <p class=\"vf-card__text\">Connecting to backend services</p>\n        </div>\n      </div>\n    </div>\n  </div>\n\n  <div class=\"vf-grid__col--span-4\">\n    <div class=\"vf-box vf-box--normal vf-stack\">\n      <h3 class=\"vf-stack__heading\">Resources</h3>\n      <ul class=\"vf-list\">\n        <li class=\"vf-list__item\">\n          <a href=\"https://github.com/visual-framework/vf-core-esm-new/tree/develop/components/vf-chatbot\" class=\"vf-list__link\">\n            Chatbot source code\n          </a>\n        </li>\n        <li class=\"vf-list__item\">\n          <a href=\"#\" class=\"vf-list__link\">\n            Implementation guide\n          </a>\n        </li>\n        <li class=\"vf-list__item\">\n          <a href=\"#\" class=\"vf-list__link\">\n            API documentation\n          </a>\n        </li>\n      </ul>\n    </div>\n  </div>\n</div>\n";
cb(null, output);
;
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
b_content: b_content,
root: root
};

})();
})();
