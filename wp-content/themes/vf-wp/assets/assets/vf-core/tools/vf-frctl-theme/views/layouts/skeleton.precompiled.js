/**
 * Precompiled Nunjucks template: skeleton.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["skeleton"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<!DOCTYPE html>\n<html lang=\"";
output += runtime.suppressValue(env.getFilter("default").call(context, (lineno = 1, colno = 30, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"get"), "frctl[\"theme\"][\"get\"]", context, ["lang"])),"en"), env.opts.autoescape);
output += "\" dir=\"";
output += runtime.suppressValue(((lineno = 1, colno = 91, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"get"), "frctl[\"theme\"][\"get\"]", context, ["rtl"]))?"rtl":"ltr"), env.opts.autoescape);
output += "\" class=\"vf-no-js\">\n<head>\n";
env.getExtension("render")["run"](context,"@vf-no-js", function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
output += "  <title>";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "page")),"title")) {
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "page")),"title"), env.opts.autoescape);
output += " component | ";
;
}
output += runtime.suppressValue((lineno = 4, colno = 80, runtime.callWrap(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"get"), "frctl[\"get\"]", context, ["project.title"])), env.opts.autoescape);
output += "</title>\n  <meta charset=\"utf-8\">\n  <meta content=\"width=device-width, initial-scale=1, minimum-scale=1\" name=\"viewport\">\n";
env.getExtension("render")["run"](context,"@vf-favicon", function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
output += runtime.suppressValue(t_3, true && env.opts.autoescape);
if(runtime.memberLookup((runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "_config")),"project")),"environment")),"local")) {
output += "  <link rel=\"stylesheet\" href=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, "/css/styles.css"), env.opts.autoescape);
output += "\">\n";
;
}
if(runtime.memberLookup((runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "_config")),"project")),"environment")),"production")) {
output += "  <link rel=\"stylesheet\" href=\"https://dev.assets.emblstatic.net/vf/develop/css/styles.css\">\n";
;
}
output += "  <script>\n  window.frctl = {\n    env: '";
if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"env")),"server")) {
output += "server";
;
}
else {
output += "static";
;
}
output += "'\n  };\n  </script>\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/stylesheets.njk", false, "skeleton", false, function(t_6,t_5) {
if(t_6) { cb(t_6); return; }
callback(null,t_5);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_8,t_7) {
if(t_8) { cb(t_8); return; }
callback(null,t_7);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
output += "</head>\n<body class=\"vf-body\">\n";
(parentTemplate ? function(e, c, f, r, cb) { cb(""); } : context.getBlock("page"))(env, context, frame, runtime, function(t_10,t_9) {
if(t_10) { cb(t_10); return; }
output += t_9;
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/foot.njk", false, "skeleton", false, function(t_12,t_11) {
if(t_12) { cb(t_12); return; }
callback(null,t_11);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_14,t_13) {
if(t_14) { cb(t_14); return; }
callback(null,t_13);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
output += "</body>\n</html>\n";
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})})})})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
function b_page(env, context, frame, runtime, cb) {
var lineno = 22;
var colno = 3;
var output = "";
try {
var frame = frame.push(true);
cb(null, output);
;
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
b_page: b_page,
root: root
};

})();
})();
