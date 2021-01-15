/**
 * Precompiled Nunjucks template: vf-news-container.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-news-container"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<section class=\"vf-news-container | embl-grid\">\n";
env.getExtension("render")["run"](context,"@vf-section-header", function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
output += "\n  <div class=\"vf-news-container__content\">\n";
env.getExtension("render")["run"](context,"@vf-summary--news-has-image", function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
output += runtime.suppressValue(t_3, true && env.opts.autoescape);
env.getExtension("render")["run"](context,"@vf-summary--news-has-image", function(t_6,t_5) {
if(t_6) { cb(t_6); return; }
output += runtime.suppressValue(t_5, true && env.opts.autoescape);
env.getExtension("render")["run"](context,"@vf-summary--news-has-image", function(t_8,t_7) {
if(t_8) { cb(t_8); return; }
output += runtime.suppressValue(t_7, true && env.opts.autoescape);
output += "  </div>\n\n  <!--\n  <div class=\"vf-news-container__sidebar\">\n    Optional vf-news-container__sidebar\n  </div>\n  -->\n</section>\n";
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
