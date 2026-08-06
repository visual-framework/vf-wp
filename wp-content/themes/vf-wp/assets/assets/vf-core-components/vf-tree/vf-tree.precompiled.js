/**
 * Precompiled Nunjucks template: vf-tree.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-tree"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
var t_1;
t_1 = (function() {
var output = "";
output += "<button class=\"vf-button vf-tree__button\" ";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "item")),"expanded") == true) {
output += "aria-expanded=\"true\"";
;
}
output += " data-vf-js-tree--button>\n  <p data-vf-js-tree-button-hidden-text class=\"vf-u-sr-only\">\n    ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "button_hidden_open_text"), env.opts.autoescape);
output += "\n  </p>\n  <svg aria-hidden=\"true\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\"><path d=\"M19.5,12a2.3,2.3,0,0,1-.78,1.729L7.568,23.54a1.847,1.847,0,0,1-2.439-2.773l9.752-8.579a.25.25,0,0,0,0-.376L5.129,3.233A1.847,1.847,0,0,1,7.568.46l11.148,9.808A2.31,2.31,0,0,1,19.5,12Z\"/></svg>\n</button>\n";
;
return output;
})()
;
frame.set("vfTreeButton", t_1, true);
if(frame.topLevel) {
context.setVariable("vfTreeButton", t_1);
}
if(frame.topLevel) {
context.addExport("vfTreeButton", t_1);
}
output += "\n";
var macro_t_2 = runtime.makeMacro(
["list"], 
["currentDepth"], 
function (l_list, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("list", l_list);
frame.set("currentDepth", Object.prototype.hasOwnProperty.call(kwargs, "currentDepth") ? kwargs["currentDepth"] : 1);var t_3 = "";t_3 += "  <ul class=\"vf-tree__list ";
if(runtime.contextOrFrameLookup(context, frame, "currentDepth") > 1) {
t_3 += "vf-tree__list--additional";
;
}
t_3 += " vf-tree__list--";
t_3 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "currentDepth"), env.opts.autoescape);
t_3 += " | vf-list\" aria-role=\"";
if(runtime.contextOrFrameLookup(context, frame, "currentDepth") == 1) {
t_3 += "tree";
;
}
else {
t_3 += "group";
;
}
t_3 += "\">\n";
frame = frame.push();
var t_6 = l_list;
if(t_6) {t_6 = runtime.fromIterator(t_6);
var t_5 = t_6.length;
for(var t_4=0; t_4 < t_6.length; t_4++) {
var t_7 = t_6[t_4];
frame.set("item", t_7);
frame.set("loop.index", t_4 + 1);
frame.set("loop.index0", t_4);
frame.set("loop.revindex", t_5 - t_4);
frame.set("loop.revindex0", t_5 - t_4 - 1);
frame.set("loop.first", t_4 === 0);
frame.set("loop.last", t_4 === t_5 - 1);
frame.set("loop.length", t_5);
t_3 += "    <li class=\"vf-tree__item";
if(runtime.memberLookup((t_7),"selected") == true) {
t_3 += " vf-tree__item--selected";
;
}
if(runtime.memberLookup((t_7),"expanded") == true) {
t_3 += " | vf-tree__item--expanded";
;
}
t_3 += "\" data-vf-js-tree--collapsed=\"";
t_3 += runtime.suppressValue(!runtime.memberLookup((t_7),"expanded"), env.opts.autoescape);
t_3 += "\" data-vf-js-tree aria-role=\"treeitem\" aria-expanded=\"";
t_3 += runtime.suppressValue(runtime.memberLookup((t_7),"expanded"), env.opts.autoescape);
t_3 += "\">\n      <a href=\"";
t_3 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tree_example_href"), env.opts.autoescape);
t_3 += "\" class=\"vf-tree__link\"";
if(runtime.memberLookup((t_7),"artiveTrail") == true) {
t_3 += " aria-current=\"page\"";
;
}
t_3 += ">";
t_3 += runtime.suppressValue(runtime.memberLookup((t_7),"title"), env.opts.autoescape);
t_3 += " ";
if(runtime.memberLookup((t_7),"sublist")) {
t_3 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vfTreeButton"), env.opts.autoescape);
;
}
t_3 += "</a>\n";
if(runtime.memberLookup((t_7),"sublist")) {
t_3 += "        ";
t_3 += runtime.suppressValue((lineno = 15, colno = 21, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "vfTreeList"), "vfTreeList", context, [runtime.memberLookup((t_7),"sublist"),runtime.contextOrFrameLookup(context, frame, "currentDepth") + 1])), env.opts.autoescape);
t_3 += "\n";
;
}
t_3 += "    </li>\n";
;
}
}
frame = frame.pop();
t_3 += "  </ul>\n";
;
frame = callerFrame;
return new runtime.SafeString(t_3);
});
context.addExport("vfTreeList");
context.setVariable("vfTreeList", macro_t_2);
output += "\n<div class=\"vf-tree";
if(runtime.contextOrFrameLookup(context, frame, "expanded") === false) {
output += " vf-tree--collapsed";
;
}
output += "\" data-vf-js-tree ";
if(runtime.contextOrFrameLookup(context, frame, "expanded") === false) {
output += "data-vf-js-tree--collapsed=\"true\"";
;
}
output += " aria-expanded=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "expanded"), env.opts.autoescape);
output += "\" data-vf-js-button-hidden-open-text=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "button_hidden_open_text"), env.opts.autoescape);
output += "\" data-vf-js-button-hidden-close-text=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "button_hidden_close_text"), env.opts.autoescape);
output += "\">\n  <div class=\"vf-tree__inner\">\n\n    ";
output += runtime.suppressValue((lineno = 25, colno = 17, runtime.callWrap(macro_t_2, "vfTreeList", context, [runtime.contextOrFrameLookup(context, frame, "vf_tree_list")])), env.opts.autoescape);
output += "\n\n  </div>\n</div>\n";
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
