/**
 * Precompiled Nunjucks template: errors.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["errors"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
var macro_t_1 = runtime.makeMacro(
["type", "message", "stack"], 
[], 
function (l_type, l_message, l_stack, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("type", l_type);
frame.set("message", l_message);
frame.set("stack", l_stack);
var t_2 = "";t_2 += "<div class=\"Error Error--render\">\n    <h4 class=\"Error-title\">Error rendering ";
t_2 += runtime.suppressValue(l_type, env.opts.autoescape);
t_2 += "</h4>\n    <div class=\"Error-message component-library-notes\">\n        ";
t_2 += runtime.suppressValue(env.getFilter("markdown").call(context, l_message), env.opts.autoescape);
t_2 += "\n    </div>\n";
if(l_stack) {
t_2 += "    <code class=\"Error-stack vf-code-example\">\n        <pre class=\"vf-code-example__pre\">";
t_2 += runtime.suppressValue(l_stack, env.opts.autoescape);
t_2 += "</pre>\n    </code>\n";
;
}
t_2 += "</div>\n";
;
frame = callerFrame;
return new runtime.SafeString(t_2);
});
context.addExport("renderError");
context.setVariable("renderError", macro_t_1);
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
