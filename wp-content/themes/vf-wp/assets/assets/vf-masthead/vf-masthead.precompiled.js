/**
 * Precompiled Nunjucks template: vf-masthead.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-masthead"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<style>\n    .vf-masthead {\n      --vf-masthead__bg-image: url(";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "mastheadImage"), env.opts.autoescape);
output += ");\n    }\n</style>\n<div\n  class=\"vf-masthead";
if(runtime.contextOrFrameLookup(context, frame, "mastheadThemeVariant")) {
output += "  ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "mastheadThemeVariant"), env.opts.autoescape);
;
}
if(runtime.contextOrFrameLookup(context, frame, "hasTitleBlock")) {
output += "  ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "hasTitleBlock"), env.opts.autoescape);
output += "\n";
;
}
if(runtime.contextOrFrameLookup(context, frame, "mastheadImageClass")) {
output += "  ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "mastheadImageClass"), env.opts.autoescape);
output += "\n";
;
}
output += "\"\n";
if(runtime.contextOrFrameLookup(context, frame, "mastheadImage")) {
output += "  style=\"background-image: var(--vf-masthead__bg-image)\"\n  data-vf-js-masthead\n";
;
}
output += ">\n  <div class=\"vf-masthead__inner\">\n    <div class=\"vf-masthead__title\">\n      <h1 class=\"vf-masthead__heading\">\n        <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "masthead_href"), env.opts.autoescape);
output += "\" class=\"vf-masthead__heading__link\">Strategy &amp; Communications</a>\n        <span class=\"vf-masthead__heading--additional\">Chromosome structure and dynamics</span>\n      </h1>\n      <h2 class=\"vf-masthead__subheading\">\n        <span class=\"vf-masthead__location\">VF Hamburg</span>\n        <span class=\"vf-masthead__group\">Structural Biology</span>\n      </h2>\n    </div>\n  </div>\n</div>\n";
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
