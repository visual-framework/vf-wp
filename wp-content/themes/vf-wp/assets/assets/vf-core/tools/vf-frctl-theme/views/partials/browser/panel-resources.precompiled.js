/**
 * Precompiled Nunjucks template: panel-resources.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["panel-resources"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
var t_1;
t_1 = runtime.memberLookup(((lineno = 0, colno = 36, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"component"), "entity[\"component\"]", context, []))),"handle");
frame.set("compHandle", t_1, true);
if(frame.topLevel) {
context.setVariable("compHandle", t_1);
}
if(frame.topLevel) {
context.addExport("compHandle", t_1);
}
frame = frame.push();
var t_4 = (lineno = 1, colno = 45, runtime.callWrap(runtime.memberLookup(((lineno = 1, colno = 37, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"resources"), "entity[\"resources\"]", context, []))),"items"), "the return value of (entity[\"resources\"])[\"items\"]", context, []));
if(t_4) {t_4 = runtime.fromIterator(t_4);
var t_3 = t_4.length;
for(var t_2=0; t_2 < t_4.length; t_2++) {
var t_5 = t_4[t_2];
frame.set("collection", t_5);
frame.set("loop.index", t_2 + 1);
frame.set("loop.index0", t_2);
frame.set("loop.revindex", t_3 - t_2);
frame.set("loop.revindex0", t_3 - t_2 - 1);
frame.set("loop.first", t_2 === 0);
frame.set("loop.last", t_2 === t_3 - 1);
frame.set("loop.length", t_3);
if(runtime.memberLookup((t_5),"size")) {
output += "<h3 class=\"vf-text vf-text-heading--4\">Resources and files</h3>\n\n<div class=\"vf-tabs\">\n  <ul class=\"vf-tabs__list\" data-vf-js-tabs>\n    <!--<li class=\"vf-tabs__item\">Component files</li>-->\n";
frame = frame.push();
var t_8 = (lineno = 8, colno = 39, runtime.callWrap(runtime.memberLookup((t_5),"items"), "collection[\"items\"]", context, []));
if(t_8) {t_8 = runtime.fromIterator(t_8);
var t_7 = t_8.length;
for(var t_6=0; t_6 < t_8.length; t_6++) {
var t_9 = t_8[t_6];
frame.set("resource", t_9);
frame.set("loop.index", t_6 + 1);
frame.set("loop.index0", t_6);
frame.set("loop.revindex", t_7 - t_6);
frame.set("loop.revindex0", t_7 - t_6 - 1);
frame.set("loop.first", t_6 === 0);
frame.set("loop.last", t_6 === t_7 - 1);
frame.set("loop.length", t_7);
if(runtime.memberLookup((t_9),"base") != "package-lock.json") {
output += "      <li class=\"vf-tabs__item\">\n        <a class=\"vf-tabs__link\" href=\"#vf-tabs__section--";
output += runtime.suppressValue(runtime.memberLookup((t_9),"id"), env.opts.autoescape);
output += "\">";
output += runtime.suppressValue(runtime.memberLookup((t_9),"base"), env.opts.autoescape);
output += "</a>\n      </li>\n";
;
}
;
}
}
frame = frame.pop();
output += "  </ul>\n</div>\n\n<div class=\"vf-tabs-content\" data-vf-js-tabs-content>\n";
frame = frame.push();
var t_12 = (lineno = 19, colno = 37, runtime.callWrap(runtime.memberLookup((t_5),"items"), "collection[\"items\"]", context, []));
if(t_12) {t_12 = runtime.fromIterator(t_12);
var t_11 = t_12.length;
for(var t_10=0; t_10 < t_12.length; t_10++) {
var t_13 = t_12[t_10];
frame.set("resource", t_13);
frame.set("loop.index", t_10 + 1);
frame.set("loop.index0", t_10);
frame.set("loop.revindex", t_11 - t_10);
frame.set("loop.revindex0", t_11 - t_10 - 1);
frame.set("loop.first", t_10 === 0);
frame.set("loop.last", t_10 === t_11 - 1);
frame.set("loop.length", t_11);
if(runtime.memberLookup((t_13),"base") != "package-lock.json") {
output += "    <section class=\"vf-tabs__section\" id=\"vf-tabs__section--";
output += runtime.suppressValue(runtime.memberLookup((t_13),"id"), env.opts.autoescape);
output += "\">\n      <div class=\"FileBrowser-itemPreview\">\n";
if(runtime.memberLookup((t_13),"isBinary") && runtime.memberLookup((t_13),"isImage")) {
output += "            <img src=\"";
output += runtime.suppressValue((lineno = 24, colno = 29, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 24, colno = 54, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["component-resource",{"handle": runtime.contextOrFrameLookup(context, frame, "compHandle"),"asset": runtime.memberLookup((t_13),"base")}]))])), env.opts.autoescape);
output += "\">\n";
;
}
else {
if(!runtime.memberLookup((t_13),"isBinary")) {
output += "        <pre class=\"vf-code-example__pre\">\n          <code class=\"Code Code--lang-";
output += runtime.suppressValue(runtime.memberLookup((t_13),"lang"), env.opts.autoescape);
output += " FileBrowser-code vf-code-example\">";
output += runtime.suppressValue(env.getFilter("highlight").call(context, runtime.memberLookup((t_13),"contents"),runtime.memberLookup((t_13),"lang")), env.opts.autoescape);
output += "</code>\n        </pre>\n";
;
}
else {
output += "          <p class=\"vf-text\"><em>Previews are currently not available for this file type.</em></p>\n";
;
}
;
}
output += "      </div>\n\n      <h3 class=\"vf-text vf-text-heading--4\">File information</h3>\n      <ul class=\"vf-list vf-list--unordered\">\n        <li class=\"vf-list__item\">\n          URL:\n          <a data-no-pjax href=\"";
output += runtime.suppressValue((lineno = 38, colno = 39, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 38, colno = 64, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["component-resource",{"handle": runtime.contextOrFrameLookup(context, frame, "compHandle"),"asset": runtime.memberLookup((t_13),"base")}]))])), env.opts.autoescape);
output += "\">";
output += runtime.suppressValue((lineno = 38, colno = 163, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["component-resource",{"handle": runtime.contextOrFrameLookup(context, frame, "compHandle"),"asset": runtime.memberLookup((t_13),"base")}])), env.opts.autoescape);
output += "</a>\n        </li>\n        <li class=\"vf-list__item\">\n          Filesystem path: ";
output += runtime.suppressValue(env.getFilter("componentPath").call(context, runtime.memberLookup((t_13),"path")), env.opts.autoescape);
output += "\n        </li>\n        <li class=\"vf-list__item\">\n          Size: ";
output += runtime.suppressValue(env.getFilter("fileSize").call(context, runtime.memberLookup((runtime.memberLookup((t_13),"stat")),"size"),0), env.opts.autoescape);
output += "\n        </li>\n      </ul>\n    </section>\n";
;
}
;
}
}
frame = frame.pop();
output += "</div>\n\n";
;
}
;
}
}
frame = frame.pop();
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
