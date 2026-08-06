/**
 * Precompiled Nunjucks template: vf-details.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-details"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "\n";
if(runtime.contextOrFrameLookup(context, frame, "context")) {
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"details_open");
frame.set("details_open", t_1, true);
if(frame.topLevel) {
context.setVariable("details_open", t_1);
}
if(frame.topLevel) {
context.addExport("details_open", t_1);
}
var t_2;
t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"details_open");
frame.set("details_summary", t_2, true);
if(frame.topLevel) {
context.setVariable("details_summary", t_2);
}
if(frame.topLevel) {
context.addExport("details_summary", t_2);
}
var t_3;
t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"details_open");
frame.set("details_content", t_3, true);
if(frame.topLevel) {
context.setVariable("details_content", t_3);
}
if(frame.topLevel) {
context.addExport("details_content", t_3);
}
;
}
output += "\n<details class=\"vf-details\"";
if(runtime.contextOrFrameLookup(context, frame, "details_open")) {
output += " open";
;
}
output += ">\n    <summary class=\"vf-details--summary\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "details_summary"), env.opts.autoescape);
output += "</summary>\n    ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "details_content"), env.opts.autoescape);
output += "\n</details>\n\n";
output += "\n";
output += "\n";
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
