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
output += "<div class=\"vf-video-teaser | vf-stack vf-stack--400\">\n\n";
if(runtime.contextOrFrameLookup(context, frame, "vf_video_teaser__title")) {
output += "<h3 class=\"vf-video-teaser__title\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_video_teaser__title"), env.opts.autoescape);
output += "</h3>";
;
}
output += "\n";
frame = frame.push();
var t_3 = runtime.contextOrFrameLookup(context, frame, "teasers");
if(t_3) {t_3 = runtime.fromIterator(t_3);
var t_2 = t_3.length;
for(var t_1=0; t_1 < t_3.length; t_1++) {
var t_4 = t_3[t_1];
frame.set("item", t_4);
frame.set("loop.index", t_1 + 1);
frame.set("loop.index0", t_1);
frame.set("loop.revindex", t_2 - t_1);
frame.set("loop.revindex0", t_2 - t_1 - 1);
frame.set("loop.first", t_1 === 0);
frame.set("loop.last", t_1 === t_2 - 1);
frame.set("loop.length", t_2);
output += "  <article class=\"vf-video-teaser__item | vf-stack vf-stack--400\">\n    <img class=\"vf-video-teaser__image\" src=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"vf_video_teaser__poster"), env.opts.autoescape);
output += "\" alt=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"vf_video_teaser_alt_text"), env.opts.autoescape);
output += "\" loading=\"lazy\">\n    <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"video_href"), env.opts.autoescape);
output += "\" class=\"vf-video-teaser__link vf-link\">";
output += runtime.suppressValue(runtime.memberLookup((t_4),"vf_video_teaser__link"), env.opts.autoescape);
output += "</a>\n  </article>\n";
;
}
}
frame = frame.pop();
output += "\n</div>\n";
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
