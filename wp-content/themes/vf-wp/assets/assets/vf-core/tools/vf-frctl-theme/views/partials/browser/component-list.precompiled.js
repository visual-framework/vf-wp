/**
 * Precompiled Nunjucks template: component-list.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["component-list"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate("macros/status.njk", false, "component-list", false, function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
t_1.getExported(function(t_3,t_1) {
if(t_3) { cb(t_3); return; }
context.setVariable("status", t_1);
output += "<a id=\"components\"></a>\n<h1 class=\"vf-text vf-text-heading--1 vf-text--invert\">All the components </h1>\n\n";
var macro_t_4 = runtime.makeMacro(
[], 
["displayName", "type", "description"], 
function (kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("displayName", Object.prototype.hasOwnProperty.call(kwargs, "displayName") ? kwargs["displayName"] : "Grids");frame.set("type", Object.prototype.hasOwnProperty.call(kwargs, "type") ? kwargs["type"] : "grid");frame.set("description", Object.prototype.hasOwnProperty.call(kwargs, "description") ? kwargs["description"] : "");var t_5 = "";t_5 += "<section>\n  <header data-description=\"label area\" style=\"grid-column: 1 / -1;\">\n    <h4 class=\"vf-text vf-text-heading--5 | vf-u-margin--0\">\n      ";
t_5 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "displayName"), env.opts.autoescape);
t_5 += "\n      <a id=\"";
t_5 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "type"), env.opts.autoescape);
t_5 += "\"></a>\n    </h4>\n    <p class=\"vf-text--body vf-text-body--3\">\n      ";
t_5 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "description"), env.opts.autoescape);
t_5 += "\n    </p>\n  </header>\n  <section data-description=\"component list\" class=\"vf-grid vf-grid__col-4\" style=\"grid-column: 1 / -1;\">\n";
frame = frame.push();
var t_8 = runtime.fromIterator(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"components"));
runtime.asyncEach(t_8, 1, function(component, t_6, t_7,next) {
frame.set("component", component);
frame.set("loop.index", t_6 + 1);
frame.set("loop.index0", t_6);
frame.set("loop.revindex", t_7 - t_6);
frame.set("loop.revindex0", t_7 - t_6 - 1);
frame.set("loop.first", t_6 === 0);
frame.set("loop.last", t_6 === t_7 - 1);
frame.set("loop.length", t_7);
t_5 += "\n        ";
t_5 += "\n";
if(runtime.memberLookup((component),"isCollection") == true && runtime.memberLookup((component),"isHidden") == false) {
frame = frame.push();
var t_11 = component;
if(t_11) {t_11 = runtime.fromIterator(t_11);
var t_10 = t_11.length;
for(var t_9=0; t_9 < t_11.length; t_9++) {
var t_12 = t_11[t_9];
frame.set("subcomponent", t_12);
frame.set("loop.index", t_9 + 1);
frame.set("loop.index0", t_9);
frame.set("loop.revindex", t_10 - t_9);
frame.set("loop.revindex0", t_10 - t_9 - 1);
frame.set("loop.first", t_9 === 0);
frame.set("loop.last", t_9 === t_10 - 1);
frame.set("loop.length", t_10);
if(runtime.memberLookup((runtime.memberLookup((t_12),"context")),"component-type") == runtime.contextOrFrameLookup(context, frame, "type") && runtime.memberLookup((t_12),"isHidden") != true) {
t_5 += "              <p class=\"vf-text\">\n                <a class=\"vf-link--secondary\" href=\"";
t_5 += runtime.suppressValue((lineno = 23, colno = 59, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 23, colno = 84, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["component",{"handle": runtime.memberLookup((t_12),"handle")}]))])), env.opts.autoescape);
t_5 += "\">";
t_5 += runtime.suppressValue(runtime.memberLookup((t_12),"label"), env.opts.autoescape);
t_5 += "</a>\n              </p>\n";
;
}
;
}
}
frame = frame.pop();
;
}
t_5 += "\n";
if(runtime.memberLookup((runtime.memberLookup((component),"context")),"component-type") == runtime.contextOrFrameLookup(context, frame, "type")) {
t_5 += "        <p class=\"vf-text\">\n          <a class=\"vf-link--secondary\" href=\"";
t_5 += runtime.suppressValue((lineno = 31, colno = 53, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [(lineno = 31, colno = 78, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"urlFromRoute"), "frctl[\"theme\"][\"urlFromRoute\"]", context, ["component",{"handle": runtime.memberLookup((component),"handle")}]))])), env.opts.autoescape);
t_5 += "\">";
t_5 += runtime.suppressValue(runtime.memberLookup((component),"label"), env.opts.autoescape);
t_5 += "</a>\n        </p>\n";
;
}
next(t_6);
;
}, function(t_14,t_13) {
if(t_14) { cb(t_14); return; }
frame = frame.pop();
t_5 += "  </section>\n</section>\n<hr class=\"vf-divider\">\n";
});
frame = callerFrame;
return new runtime.SafeString(t_5);
});
context.addExport("componentByType");
context.setVariable("componentByType", macro_t_4);
output += "\n";
output += runtime.suppressValue((lineno = 40, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["Grids",runtime.makeKeywordArgs({"type": "grid","description": "Put stuff in columns."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 42, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["Elements",runtime.makeKeywordArgs({"type": "element","description": "The micro elements of the component library."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 44, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["Blocks",runtime.makeKeywordArgs({"type": "block","description": "Simple components like sections headers, galleries and so on."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 46, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["Container",runtime.makeKeywordArgs({"type": "container","description": "More complex components that sometimes have specific layout, like page intros, mastheads, news sections and so on."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 48, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["Boilerplates",runtime.makeKeywordArgs({"type": "boilerplate","description": "Whole-page templates that are a collection of many components."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 50, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["Utilities",runtime.makeKeywordArgs({"type": "utility","description": "Utility classes to help where neeed."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 52, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["EMBL Grids",runtime.makeKeywordArgs({"type": "embl-grid","description": "EMBLs way to put stuff in columns."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 54, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["EMBL Elements",runtime.makeKeywordArgs({"type": "embl-element","description": "EMBLs micro elements of the component library."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 56, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["EMBL Blocks",runtime.makeKeywordArgs({"type": "embl-block","description": "Simple components from EMBL like sections headers, galleries and so on."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 58, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["EMBL Containers",runtime.makeKeywordArgs({"type": "embl-container","description": "More complex EMBL components that sometimes have specific layout, like page intros, mastheads, news sections and so on."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 60, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["EMBL Boilerplates",runtime.makeKeywordArgs({"type": "embl-boilerplate","description": "Whole-page templates that are a collection of many components."})])), env.opts.autoescape);
output += "\n\n";
output += runtime.suppressValue((lineno = 62, colno = 18, runtime.callWrap(macro_t_4, "componentByType", context, ["Deprecated",runtime.makeKeywordArgs({"type": "deprecated","description": "These components are no longer maintained."})])), env.opts.autoescape);
output += "\n";
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
