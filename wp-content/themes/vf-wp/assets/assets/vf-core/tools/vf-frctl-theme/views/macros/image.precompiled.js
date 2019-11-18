/**
 * Precompiled Nunjucks template: image.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["image"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
var macro_t_1 = runtime.makeMacro(
["name"], 
["fill", "width", "height", "viewbox"], 
function (l_name, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("name", l_name);
frame.set("fill", Object.prototype.hasOwnProperty.call(kwargs, "fill") ? kwargs["fill"] : "#222");frame.set("width", Object.prototype.hasOwnProperty.call(kwargs, "width") ? kwargs["width"] : "20px");frame.set("height", Object.prototype.hasOwnProperty.call(kwargs, "height") ? kwargs["height"] : "20px");frame.set("viewbox", Object.prototype.hasOwnProperty.call(kwargs, "viewbox") ? kwargs["viewbox"] : "0 0 320 320");var t_2 = "";t_2 += "<svg width=\"";
t_2 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "width"), env.opts.autoescape);
t_2 += "\" height=\"";
t_2 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "height"), env.opts.autoescape);
t_2 += "\" viewBox=\"";
t_2 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "viewbox"), env.opts.autoescape);
t_2 += "\" fill=\"";
t_2 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "fill"), env.opts.autoescape);
t_2 += "\">\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("icons/" + l_name + ".njk", false, "image", false, function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
callback(null,t_3);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_6,t_5) {
if(t_6) { cb(t_6); return; }
callback(null,t_5);});
});
tasks.push(
function(result, callback){
t_2 += result;
callback(null);
});
env.waterfall(tasks, function(){
t_2 += "</svg>\n";
});
frame = callerFrame;
return new runtime.SafeString(t_2);
});
context.addExport("svg");
context.setVariable("svg", macro_t_1);
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
