/**
 * Precompiled Nunjucks template: vf-header--inlay.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-header--inlay"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<style>\n:root {\n  /* These CSS properties are theming variables. If used, add to your HTML\n     after the VF CSS for vf-masthead and before the HTML for vf-masthead. */\n  --vf-masthead__bg-image: url('";
output += runtime.suppressValue(env.getFilter("path").call(context, "../../assets/vf-masthead/assets/group-bg_2d4155.png"), env.opts.autoescape);
output += "');\n}\n</style>\n\n<header class=\"vf-header vf-header--inlay\">\n\n";
env.getExtension("render")["run"](context,"@vf-masthead--inlay", function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
env.getExtension("render")["run"](context,"@vf-navigation--main", function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
output += runtime.suppressValue(t_3, true && env.opts.autoescape);
output += "\n</header>\n";
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
return {
root: root
};

})();
})();
