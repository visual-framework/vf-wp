/**
 * Precompiled Nunjucks template: vf-stack.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-stack"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div\nclass=\"vf-stack";
if(runtime.contextOrFrameLookup(context, frame, "size")) {
output += " vf-stack--";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "size"), env.opts.autoescape);
;
}
if(runtime.contextOrFrameLookup(context, frame, "modifier")) {
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "modifier"), env.opts.autoescape);
;
}
output += "\"\n";
if(runtime.contextOrFrameLookup(context, frame, "custom_spacing_property")) {
output += "  style=\"--vf-stack-margin--custom: ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "custom_spacing_property"), env.opts.autoescape);
output += ";\"";
;
}
output += ">\n";
env.getExtension("render")["run"](context,"@vf-box--normal-primary", function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
env.getExtension("render")["run"](context,"@vf-box--normal-primary", function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
output += runtime.suppressValue(t_3, true && env.opts.autoescape);
env.getExtension("render")["run"](context,"@vf-box--normal-primary", function(t_6,t_5) {
if(t_6) { cb(t_6); return; }
output += runtime.suppressValue(t_5, true && env.opts.autoescape);
output += "</div>\n<br>\n";
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
