/**
 * Precompiled Nunjucks template: vf-chatbot.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-chatbot"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
(function(cb) {if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"type") == "modal") {
output += "  <div class=\"vf-chatbot\" data-vf-js-chatbot>\n";
env.getExtension("render")["run"](context,"@vf-chatbot-fab", function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
env.getExtension("render")["run"](context,"@vf-chatbot-modal",{"config": runtime.contextOrFrameLookup(context, frame, "config")}, function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
output += runtime.suppressValue(t_3, true && env.opts.autoescape);
output += "  </div>\n";
cb()})});
}
else {
(function(cb) {if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "config")),"type") == "standalone") {
env.getExtension("render")["run"](context,"@vf-chatbot-standalone",{"config": runtime.contextOrFrameLookup(context, frame, "config")}, function(t_6,t_5) {
if(t_6) { cb(t_6); return; }
output += runtime.suppressValue(t_5, true && env.opts.autoescape);
cb()});
}
else {
cb()}
})(function(t_7) {
if(t_7) { cb(t_7); return; }cb()});
}
})(function(t_8) {
if(t_8) { cb(t_8); return; }if(parentTemplate) {
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
