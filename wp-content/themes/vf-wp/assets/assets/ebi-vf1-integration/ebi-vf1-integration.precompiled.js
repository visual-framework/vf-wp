/**
 * Precompiled Nunjucks template: ebi-vf1-integration.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["ebi-vf1-integration"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<link rel=\"stylesheet\" href=\"https://ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.3/css/ebi-global.css\" type=\"text/css\" media=\"all\" />\n<link rel=\"stylesheet\" href=\"https://ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.3/css/theme-embl-petrol.css\" type=\"text/css\" media=\"all\" />\n\n<p>A demonstration of a few fixes. To make an entire page \"VF 2.x-ish\" add `class=\"ebi-vf1-integration\"` to your body element.</p>\n\n<div class=\"ebi-vf1-integration\">\n\n  <h3>With .ebi-vf1-integration</h3>\n\n  <p>With <code class=\"vf-code-example\">.ebi-vf1-integration</code></p>\n\n  <h1>h1</h1>\n  <h2>h2</h2>\n  <h3>h3</h3>\n\n  <div><strong>I'm bold</strong></div>\n\n  <div>";
env.getExtension("render")["run"](context,"@vf-button--link", function(t_2,t_1) {
if(t_2) { cb(t_2); return; }
output += runtime.suppressValue(t_1, true && env.opts.autoescape);
output += "</div>\n\n  <div><a href=\"https://example.com\">A link</a></div>\n\n  <input type=\"search\" class=\"vf-form__input | vf-search__input\" value=\"\" name=\"s\">\n\n</div>\n\n<br/><br/>\n\n<hr class=\"vf-divider\" />\n\n<div class=\"\">\n\n  <h3>With<em>out</em> .ebi-vf1-integration</h3>\n\n  <p>With<em>out</em> <code class=\"vf-code-example\">.ebi-vf1-integration</code></p>\n\n  <h1>h1</h1>\n  <h2>h2</h2>\n  <h3>h3</h3>\n\n  <div><strong>I'm bold</strong></div>\n\n  <div>";
env.getExtension("render")["run"](context,"@vf-button--link", function(t_4,t_3) {
if(t_4) { cb(t_4); return; }
output += runtime.suppressValue(t_3, true && env.opts.autoescape);
output += "</div>\n  (click the link to see the visited state)\n\n  <div><a href=\"https://example.com\">A link</a></div>\n\n  <input type=\"search\" class=\"vf-form__input | vf-search__input\" value=\"\" name=\"s\">\n\n</div>\n";
if(parentTemplate) {
parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
} else {
cb(null, output);
}
})});
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
root: root
};

})();
})();
