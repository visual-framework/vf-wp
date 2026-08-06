/**
 * Precompiled Nunjucks template: vf-video.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-video"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-video\">\n  <iframe width=\"560\" height=\"315\" src=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "video_href"), env.opts.autoescape);
output += "\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>\n</div>\n";
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
