/**
 * Precompiled Nunjucks template: vf-video-teaser.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-video-teaser"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-video-teaser\">\n  <h3 class=\"vf-video-teaser__title\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_video_teaser__title"), env.opts.autoescape);
output += "</h3>\n  <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "video_href"), env.opts.autoescape);
output += "\"><img class=\"vf-video-teaser__image\" src=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "../../assets/vf-video-teaser/assets/video-teaser.png"), env.opts.autoescape);
output += "\" alt=\"\" loading=\"lazy\"></a>\n  <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "video_href"), env.opts.autoescape);
output += "\" class=\"vf-video-teaser__link vf-link\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_video_teaser__link"), env.opts.autoescape);
output += "</a>\n</div>\n";
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
