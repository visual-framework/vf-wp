/**
 * Precompiled Nunjucks template: vf-masthead--inlay.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-masthead--inlay"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<style>\n:root {\n  /* These CSS properties are theming variables. If used, add to your HTML\n     after the VF CSS for vf-masthead and before the HTML for vf-masthead. */\n  --vf-masthead__bg-image: url('";
output += runtime.suppressValue(env.getFilter("path").call(context, "../../assets/vf-masthead/assets/group-bg_2d4155.png"), env.opts.autoescape);
output += "');\n}\n</style>\n<div data-vf-js-masthead class=\"vf-masthead\" style=\"background-color: 'var(--vf-masthead__color--background', var(--vf-masthead__color--background-default)); color: var(--vf-masthead__color--foreground, var(--vf-masthead__color--foreground-default) );\">\n  <div class=\"vf-masthead__inner\">\n    <div class=\"vf-masthead__title\">\n      <h1 class=\"vf-masthead__heading\">\n        <a class=\"vf-masthead__heading__link\" href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "masthead_href"), env.opts.autoescape);
output += "\">Häring Group</a>\n        <span class=\"vf-masthead__heading--additional\">Chromosome structure and dynamics</span>\n      </h1>\n    </div>\n  </div>\n</div>\n<!--/vf-masthead-->\n";
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
