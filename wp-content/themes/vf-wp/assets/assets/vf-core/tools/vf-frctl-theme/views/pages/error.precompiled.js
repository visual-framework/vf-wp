/**
 * Precompiled Nunjucks template: error.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["error"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate("layouts/doc.njk", true, "error", false, function(t_3,t_2) {
if(t_3) { cb(t_3); return; }
parentTemplate = t_2
for(var t_1 in parentTemplate.blocks) {
context.addBlock(t_1, parentTemplate.blocks[t_1]);
}
output += "\n";
var t_4;
t_4 = {"title": env.getFilter("default").call(context, ((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"status") == "404"?"Not found":runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"name"))),"An error has occurred")};
frame.set("page", t_4, true);
if(frame.topLevel) {
context.setVariable("page", t_4);
}
if(frame.topLevel) {
context.addExport("page", t_4);
}
output += "\n";
(parentTemplate ? function(e, c, f, r, cb) { cb(""); } : context.getBlock("docContent"))(env, context, frame, runtime, function(t_6,t_5) {
if(t_6) { cb(t_6); return; }
output += t_5;
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
function b_docContent(env, context, frame, runtime, cb) {
var lineno = 6;
var colno = 3;
var output = "";
try {
var frame = frame.push(true);
output += "\n<div class=\"Error\">\n\n    <div class=\"Error-message component-library-notes\">\n        ";
output += runtime.suppressValue(env.getFilter("markdown").call(context, env.getFilter("default").call(context, runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"message"),"No more information is available.")), env.opts.autoescape);
output += "\n    </div>\n\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"stack") && runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"status") != "404") {
output += "    <pre class=\"vf-code-example__pre\"><code class=\"Code Error-stack vf-code-example\">";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "error")),"stack"), env.opts.autoescape);
output += "</code></pre>\n";
;
}
output += "\n</div>\n\n";
cb(null, output);
;
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
b_docContent: b_docContent,
root: root
};

})();
})();
