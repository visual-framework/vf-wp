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
t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_action");
frame.set("search_action", t_5, true);
if(frame.topLevel) {
context.setVariable("search_action", t_5);
}
if(frame.topLevel) {
context.addExport("search_action", t_5);
}
var t_6;
t_6 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_id");
frame.set("search_id", t_6, true);
if(frame.topLevel) {
context.setVariable("search_id", t_6);
}
if(frame.topLevel) {
context.addExport("search_id", t_6);
}
var t_7;
t_7 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"search_value_default");
frame.set("search_value_default", t_7, true);
if(frame.topLevel) {
context.setVariable("search_value_default", t_7);
}
if(frame.topLevel) {
context.addExport("search_value_default", t_7);
}
output += "\n";
var t_8;
t_8 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"theme");
frame.set("theme", t_8, true);
if(frame.topLevel) {
context.setVariable("theme", t_8);
}
if(frame.topLevel) {
context.addExport("theme", t_8);
}
var t_9;
t_9 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"variant");
frame.set("variant", t_9, true);
if(frame.topLevel) {
context.setVariable("variant", t_9);
}
if(frame.topLevel) {
context.addExport("variant", t_9);
}
output += "\n";
var t_10;
t_10 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
frame.set("id", t_10, true);
if(frame.topLevel) {
context.setVariable("id", t_10);
}
if(frame.topLevel) {
context.addExport("id", t_10);
}
var t_11;
t_11 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"modifiers");
frame.set("modifiers", t_11, true);
if(frame.topLevel) {
context.setVariable("modifiers", t_11);
}
if(frame.topLevel) {
context.addExport("modifiers", t_11);
}
var t_12;
t_12 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
frame.set("override_class", t_12, true);
if(frame.topLevel) {
context.setVariable("override_class", t_12);
}
if(frame.topLevel) {
context.addExport("override_class", t_12);
}
;
}
output += "<form action=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_action"), env.opts.autoescape);
output += "\"";
if(runtime.contextOrFrameLookup(context, frame, "id")) {
output += " id=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
output += "\"";
;
}
output += "  class=\"vf-form | vf-search";
if(runtime.contextOrFrameLookup(context, frame, "variant") == "inline") {
output += " vf-search--inline";
;
}
if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
output += " | ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
;
}
output += "\">\n  <div class=\"vf-form__item | vf-search__item\">\n    <label class=\"vf-form__label vf-u-sr-only | vf-search__label\" for=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_id"), env.opts.autoescape);
output += "\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_label"), env.opts.autoescape);
output += "</label>\n    <input type=\"search\" placeholder=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_placeholder"), env.opts.autoescape);
output += "\" id=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_id"), env.opts.autoescape);
output += "\" ";
if(runtime.contextOrFrameLookup(context, frame, "search_value_default")) {
output += " value=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_value_default"), env.opts.autoescape);
output += "\"";
;
}
output += " class=\"vf-form__input | vf-search__input\"";
if(runtime.contextOrFrameLookup(context, frame, "search_autofocus")) {
output += " autofocus";
;
}
output += ">\n  </div>\n  <button type=\"submit\" class=\"vf-search__button | vf-button vf-button--primary\"> ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "search_button"), env.opts.autoescape);
output += "</button>\n</form>\n";
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
