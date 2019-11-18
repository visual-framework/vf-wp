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
output += "<div class=\"\">\n\n  ";
output += "\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/browser/panel-info.njk", false, "browser", false, function(t_2,t_1) {
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
output += "\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/browser/panel-html.njk", false, "browser", false, function(t_6,t_5) {
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
output += "\n  <div class=\"vf-grid vf-grid__col-3\">\n    <div class=\"vf-grid__col--span-3\">\n      <hr class=\"vf-divider vf-u-margin__bottom--md vf-u-margin__top--md\">\n    </div>\n    <div class=\"vf-grid__col--span-3\">\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/browser/panel-resources.njk", false, "browser", false, function(t_10,t_9) {
if(t_10) { cb(t_10); return; }
callback(null,t_9);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_12,t_11) {
if(t_12) { cb(t_12); return; }
callback(null,t_11);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
output += "    </div>\n  </div>\n\n</div>\n";
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
