/**
 * Precompiled Nunjucks template: @visual-framework/vf-breadcrumbs
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-breadcrumbs"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<nav class=\"vf-breadcrumbs\" aria-label=\"Breadcrumb\">\n  <ul class=\"vf-breadcrumbs__list | vf-list vf-list--inline\">\n";
frame = frame.push();
var t_3 = runtime.contextOrFrameLookup(context, frame, "breadcrumbs");
if(t_3) {t_3 = runtime.fromIterator(t_3);
var t_2 = t_3.length;
for(var t_1=0; t_1 < t_3.length; t_1++) {
var t_4 = t_3[t_1];
frame.set("breadcrumb", t_4);
frame.set("loop.index", t_1 + 1);
frame.set("loop.index0", t_1);
frame.set("loop.revindex", t_2 - t_1);
frame.set("loop.revindex0", t_2 - t_1 - 1);
frame.set("loop.first", t_1 === 0);
frame.set("loop.last", t_1 === t_2 - 1);
frame.set("loop.length", t_2);
output += "    <li class=\"vf-breadcrumbs__item\">\n";
if(runtime.memberLookup((t_4),"url")) {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"url"), env.opts.autoescape);
output += "\" class=\"vf-breadcrumbs__link\">";
output += runtime.suppressValue(runtime.memberLookup((t_4),"text"), env.opts.autoescape);
output += "</a>\n";
;
}
else {
output += "      ";
output += runtime.suppressValue(runtime.memberLookup((t_4),"text"), env.opts.autoescape);
output += "\n";
;
}
output += "    </li>\n";
;
}
}
frame = frame.pop();
output += "  </ul>\n</nav>\n";
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
