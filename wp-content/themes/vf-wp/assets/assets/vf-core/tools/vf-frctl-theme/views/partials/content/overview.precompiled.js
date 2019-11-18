/**
 * Precompiled Nunjucks template: overview.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["overview"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate("macros/status.njk", false, "overview", false, function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
t_1.getExported(function(t_3,t_1) {
if(t_3) { cb(t_3); return; }
context.setVariable("status", t_1);
output += "\n";
output += "\n";
frame = frame.push();
var t_6 = runtime.fromIterator(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"docs"));
runtime.asyncEach(t_6, 1, function(item, t_4, t_5,next) {
frame.set("item", item);
frame.set("loop.index", t_4 + 1);
frame.set("loop.index0", t_4);
frame.set("loop.revindex", t_5 - t_4);
frame.set("loop.revindex0", t_5 - t_4 - 1);
frame.set("loop.first", t_4 === 0);
frame.set("loop.last", t_4 === t_5 - 1);
frame.set("loop.length", t_5);
frame = frame.push();
var t_9 = runtime.fromIterator(item);
runtime.asyncEach(t_9, 1, function(menuitem, t_7, t_8,next) {
frame.set("menuitem", menuitem);
frame.set("loop.index", t_7 + 1);
frame.set("loop.index0", t_7);
frame.set("loop.revindex", t_8 - t_7);
frame.set("loop.revindex0", t_8 - t_7 - 1);
frame.set("loop.first", t_7 === 0);
frame.set("loop.last", t_7 === t_8 - 1);
frame.set("loop.length", t_8);
output += "    ";
output += "\n      <section class=\"embl-grid embl-grid--has-centered-content\">\n        <div class=\"vf-section-header\">\n          <h3 class=\"vf-section-header__heading\">";
output += runtime.suppressValue(runtime.memberLookup((menuitem),"title"), env.opts.autoescape);
output += "</h3>\n        </div>\n        <div class=\"vf-section-content | vf-content\">\n          <p class=\"vf-text--body vf-text-body--2\">";
output += runtime.suppressValue(runtime.memberLookup((runtime.memberLookup((menuitem),"context")),"intro"), env.opts.autoescape);
output += "</p>\n\n          ";
output += runtime.suppressValue(env.getFilter("markdown").call(context, runtime.memberLookup((menuitem),"content")), env.opts.autoescape);
output += "\n          ";
output += "\n          ";
output += "\n        </div>\n      </section>\n    ";
output += "\n";
next(t_7);
;
}, function(t_11,t_10) {
if(t_11) { cb(t_11); return; }
frame = frame.pop();
next(t_4);
});
}, function(t_13,t_12) {
if(t_13) { cb(t_13); return; }
frame = frame.pop();
output += "\n<div class=\"vf-grid vf-grid__col-1\">\n  <div class=\"\">\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/browser/component-list.njk", false, "overview", false, function(t_15,t_14) {
if(t_15) { cb(t_15); return; }
callback(null,t_14);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_17,t_16) {
if(t_17) { cb(t_17); return; }
callback(null,t_16);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
output += "  </div>\n</div>\n";
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
return {
root: root
};

})();
})();
