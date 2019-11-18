/**
 * Precompiled Nunjucks template: panel-info.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["panel-info"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<h3 class=\"vf-text vf-text-heading--4\">Component information</h3>\n<ul class=\"vf-list vf-list--unordered\">\n    <li class=\"vf-list__item\">\n        Handle: @";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"handle"), env.opts.autoescape);
output += "\n    </li>\n";
if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"tags")),"length") > 1) {
output += "    <li class=\"vf-list__item\">\n        Tags:\n";
frame = frame.push();
var t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"tags");
if(t_3) {t_3 = runtime.fromIterator(t_3);
var t_2 = t_3.length;
for(var t_1=0; t_1 < t_3.length; t_1++) {
var t_4 = t_3[t_1];
frame.set("tag", t_4);
frame.set("loop.index", t_1 + 1);
frame.set("loop.index0", t_1);
frame.set("loop.revindex", t_2 - t_1);
frame.set("loop.revindex0", t_2 - t_1 - 1);
frame.set("loop.first", t_1 === 0);
frame.set("loop.last", t_1 === t_2 - 1);
frame.set("loop.length", t_2);
if(t_4) {
output += runtime.suppressValue(t_4, env.opts.autoescape);
if(!runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "loop")),"last")) {
output += ", ";
;
}
;
}
;
}
}
frame = frame.pop();
output += "    </li>\n";
;
}
output += "    <li class=\"vf-list__item\">\n        Filesystem path: ";
output += runtime.suppressValue(env.getFilter("componentPath").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"viewPath")), env.opts.autoescape);
output += "\n    </li>\n";
if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"references")),"length")) {
output += "    <li class=\"vf-list__item\">\n      References (";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"references")),"length"), env.opts.autoescape);
output += "):\n";
frame = frame.push();
var t_7 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"references");
if(t_7) {t_7 = runtime.fromIterator(t_7);
var t_6 = t_7.length;
for(var t_5=0; t_5 < t_7.length; t_5++) {
var t_8 = t_7[t_5];
frame.set("ref", t_8);
frame.set("loop.index", t_5 + 1);
frame.set("loop.index0", t_5);
frame.set("loop.revindex", t_6 - t_5);
frame.set("loop.revindex0", t_6 - t_5 - 1);
frame.set("loop.first", t_5 === 0);
frame.set("loop.last", t_5 === t_6 - 1);
frame.set("loop.length", t_6);
output += "        <a href=\"";
output += runtime.suppressValue((lineno = 20, colno = 24, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 20, colno = 49, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["component",{"handle": runtime.memberLookup((t_8),"handle")}]))])), env.opts.autoescape);
output += "\">@";
output += runtime.suppressValue(runtime.memberLookup((t_8),"handle"), env.opts.autoescape);
output += "</a>";
if(!runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "loop")),"last")) {
output += ", ";
;
}
;
}
}
frame = frame.pop();
output += "    </li>\n";
;
}
if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"referencedBy")),"length")) {
output += "    <li class=\"vf-list__item\">\n        Referenced by ";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"referencedBy")),"length"), env.opts.autoescape);
output += " components:\n";
frame = frame.push();
var t_11 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"referencedBy");
if(t_11) {t_11 = runtime.fromIterator(t_11);
var t_10 = t_11.length;
for(var t_9=0; t_9 < t_11.length; t_9++) {
var t_12 = t_11[t_9];
frame.set("ref", t_12);
frame.set("loop.index", t_9 + 1);
frame.set("loop.index0", t_9);
frame.set("loop.revindex", t_10 - t_9);
frame.set("loop.revindex0", t_10 - t_9 - 1);
frame.set("loop.first", t_9 === 0);
frame.set("loop.last", t_9 === t_10 - 1);
frame.set("loop.length", t_10);
output += "          <a href=\"";
output += runtime.suppressValue((lineno = 28, colno = 26, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 28, colno = 51, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["component",{"handle": runtime.memberLookup((t_12),"handle")}]))])), env.opts.autoescape);
output += "\">@";
output += runtime.suppressValue(runtime.memberLookup((t_12),"handle"), env.opts.autoescape);
output += "</a>";
if(!runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "loop")),"last")) {
output += ", ";
;
}
;
}
}
frame = frame.pop();
output += "    </li>\n";
;
}
output += "</ul>\n";
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
