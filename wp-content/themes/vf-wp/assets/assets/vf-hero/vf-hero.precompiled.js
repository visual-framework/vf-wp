/**
 * Precompiled Nunjucks template: vf-hero.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-hero"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<section\n  class=\"vf-hero ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "modifier_class"), env.opts.autoescape);
output += "\"\n  style=\"\n";
if(runtime.contextOrFrameLookup(context, frame, "vf_hero_image")) {
output += "--vf-hero-bg-image: ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_image"), env.opts.autoescape);
;
}
if(runtime.contextOrFrameLookup(context, frame, "vf_hero__initial_row")) {
output += " --vf-hero-grid__row--initial: ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero__initial_row"), env.opts.autoescape);
;
}
output += "\"\n>\n  <div class=\"vf-hero__content\">\n";
if(runtime.contextOrFrameLookup(context, frame, "vf_hero_heading")) {
output += " <h2 class=\"vf-hero__heading\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_heading"), env.opts.autoescape);
output += "</h2>";
;
}
if((runtime.contextOrFrameLookup(context, frame, "vf_hero_summary_title")) || (runtime.contextOrFrameLookup(context, frame, "vf_hero_subheading"))) {
output += "<p class=\"[ vf-summary__title ] vf-hero__subheading\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_summary_title"), env.opts.autoescape);
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_subheading"), env.opts.autoescape);
output += "</p>";
;
}
(function(cb) {if(runtime.contextOrFrameLookup(context, frame, "vf_hero_href")) {
output += "    <p class=\"vf-hero__text\">";
if(runtime.contextOrFrameLookup(context, frame, "vf_hero_href")) {
output += "      <a class=\"vf-link\" href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_href"), env.opts.autoescape);
output += "\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_text"), env.opts.autoescape);
output += "<svg width=\"24\" height=\"24\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z\" fill=\"\" fill-rule=\"nonzero\"></path></svg></a>\n";
;
}
else {
output += "      ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_hero_text"), env.opts.autoescape);
output += "\n";
;
}
output += "    </p>\n";
cb();
}
else {
frame = frame.push();
var t_3 = runtime.fromIterator(runtime.contextOrFrameLookup(context, frame, "vf_hero_text"));
runtime.asyncEach(t_3, 1, function(hero_text, t_1, t_2,next) {
frame.set("hero_text", hero_text);
frame.set("loop.index", t_1 + 1);
frame.set("loop.index0", t_1);
frame.set("loop.revindex", t_2 - t_1);
frame.set("loop.revindex0", t_2 - t_1 - 1);
frame.set("loop.first", t_1 === 0);
frame.set("loop.last", t_1 === t_2 - 1);
frame.set("loop.length", t_2);
output += "<p class=\"vf-hero__text\">\n      ";
output += runtime.suppressValue(hero_text, env.opts.autoescape);
output += "\n    </p>\n";
next(t_1);
;
}, function(t_5,t_4) {
if(t_5) { cb(t_5); return; }
frame = frame.pop();
cb()});
}
})(function(t_6) {
if(t_6) { cb(t_6); return; }output += "  </div>\n\n";
if(runtime.contextOrFrameLookup(context, frame, "modifier_class") === "vf-hero--intense") {
output += "  <script defer=\"defer\" src=\"../../assets/vf-hero/assets/jarallax.js\"></script>\n  <script>\n    document.addEventListener(\"DOMContentLoaded\", function(){\n      jarallax(document.querySelectorAll('.vf-hero--intense'), {\n        speed: 1.25\n      });\n    });\n  </script>\n";
;
}
output += "</section>\n";
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
