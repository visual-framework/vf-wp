/**
 * Precompiled Nunjucks template: header.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["header"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<header class=\"vf-header\">\n  <div class=\"vf-global-header\">\n\n    <div class=\"vf-global-header__inner\">\n";
env.getExtension("render")["run"](context,"@vf-logo",{"href": (lineno = 4, colno = 39, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, ["/",runtime.contextOrFrameLookup(context, frame, "request")])),"logo_text": (lineno = 4, colno = 75, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"get"), "frctl[\"get\"]", context, ["project.title"])),"image": "../../assets/vf-logo/assets/logo.svg"}, function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
output += "\n      <nav class=\"vf-navigation vf-navigation--global\">\n        <ul class=\"vf-navigation__list | vf-list--inline\">\n          <li class=\"vf-navigation__item\">\n            <a href=\"https://visual-framework.github.io/vf-welcome/\" class=\"vf-navigation__link\">About the Visual Framework</a>\n          </li>\n          <li class=\"vf-navigation__item\">\n            <a href=\"https://visual-framework.github.io/vf-welcome/developing\" class=\"vf-navigation__link\">Documentation</a>\n          </li>\n          <li class=\"vf-navigation__item\">\n            <a href=\"https://join.slack.com/t/visual-framework/shared_invite/enQtNDAxNzY0NDg4NTY0LWFhMjEwNGY3ZTk3NWYxNWVjOWQ1ZWE4YjViZmY1YjBkMDQxMTNlNjQ0N2ZiMTQ1ZTZiMGM4NjU5Y2E0MjM3ZGQ\" class=\"vf-navigation__link\">Help, chat</a>\n          </li>\n        </ul>\n      </nav>\n\n    </div>\n\n  </div>\n</header>\n";
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
