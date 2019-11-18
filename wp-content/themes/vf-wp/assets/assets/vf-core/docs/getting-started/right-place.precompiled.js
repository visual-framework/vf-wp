/**
 * Precompiled Nunjucks template: right-place.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["right-place"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "---\ntitle: In the right place?\nlabel: none\ncontext:\n  lede: None yet\n  intro: This site is designed as a demonstration of all the components bundled with the Visual Framework 2.0 core.\norder: 99\nisIndex: true\n---\n\nYou most likely don't need to clone the\n[`vf-core`](https://github.com/visual-framework/vf-core) unless you're\ninterested in improving the architecure of the system or contributing a global\ncomponent for all users of the Visual Framework.\n\nMost developers will want to build a site or design system that use the Visual\nFramework architecure and components, for those users we recommend:\n\n1. Reviewing the links below and components on this site; and\n2. Utilising the [`vf-eleventy`](https://visual-framework.github.io/vf-eleventy/) templates.\n";
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
