/**
 * Precompiled Nunjucks template: preview.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["preview"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "\n\n\n\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"isComponent")) {
var t_1;
t_1 = (lineno = 5, colno = 37, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"variants"), "entity[\"variants\"]", context, []));
frame.set("variants", t_1, true);
if(frame.topLevel) {
context.setVariable("variants", t_1);
}
if(frame.topLevel) {
context.addExport("variants", t_1);
}
;
}
else {
var t_2;
t_2 = (lineno = 7, colno = 44, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"parent")),"variants"), "entity[\"parent\"][\"variants\"]", context, []));
frame.set("variants", t_2, true);
if(frame.topLevel) {
context.setVariable("variants", t_2);
}
if(frame.topLevel) {
context.addExport("variants", t_2);
}
;
}
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "variants")),"size") > 1 && runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"preview") != "@preview--blocks" && runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"preview") != "@preview--elements") {
output += "        <div class=\"vf-tabs\">\n          <ul class=\"vf-tabs__list\">\n            <li class=\"vf-tabs__item\">\n              This component has variants:\n            </li>\n";
frame = frame.push();
var t_5 = (lineno = 15, colno = 44, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "variants")),"items"), "variants[\"items\"]", context, []));
if(t_5) {t_5 = runtime.fromIterator(t_5);
var t_4 = t_5.length;
for(var t_3=0; t_3 < t_5.length; t_3++) {
var t_6 = t_5[t_3];
frame.set("variant", t_6);
frame.set("loop.index", t_3 + 1);
frame.set("loop.index0", t_3);
frame.set("loop.revindex", t_4 - t_3);
frame.set("loop.revindex0", t_4 - t_3 - 1);
frame.set("loop.first", t_3 === 0);
frame.set("loop.last", t_3 === t_4 - 1);
frame.set("loop.length", t_4);
output += "\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"handle") == runtime.memberLookup((t_6),"handle")) {
output += "                <a class=\"vf-tabs__link is-active\" href=\"#is-active\">\n                  ";
output += runtime.suppressValue(runtime.memberLookup((t_6),"label"), env.opts.autoescape);
output += "\n                </a>\n";
;
}
else {
if(runtime.memberLookup((t_6),"isHidden") == false) {
output += "                  <a class=\"vf-tabs__link\" href=\"";
output += runtime.suppressValue((lineno = 23, colno = 56, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 23, colno = 81, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["component",{"handle": runtime.memberLookup((t_6),"handle")}]))])), env.opts.autoescape);
output += "\">\n                    ";
output += runtime.suppressValue(runtime.memberLookup((t_6),"label"), env.opts.autoescape);
output += "\n                  </a>\n";
;
}
output += " ";
output += "\n";
;
}
output += " ";
output += "\n\n";
;
}
}
frame = frame.pop();
output += "          </ul>\n\n      </div>\n\n";
;
}
output += "\n\n\n\n";
env.getTemplate("macros/render.njk", false, "preview", false, function(t_8,t_7) {
if(t_8) { cb(t_8); return; }
t_7.getExported(function(t_9,t_7) {
if(t_9) { cb(t_9); return; }
context.setVariable("render", t_7);
if((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"preview") == "@preview--blocks") || (runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"preview") == "@preview--elements")) {
output += "<div class=\"vf-component__container\">\n  <!-- @preview--blocks aren't done in iframes, this allows us a bit more flexibility and to test cross-contamination -->\n";
frame = frame.push();
var t_12 = runtime.contextOrFrameLookup(context, frame, "variants");
if(t_12) {t_12 = runtime.fromIterator(t_12);
var t_11 = t_12.length;
for(var t_10=0; t_10 < t_12.length; t_10++) {
var t_13 = t_12[t_10];
frame.set("item", t_13);
frame.set("loop.index", t_10 + 1);
frame.set("loop.index0", t_10);
frame.set("loop.revindex", t_11 - t_10);
frame.set("loop.revindex0", t_11 - t_10 - 1);
frame.set("loop.first", t_10 === 0);
frame.set("loop.last", t_10 === t_11 - 1);
frame.set("loop.length", t_11);
if(runtime.memberLookup((t_13),"isHidden") == false) {
output += "      <h3 class=\"vf-text vf-text-heading--4\"><a class=\"vf-badge vf-badge--tertiary\" href=\"#";
output += runtime.suppressValue(runtime.memberLookup((t_13),"handle"), env.opts.autoescape);
output += "\" id=\"";
output += runtime.suppressValue(runtime.memberLookup((t_13),"handle"), env.opts.autoescape);
output += "\">Variant ";
output += runtime.suppressValue(runtime.memberLookup((t_13),"label"), env.opts.autoescape);
output += "</a></h3>\n";
var t_14;
t_14 = env.getFilter("async").call(context, (lineno = 46, colno = 29, runtime.callWrap(runtime.memberLookup((t_13),"render"), "item[\"render\"]", context, [null,runtime.contextOrFrameLookup(context, frame, "renderEnv"),{"preview": runtime.contextOrFrameLookup(context, frame, "withLayout"),"collate": runtime.contextOrFrameLookup(context, frame, "withCollation")}])),true);
frame.set("rendered", t_14, true);
if(frame.topLevel) {
context.setVariable("rendered", t_14);
}
if(frame.topLevel) {
context.addExport("rendered", t_14);
}
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "rendered"), env.opts.autoescape);
output += "\n<div class=\"vf-grid\">\n<pre class=\"vf-code-example__pre\">\n";
output += runtime.suppressValue(env.getFilter("trim").call(context, (lineno = 50, colno = 16, runtime.callWrap(runtime.memberLookup((t_7),"entity"), "render[\"entity\"]", context, [env.getFilter("async").call(context, (lineno = 50, colno = 28, runtime.callWrap(runtime.memberLookup((t_13),"render"), "item[\"render\"]", context, [null,runtime.contextOrFrameLookup(context, frame, "renderEnv"),{"preview": false,"collate": false}])),true)]))), env.opts.autoescape);
output += "\n</pre>\n</div>\n\n      <hr class=\"vf-divider\" />\n";
;
}
;
}
}
frame = frame.pop();
output += "  </div>\n";
;
}
else {
output += "  <div class=\"Pen-panel Pen-preview Preview\" data-behaviour=\"preview\" id=\"preview-";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"id"), env.opts.autoescape);
output += "\">\n      <div class=\"Preview-wrapper\" data-role=\"resizer-disabled\">\n";
if(runtime.contextOrFrameLookup(context, frame, "renderError")) {
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "renderError"), env.opts.autoescape);
output += "\n";
;
}
else {
output += "         <iframe\n            class=\"Preview-iframe\"\n            data-role=\"window\"\n            src=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "previewUrl"), env.opts.autoescape);
output += "\"\n            sandbox=\"allow-same-origin allow-scripts allow-forms\"\n\n            marginwidth=\"0\" marginheight=\"0\" frameborder=\"1\" vspace=\"0\" hspace=\"0\" scrolling=\"yes\"\n            onload=\"resizeIframe(this)\"\n          >\n        </iframe>";
;
}
output += "            <p class=\"vf-figure__caption\">\n              Open the above preview in a new window:\n              <a href=\"";
output += runtime.suppressValue((lineno = 77, colno = 30, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 77, colno = 55, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["preview",{"handle": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"handle")}]))])), env.opts.autoescape);
output += "\"><span>With layout</span> Open</a>\n              <a href=\"";
output += runtime.suppressValue((lineno = 78, colno = 30, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 78, colno = 55, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["render",{"handle": runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"handle")}]))])), env.opts.autoescape);
output += "\"><span>Component only</span> Open</a>\n            </p>\n\n      </div>\n  </div>";
;
}
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
