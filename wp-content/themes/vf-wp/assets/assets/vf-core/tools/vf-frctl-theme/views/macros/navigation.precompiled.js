/**
 * Precompiled Nunjucks template: navigation.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["navigation"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate("macros/status.njk", false, "navigation", false, function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
t_1.getExported(function(t_3,t_1) {
if(t_3) { cb(t_3); return; }
context.setVariable("status", t_1);
output += "\n";
var macro_t_4 = runtime.makeMacro(
["root", "current", "request"], 
[], 
function (l_root, l_current, l_request, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("root", l_root);
frame.set("current", l_current);
frame.set("request", l_request);
var t_5 = "";t_5 += "<div class=\"Tree\" data-behaviour=\"tree\" id=\"tree-";
t_5 += runtime.suppressValue(runtime.memberLookup((l_root),"name"), env.opts.autoescape);
t_5 += "\">\n    <!-- <h3 class=\"Tree-title\">";
t_5 += runtime.suppressValue(runtime.memberLookup((l_root),"label"), env.opts.autoescape);
t_5 += "</h3> -->\n    <ul class=\"Tree-items Tree-depth-1 vf-list\">\n        ";
t_5 += runtime.suppressValue((lineno = 6, colno = 17, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "leaves"), "leaves", context, [(lineno = 6, colno = 54, runtime.callWrap(runtime.memberLookup(((lineno = 6, colno = 29, runtime.callWrap(runtime.memberLookup((l_root),"filter"), "root[\"filter\"]", context, ["isHidden",false]))),"items"), "the return value of (root[\"filter\"])[\"items\"]", context, [])),l_root,l_current,2,l_request])), env.opts.autoescape);
t_5 += "\n    </ul>\n</div>\n";
;
frame = callerFrame;
return new runtime.SafeString(t_5);
});
context.addExport("tree");
context.setVariable("tree", macro_t_4);
output += "\n";
var macro_t_6 = runtime.makeMacro(
["items", "root", "current", "depth", "request"], 
[], 
function (l_items, l_root, l_current, l_depth, l_request, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("items", l_items);
frame.set("root", l_root);
frame.set("current", l_current);
frame.set("depth", l_depth);
frame.set("request", l_request);
var t_7 = "";frame = frame.push();
var t_10 = l_items;
if(t_10) {t_10 = runtime.fromIterator(t_10);
var t_9 = t_10.length;
for(var t_8=0; t_8 < t_10.length; t_8++) {
var t_11 = t_10[t_8];
frame.set("item", t_11);
frame.set("loop.index", t_8 + 1);
frame.set("loop.index0", t_8);
frame.set("loop.revindex", t_9 - t_8);
frame.set("loop.revindex0", t_9 - t_8 - 1);
frame.set("loop.first", t_8 === 0);
frame.set("loop.last", t_8 === t_9 - 1);
frame.set("loop.length", t_9);
if(runtime.memberLookup((t_11),"isCollection") || (runtime.memberLookup((t_11),"isComponent") && !runtime.memberLookup((t_11),"isCollated") && runtime.memberLookup(((lineno = 13, colno = 103, runtime.callWrap(runtime.memberLookup(((lineno = 13, colno = 94, runtime.callWrap(runtime.memberLookup((t_11),"variants"), "item[\"variants\"]", context, []))),"filter"), "the return value of (item[\"variants\"])[\"filter\"]", context, ["isHidden",false]))),"size") > 1)) {
t_7 += "        <li class=\"Tree-item Tree-collection Tree-depth-";
t_7 += runtime.suppressValue(l_depth, env.opts.autoescape);
t_7 += "\" data-behaviour=\"collection\" id=\"tree-";
t_7 += runtime.suppressValue(runtime.memberLookup((l_root),"name"), env.opts.autoescape);
t_7 += "-collection-";
t_7 += runtime.suppressValue(runtime.memberLookup((t_11),"handle"), env.opts.autoescape);
t_7 += "\">\n            <h4 class=\"vf-heading vf-u-type__text-heading--5 vf-u-margin--0 vf-u-margin__bottom--md vf-text--has-tag Tree-collectionLabel\" data-role=\"toggle\">\n                <span>";
t_7 += runtime.suppressValue(runtime.memberLookup((t_11),"label"), env.opts.autoescape);
t_7 += "</span>\n";
if(runtime.memberLookup((t_11),"isComponent") && runtime.memberLookup(((lineno = 17, colno = 56, runtime.callWrap(runtime.memberLookup((t_11),"variants"), "item[\"variants\"]", context, []))),"size") > 1) {
if(runtime.memberLookup((t_11),"status")) {
t_7 += runtime.suppressValue((lineno = 18, colno = 58, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "status")),"unlabelled"), "status[\"unlabelled\"]", context, [runtime.memberLookup((t_11),"status")])), env.opts.autoescape);
;
}
;
}
t_7 += "            </h4>\n            <ul class=\"vf-list vf-u-padding__left--0 Tree-collectionItems\" data-role=\"items\">\n";
if(runtime.memberLookup((t_11),"isComponent") && !runtime.memberLookup((t_11),"isCollated")) {
l_items = (lineno = 23, colno = 74, runtime.callWrap(runtime.memberLookup(((lineno = 23, colno = 49, runtime.callWrap(runtime.memberLookup(((lineno = 23, colno = 40, runtime.callWrap(runtime.memberLookup((t_11),"variants"), "item[\"variants\"]", context, []))),"filter"), "the return value of (item[\"variants\"])[\"filter\"]", context, ["isHidden",false]))),"items"), "the return value of (the return value of (item[\"variants\"])[\"filter\"])[\"items\"]", context, []));
frame.set("items", l_items, true);
if(frame.topLevel) {
context.setVariable("items", l_items);
}
if(frame.topLevel) {
context.addExport("items", l_items);
}
;
}
else {
l_items = (lineno = 25, colno = 63, runtime.callWrap(runtime.memberLookup(((lineno = 25, colno = 38, runtime.callWrap(runtime.memberLookup((t_11),"filter"), "item[\"filter\"]", context, ["isHidden",false]))),"items"), "the return value of (item[\"filter\"])[\"items\"]", context, []));
frame.set("items", l_items, true);
if(frame.topLevel) {
context.setVariable("items", l_items);
}
if(frame.topLevel) {
context.addExport("items", l_items);
}
;
}
t_7 += "            ";
t_7 += runtime.suppressValue((lineno = 27, colno = 21, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "leaves"), "leaves", context, [l_items,l_root,l_current,(l_depth + 1),l_request])), env.opts.autoescape);
t_7 += "\n            </ul>\n        </li>\n";
;
}
else {
var t_12;
t_12 = ((l_current && (runtime.memberLookup((l_current),"id") == runtime.memberLookup((t_11),"id")))?true:false);
frame.set("isCurrent", t_12, true);
if(frame.topLevel) {
context.setVariable("isCurrent", t_12);
}
if(frame.topLevel) {
context.addExport("isCurrent", t_12);
}
t_7 += "        <li class=\"Tree-item Tree-entity";
if(runtime.contextOrFrameLookup(context, frame, "isCurrent")) {
t_7 += " is-current";
;
}
t_7 += "\"";
if(runtime.contextOrFrameLookup(context, frame, "isCurrent")) {
t_7 += " data-state=\"current\"";
;
}
t_7 += " data-role=\"item\">\n            <a class=\"Tree-entityLink\" href=\"";
t_7 += runtime.suppressValue((lineno = 33, colno = 52, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(env.getFilter("url").call(context, t_11)),l_request])), env.opts.autoescape);
t_7 += "\" data-pjax>\n                <span>";
t_7 += runtime.suppressValue(runtime.memberLookup((t_11),"label"), env.opts.autoescape);
t_7 += "</span>";
if(runtime.memberLookup((t_11),"status")) {
t_7 += runtime.suppressValue((lineno = 34, colno = 85, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "status")),"unlabelled"), "status[\"unlabelled\"]", context, [runtime.memberLookup((t_11),"status")])), env.opts.autoescape);
;
}
t_7 += "            </a>\n        </li>\n";
;
}
;
}
}
frame = frame.pop();
;
frame = callerFrame;
return new runtime.SafeString(t_7);
});
context.addExport("leaves");
context.setVariable("leaves", macro_t_6);
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
