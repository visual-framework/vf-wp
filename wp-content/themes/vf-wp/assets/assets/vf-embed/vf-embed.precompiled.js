/**
 * Precompiled Nunjucks template: vf-embed.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-embed"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div\n  class=\"vf-embed";
if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_16x9") == true) {
output += " vf-embed--16x9";
;
}
if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_4x3") == true) {
output += " vf-embed--4x3";
;
}
if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_custom") == true) {
output += " vf-embed--custom-ratio";
;
}
output += "\"\n\n  style=\"";
if(runtime.contextOrFrameLookup(context, frame, "vf_embed_max_width")) {
output += "--vf-embed-max-width: ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_max_width"), env.opts.autoescape);
output += ";\n";
;
}
if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_custom") == true) {
output += "    --vf-embed-custom-ratio-x: ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_custom_ratio_X"), env.opts.autoescape);
output += ";\n    --vf-embed-custom-ratio-y: ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_custom_ratio_Y"), env.opts.autoescape);
output += ";";
;
}
output += "\"\n>";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embedded_content"), env.opts.autoescape);
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
