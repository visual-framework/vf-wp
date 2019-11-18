/**
 * Precompiled Nunjucks template: doc.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["doc"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate("layouts/doc.njk", true, "doc", false, function(t_3,t_2) {
if(t_3) { cb(t_3); return; }
parentTemplate = t_2
for(var t_1 in parentTemplate.blocks) {
context.addBlock(t_1, parentTemplate.blocks[t_1]);
}
env.getTemplate("macros/status.njk", false, "doc", false, function(t_5,t_4) {
if(t_5) { cb(t_5); return; }
t_4.getExported(function(t_6,t_4) {
if(t_6) { cb(t_6); return; }
context.setVariable("status", t_4);
output += "\n";
var t_7;
t_7 = runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "request")),"params")),"path");
frame.set("pathParam", t_7, true);
if(frame.topLevel) {
context.setVariable("pathParam", t_7);
}
if(frame.topLevel) {
context.addExport("pathParam", t_7);
}
var t_8;
t_8 = (lineno = 4, colno = 28, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"docs")),"find"), "frctl[\"docs\"][\"find\"]", context, ["path",env.getFilter("default").call(context, runtime.contextOrFrameLookup(context, frame, "pathParam"),"")]));
frame.set("doc", t_8, true);
if(frame.topLevel) {
context.setVariable("doc", t_8);
}
if(frame.topLevel) {
context.addExport("doc", t_8);
}
output += "\n";
if(runtime.contextOrFrameLookup(context, frame, "pathParam") && !runtime.contextOrFrameLookup(context, frame, "doc")) {
output += runtime.suppressValue((lineno = 6, colno = 38, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "throw"), "throw", context, [404,"Page not found"])), env.opts.autoescape);
;
}
output += "\n";
var t_9;
t_9 = {"title": env.getFilter("default").call(context, env.getFilter("default").call(context, env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "doc")),"title"),"VF 2.0 Component library"),(lineno = 9, colno = 78, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"get"), "frctl[\"get\"]", context, ["project.title"]))),"Welcome to your component library"),"lede": env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "doc")),"lede"),"Browse all the components included in the <a href=\"https://github.com/visual-framework/vf-core\" class=\"vf-link\">Visual Framework 2.0 core</a>."),"intro": env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "doc")),"intro"),"Use this website to browse components, understand the consituent CSS, JS templates, and to install from npm.")};
frame.set("page", t_9, true);
if(frame.topLevel) {
context.setVariable("page", t_9);
}
if(frame.topLevel) {
context.addExport("page", t_9);
}
output += "\n";
t_4 = (lineno = 14, colno = 26, runtime.callWrap(runtime.memberLookup((t_4),"tag"), "status[\"tag\"]", context, [runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "doc")),"status")]));
frame.set("status", t_4, true);
if(frame.topLevel) {
context.setVariable("status", t_4);
}
if(frame.topLevel) {
context.addExport("status", t_4);
}
output += "\n";
(parentTemplate ? function(e, c, f, r, cb) { cb(""); } : context.getBlock("docContent"))(env, context, frame, runtime, function(t_11,t_10) {
if(t_11) { cb(t_11); return; }
output += t_10;
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})})})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
function b_docContent(env, context, frame, runtime, cb) {
var lineno = 16;
var colno = 3;
var output = "";
try {
var frame = frame.push(true);
output += "\n<div class=\"component-library-notes\">\n\n";
context.getBlock("pageContent")(env, context, frame, runtime, function(t_13,t_12) {
if(t_13) { cb(t_13); return; }
output += t_12;
output += "</div>\n\n";
env.getExtension("render")["run"](context,"@vf-divider", function(t_15,t_14) {
if(t_15) { cb(t_15); return; }
output += runtime.suppressValue(t_14, true && env.opts.autoescape);
output += "\n";
(function(cb) {if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"docs")) {
env.getExtension("render")["run"](context,"@vf-heading",{"type": "large","title": "In this section"}, function(t_17,t_16) {
if(t_17) { cb(t_17); return; }
output += runtime.suppressValue(t_16, true && env.opts.autoescape);
cb()});
}
else {
cb()}
})(function(t_18) {
if(t_18) { cb(t_18); return; }output += "\n";
(function(cb) {if(runtime.contextOrFrameLookup(context, frame, "doc")) {
var t_19;
t_19 = (lineno = 40, colno = 36, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "doc")),"path")),"split"), "doc[\"path\"][\"split\"]", context, ["/"]));
frame.set("docSection", t_19, true);
if(frame.topLevel) {
context.setVariable("docSection", t_19);
}
if(frame.topLevel) {
context.addExport("docSection", t_19);
}
output += "\n";
(function(cb) {if(runtime.contextOrFrameLookup(context, frame, "docSection") != "") {
output += "  <ul>\n";
frame = frame.push();
var t_22 = runtime.fromIterator(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"docs"));
runtime.asyncEach(t_22, 1, function(item, t_20, t_21,next) {
frame.set("item", item);
frame.set("loop.index", t_20 + 1);
frame.set("loop.index0", t_20);
frame.set("loop.revindex", t_21 - t_20);
frame.set("loop.revindex0", t_21 - t_20 - 1);
frame.set("loop.first", t_20 === 0);
frame.set("loop.last", t_20 === t_21 - 1);
frame.set("loop.length", t_21);
frame = frame.push();
var t_25 = runtime.fromIterator(item);
runtime.asyncEach(t_25, 1, function(menuitem, t_23, t_24,next) {
frame.set("menuitem", menuitem);
frame.set("loop.index", t_23 + 1);
frame.set("loop.index0", t_23);
frame.set("loop.revindex", t_24 - t_23);
frame.set("loop.revindex0", t_24 - t_23 - 1);
frame.set("loop.first", t_23 === 0);
frame.set("loop.last", t_23 === t_24 - 1);
frame.set("loop.length", t_24);
if(runtime.memberLookup((item),"path") == runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "docSection")),0)) {
output += "              <li>\n                <a href=\"";
output += runtime.suppressValue((lineno = 48, colno = 32, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(env.getFilter("url").call(context, menuitem)),runtime.contextOrFrameLookup(context, frame, "request")])), env.opts.autoescape);
output += "\">";
output += runtime.suppressValue(runtime.memberLookup((menuitem),"title"), env.opts.autoescape);
output += "</a>\n";
if(runtime.memberLookup((menuitem),"path") == runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "doc")),"path")) {
output += "                <span class=\"vf-badge\">You are here</span>\n";
;
}
output += "              </li>\n";
;
}
next(t_23);
;
}, function(t_27,t_26) {
if(t_27) { cb(t_27); return; }
frame = frame.pop();
next(t_20);
});
}, function(t_29,t_28) {
if(t_29) { cb(t_29); return; }
frame = frame.pop();
output += "    </ul>\n";
cb()});
}
else {
cb()}
})(function(t_30) {
if(t_30) { cb(t_30); return; }cb()});
}
else {
cb()}
})(function(t_31) {
if(t_31) { cb(t_31); return; }output += "\n\n";
cb(null, output);
})})})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
function b_pageContent(env, context, frame, runtime, cb) {
var lineno = 20;
var colno = 3;
var output = "";
try {
var frame = frame.push(true);
env.getTemplate("macros/errors.njk", false, "doc", false, function(t_33,t_32) {
if(t_33) { cb(t_33); return; }
t_32.getExported(function(t_34,t_32) {
if(t_34) { cb(t_34); return; }
context.setVariable("errors", t_32);
if(runtime.contextOrFrameLookup(context, frame, "doc")) {
var t_35;
t_35 = env.getFilter("async").call(context, (lineno = 23, colno = 32, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "doc")),"render"), "doc[\"render\"]", context, [null,runtime.contextOrFrameLookup(context, frame, "renderEnv")])),true);
frame.set("contents", t_35, true);
if(frame.topLevel) {
context.setVariable("contents", t_35);
}
if(frame.topLevel) {
context.addExport("contents", t_35);
}
if(env.getFilter("isError").call(context, runtime.contextOrFrameLookup(context, frame, "contents"))) {
output += "    ";
output += runtime.suppressValue((lineno = 25, colno = 25, runtime.callWrap(runtime.memberLookup((t_32),"renderError"), "errors[\"renderError\"]", context, ["page",runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "contents")),"message")])), env.opts.autoescape);
output += "\n";
;
}
else {
output += "    ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "contents"), env.opts.autoescape);
output += "\n";
;
}
;
}
cb(null, output);
})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
b_docContent: b_docContent,
b_pageContent: b_pageContent,
root: root
};

})();
})();
