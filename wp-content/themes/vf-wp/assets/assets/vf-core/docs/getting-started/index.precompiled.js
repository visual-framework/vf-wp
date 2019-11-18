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
output += "---\ntitle: Getting started\nlabel: none\ncontext:\n  lede: None yet\n  intro: If you're new to the Visual Framework, here are some links to look at first.\norder: 100\nisIndex: true\n---\n\n1. Understand <a href=\"https://visual-framework.github.io/vf-welcome/\">the basics</a>;\n1. Review <a href=\"https://visual-framework.github.io/vf-welcome/developing/#getting-started/\">the documentation</a></a>;\n1. Browse <a href=\"#components\">the components</a> below;\n1. Learn <a href=\"https://visual-framework.github.io/vf-welcome/developing/#components\">how to create new components</a> or <a href=\"https://github.com/visual-framework/vf-core/issues/new?template=new-component-request.md\">request a new component</a>;\n1. Make your <a href=\"https://github.com/visual-framework/vf-eleventy\">own site or design system</a>; and\n1. <a href=\"https://join.slack.com/t/visual-framework/shared_invite/enQtNDAxNzY0NDg4NTY0LWFhMjEwNGY3ZTk3NWYxNWVjOWQ1ZWE4YjViZmY1YjBkMDQxMTNlNjQ0N2ZiMTQ1ZTZiMGM4NjU5Y2E0MjM3ZGQ\">Ask the community for help</a> or <a href=\"http://github.com/visual-framework/vf-core/issues/\">open an issue</a>.\n";
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
