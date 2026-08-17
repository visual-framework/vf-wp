/**
 * Precompiled Nunjucks template: vf-pagination.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-pagination"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<nav class=\"vf-pagination\" aria-label=\"Pagination\">\n  <ul class=\"vf-pagination__list\">\n";
frame = frame.push();
var t_3 = runtime.contextOrFrameLookup(context, frame, "pagination__list");
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
output += "    <li class=\"vf-pagination__item";
if(runtime.memberLookup((t_4),"item_modifier")) {
output += " ";
output += runtime.suppressValue(runtime.memberLookup((t_4),"item_modifier"), env.opts.autoescape);
;
}
output += "\">\n";
if(runtime.memberLookup((t_4),"item_modifier") == "vf-pagination__item--jump-back") {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"page_href"), env.opts.autoescape);
output += "\" class=\"vf-pagination__link\"  aria-label=\"First page\">\n        <span class=\"vf-pagination__icon\" aria-hidden=\"true\" onclick=\"window.scrollTo(0,100)\">\n          <svg width=\"16\" height=\"16\" viewBox=\"0 0 20 20\" focusable=\"false\" xmlns=\"http://www.w3.org/2000/svg\">\n            <path d=\"M13.5 5.5L8.5 10.5L13.5 15.5\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n            <path d=\"M9.5 5.5L4.5 10.5L9.5 15.5\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n          </svg>\n        </span>\n        <span class=\"vf-u-sr-only\">First page</span>\n      </a>\n";
;
}
else {
if(runtime.memberLookup((t_4),"item_modifier") == "vf-pagination__item--previous-page") {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"page_href"), env.opts.autoescape);
output += "\" class=\"vf-pagination__link\"  aria-label=\"Previous page\">\n        <span class=\"vf-pagination__icon\" aria-hidden=\"true\" onclick=\"window.scrollTo(0,100)\">\n          <svg width=\"16\" height=\"16\" viewBox=\"0 0 20 20\" focusable=\"false\" xmlns=\"http://www.w3.org/2000/svg\">\n            <path d=\"M11.5 5.5L6.5 10.5L11.5 15.5\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n          </svg>\n        </span>\n        <span class=\"vf-u-sr-only\">Previous page</span>\n      </a>\n";
;
}
else {
if(runtime.memberLookup((t_4),"item_modifier") == "vf-pagination__item--next-page") {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"page_href"), env.opts.autoescape);
output += "\" class=\"vf-pagination__link\" aria-label=\"Next page\">\n        <span class=\"vf-pagination__icon\" aria-hidden=\"true\" onclick=\"window.scrollTo(0,100)\">\n          <svg width=\"16\" height=\"16\" viewBox=\"0 0 20 20\" focusable=\"false\" xmlns=\"http://www.w3.org/2000/svg\">\n            <path d=\"M8.5 5.5L13.5 10.5L8.5 15.5\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n          </svg>\n        </span>\n        <span class=\"vf-u-sr-only\">Next page</span>\n      </a>\n";
;
}
else {
if(runtime.memberLookup((t_4),"item_modifier") == "vf-pagination__item--jump-forward") {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"page_href"), env.opts.autoescape);
output += "\" class=\"vf-pagination__link\"  aria-label=\"Last page\">\n        <span class=\"vf-pagination__icon\" aria-hidden=\"true\" onclick=\"window.scrollTo(0,100)\">\n          <svg width=\"16\" height=\"16\" viewBox=\"0 0 20 20\" focusable=\"false\" xmlns=\"http://www.w3.org/2000/svg\">\n            <path d=\"M6.5 5.5L11.5 10.5L6.5 15.5\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n            <path d=\"M10.5 5.5L15.5 10.5L10.5 15.5\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>\n          </svg>\n        </span>\n        <span class=\"vf-u-sr-only\">Last page</span>\n      </a>\n";
;
}
else {
if(runtime.memberLookup((t_4),"page_href")) {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"page_href"), env.opts.autoescape);
output += "\" class=\"vf-pagination__link";
if(runtime.memberLookup((t_4),"item_modifier") == "vf-pagination__link--visited") {
output += " vf-pagination__link--visited";
;
}
output += "\" onclick=\"window.scrollTo(0,100)\">\n        <span class=\"vf-u-sr-only\">Page </span>";
output += runtime.suppressValue(runtime.memberLookup((t_4),"page_number"), env.opts.autoescape);
output += "\n      </a>\n";
;
}
else {
if(runtime.memberLookup((t_4),"item_modifier") == "vf-pagination__item--is-active") {
output += "      <span class=\"vf-pagination__label\" aria-current=\"page\">\n        <span class=\"vf-u-sr-only\">Page </span>";
output += runtime.suppressValue(runtime.memberLookup((t_4),"page_number"), env.opts.autoescape);
output += "\n      </span>\n";
;
}
else {
output += "      <span class=\"vf-pagination__label\">";
output += runtime.suppressValue(runtime.memberLookup((t_4),"page_number"), env.opts.autoescape);
output += "</span>\n";
;
}
;
}
;
}
;
}
;
}
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
