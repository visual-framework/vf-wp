/**
 * Precompiled Nunjucks template: vf-search.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-search"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.contextOrFrameLookup(context, frame, "context")) {
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_label");
frame.set("search_label", t_1, true);
if(frame.topLevel) {
context.setVariable("search_label", t_1);
}
if(frame.topLevel) {
context.addExport("search_label", t_1);
}
var t_2;
t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_button");
frame.set("search_button", t_2, true);
if(frame.topLevel) {
context.setVariable("search_button", t_2);
}
if(frame.topLevel) {
context.addExport("search_button", t_2);
}
var t_3;
t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_placeholder");
frame.set("search_placeholder", t_3, true);
if(frame.topLevel) {
context.setVariable("search_placeholder", t_3);
}
if(frame.topLevel) {
context.addExport("search_placeholder", t_3);
}
var t_4;
t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_autofocus");
frame.set("search_autofocus", t_4, true);
if(frame.topLevel) {
context.setVariable("search_autofocus", t_4);
}
if(frame.topLevel) {
context.addExport("search_autofocus", t_4);
}
var t_5;
t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_description");
frame.set("search_description", t_5, true);
if(frame.topLevel) {
context.setVariable("search_description", t_5);
}
if(frame.topLevel) {
context.addExport("search_description", t_5);
}
var t_6;
t_6 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_action");
frame.set("search_action", t_6, true);
if(frame.topLevel) {
context.setVariable("search_action", t_6);
}
if(frame.topLevel) {
context.addExport("search_action", t_6);
}
var t_7;
t_7 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_id");
frame.set("search_id", t_7, true);
if(frame.topLevel) {
context.setVariable("search_id", t_7);
}
if(frame.topLevel) {
context.addExport("search_id", t_7);
}
var t_8;
t_8 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_value_default");
frame.set("search_value_default", t_8, true);
if(frame.topLevel) {
context.setVariable("search_value_default", t_8);
}
if(frame.topLevel) {
context.addExport("search_value_default", t_8);
}
output += "\n";
var t_9;
t_9 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"theme");
frame.set("theme", t_9, true);
if(frame.topLevel) {
context.setVariable("theme", t_9);
}
if(frame.topLevel) {
context.addExport("theme", t_9);
}
var t_10;
t_10 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"variant");
frame.set("variant", t_10, true);
if(frame.topLevel) {
context.setVariable("variant", t_10);
}
if(frame.topLevel) {
context.addExport("variant", t_10);
}
output += "\n";
var t_11;
t_11 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
frame.set("id", t_11, true);
if(frame.topLevel) {
context.setVariable("id", t_11);
}
if(frame.topLevel) {
context.addExport("id", t_11);
}
var t_12;
t_12 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"modifiers");
frame.set("modifiers", t_12, true);
if(frame.topLevel) {
context.setVariable("modifiers", t_12);
}
if(frame.topLevel) {
context.addExport("modifiers", t_12);
}
var t_13;
t_13 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
frame.set("override_class", t_13, true);
if(frame.topLevel) {
context.setVariable("override_class", t_13);
}
if(frame.topLevel) {
context.addExport("override_class", t_13);
}
;
}
if(runtime.contextOrFrameLookup(context, frame, "search__background")) {
output += "<div class=\"vf-container vf-container--search | vf-u-fullbleed\"";
if(runtime.contextOrFrameLookup(context, frame, "search__background")) {
output += "    style=\"--vf-container--search__background-color: ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search__background"), env.opts.autoescape);
output += ";\"";
;
}
output += ">\n";
;
}
output += "\n  <form action=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_action"), env.opts.autoescape);
output += "\"";
if(runtime.contextOrFrameLookup(context, frame, "id")) {
output += " id=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
output += "\"";
;
}
output += "\n    class=\"vf-form vf-form--search";
if(runtime.contextOrFrameLookup(context, frame, "responsive")) {
output += " vf-form--search--responsive";
;
}
if(runtime.contextOrFrameLookup(context, frame, "mini")) {
output += " vf-form--search--mini";
;
}
output += " | vf-sidebar vf-sidebar--end\"\n  >\n    <div class=\"vf-sidebar__inner\">\n\n      <div class=\"vf-form__item\">\n\n        <label class=\"vf-form__label vf-u-sr-only | vf-search__label\" for=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_id"), env.opts.autoescape);
output += "\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_label"), env.opts.autoescape);
output += "</label>\n        <input type=\"search\"\n              placeholder=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_placeholder"), env.opts.autoescape);
output += "\"\n              id=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_id"), env.opts.autoescape);
output += "\"";
if(runtime.contextOrFrameLookup(context, frame, "search_value_default")) {
output += " value=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_value_default"), env.opts.autoescape);
output += "\"";
;
}
output += "              class=\"vf-form__input\"";
if(runtime.contextOrFrameLookup(context, frame, "search_autofocus")) {
output += " autofocus";
;
}
if(runtime.contextOrFrameLookup(context, frame, "results")) {
output += "aria-owns=\"vf-form--search__results-list\"";
;
}
output += "        >";
if(runtime.contextOrFrameLookup(context, frame, "results")) {
output += "          <ul\n            id=\"vf-form--search__results-list\"\n            class=\"vf-list | vf-form--search__results-list | vf-stack vf-stack--custom\"\n            aria-labelledby=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_id"), env.opts.autoescape);
output += "\"\n          >\n            <li id=\"vf-form--search__results-list--01\" class=\"vf-list__item\" role=\"option\">\n            abcdef ghijklm nopqr styuv\n            </li>\n            <li id=\"vf-form--search__results-list--02\" class=\"vf-list__item\" role=\"option\">\n            abcdef ghijklm nopqr styuv\n            </li>\n            <li id=\"vf-form--search__results-list--03\" class=\"vf-list__item\" role=\"option\">\n            abcdef ghijklm nopqr styuv\n            </li>\n            <li id=\"vf-form--search__results-list--04\" class=\"vf-list__item\" role=\"option\">\n            abcdef ghijklm nopqr styuv\n            </li>\n            <li id=\"vf-form--search__results-list--05\" class=\"vf-list__item\" role=\"option\">\n            abcdef ghijklm nopqr styuv\n            </li>\n          </ul>\n";
;
}
output += "      </div>\n\n      <button type=\"submit\" class=\"vf-search__button | vf-button vf-button--primary\">\n        <span class=\"vf-button__text";
if(runtime.contextOrFrameLookup(context, frame, "mini")) {
output += " | vf-u-sr-only";
;
}
output += "\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_button"), env.opts.autoescape);
output += "</span>\n\n";
if((runtime.contextOrFrameLookup(context, frame, "responsive") == true) || (runtime.contextOrFrameLookup(context, frame, "mini") == true)) {
output += "        <svg class=\"vf-icon vf-icon--search-btn | vf-button__icon\" aria-hidden=\"true\" xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:svgjs=\"http://svgjs.com/svgjs\" viewBox=\"0 0 140 140\" width=\"140\" height=\"140\"><g transform=\"matrix(5.833333333333333,0,0,5.833333333333333,0,0)\"><path d=\"M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z\" fill=\"#FFFFFF\" stroke=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"0\"></path></g></svg>\n";
;
}
output += "\n      </button>\n";
if(runtime.contextOrFrameLookup(context, frame, "search_description")) {
output += "<p class=\"vf-form__helper\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_description"), env.opts.autoescape);
output += "</p>";
;
}
output += "    </div>\n\n  </form>";
if(runtime.contextOrFrameLookup(context, frame, "search__background")) {
output += "</div>\n";
;
}
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
