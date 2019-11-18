/**
 * Precompiled Nunjucks template: render.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["render"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
var macro_t_1 = runtime.makeMacro(
["rendered"], 
[], 
function (l_rendered, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("rendered", l_rendered);
var t_2 = "";if(env.getFilter("isError").call(context, l_rendered)) {
t_2 += "\n";
;
}
else {
t_2 += runtime.suppressValue(env.getFilter("highlight").call(context, env.getFilter("beautify").call(context, l_rendered),"html"), env.opts.autoescape);
t_2 += "\n";
;
}
;
frame = callerFrame;
return new runtime.SafeString(t_2);
});
context.addExport("entity");
context.setVariable("entity", macro_t_1);
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
