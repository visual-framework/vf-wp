/**
 * Precompiled Nunjucks template: vf-breadcrumbs--with-related.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-breadcrumbs--with-related"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<nav class=\"vf-breadcrumbs\" aria-label=\"Breadcrumb\">\n\n  <ul class=\"vf-breadcrumbs__list | vf-list vf-list--inline\">\n";
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
output += "    <li class=\"vf-breadcrumbs__item\"\n";
if(runtime.memberLookup((t_4),"currentPage")) {
output += " aria-current=\"location\"";
;
}
output += ">\n";
if(runtime.memberLookup((t_4),"breadcrumb_href")) {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"breadcrumb_href"), env.opts.autoescape);
output += "\" class=\"vf-breadcrumbs__link\">";
output += runtime.suppressValue(runtime.memberLookup((t_4),"text"), env.opts.autoescape);
output += "</a>\n\n";
;
}
else {
output += "      ";
output += runtime.suppressValue(runtime.memberLookup((t_4),"text"), env.opts.autoescape);
output += "\n";
;
}
output += "\n    </li>\n\n";
;
}
}
frame = frame.pop();
output += "\n  </ul>\n\n";
if(runtime.contextOrFrameLookup(context, frame, "related")) {
output += "  <span class=\"vf-breadcrumbs__heading\">Related:</span>\n  <ul class=\"vf-breadcrumbs__list vf-breadcrumbs__list--related | vf-list vf-list--inline\">\n";
frame = frame.push();
var t_7 = runtime.contextOrFrameLookup(context, frame, "related");
if(t_7) {t_7 = runtime.fromIterator(t_7);
var t_6 = t_7.length;
for(var t_5=0; t_5 < t_7.length; t_5++) {
var t_8 = t_7[t_5];
frame.set("item", t_8);
frame.set("loop.index", t_5 + 1);
frame.set("loop.index0", t_5);
frame.set("loop.revindex", t_6 - t_5);
frame.set("loop.revindex0", t_6 - t_5 - 1);
frame.set("loop.first", t_5 === 0);
frame.set("loop.last", t_5 === t_6 - 1);
frame.set("loop.length", t_6);
output += "    <li class=\"vf-breadcrumbs__item\"";
if(runtime.memberLookup((t_8),"breadcrumb_last")) {
output += " aria-current=\"location\"";
;
}
output += ">\n      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_8),"breadcrumb_href"), env.opts.autoescape);
output += "\" class=\"vf-breadcrumbs__link\">";
output += runtime.suppressValue(runtime.memberLookup((t_8),"text"), env.opts.autoescape);
output += "</a>\n    </li>\n";
;
}
}
frame = frame.pop();
output += "  </ul>\n";
;
}
output += "\n</nav>\n";
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
