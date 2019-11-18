/**
 * Precompiled Nunjucks template: browser.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["browser"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"component-info\">\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"notes")) {
output += "  <div class=\"component-library-notes | vf-content\">\n  ";
output += runtime.suppressValue(env.getFilter("async").call(context, (lineno = 3, colno = 28, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"docs")),"renderString"), "frctl[\"docs\"][\"renderString\"]", context, [runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"notes"),runtime.contextOrFrameLookup(context, frame, "renderEnv")]))), env.opts.autoescape);
output += "\n  </div>\n";
;
}
else {
output += "  <p class=\"Browser-isEmptyNote\">There are no notes for this item.</p>\n";
;
}
output += "\n  <div class=\"Pen-panel Pen-info\" data-role=\"info\">\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/browser/browser.njk", false, "browser", false, function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
callback(null,t_1);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
callback(null,t_3);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
output += "  </div>\n</div>\n\n<!-- List all the components at the footer -->\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "request")),"path") != "/") {
output += "  ";
output += "\n  <div class=\"preview-component-list\">\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/browser/component-list.njk", false, "browser", false, function(t_6,t_5) {
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
output += "  </div>\n";
});
}
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
