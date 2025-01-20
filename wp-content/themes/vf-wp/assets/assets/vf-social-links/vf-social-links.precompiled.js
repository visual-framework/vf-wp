/**
 * Precompiled Nunjucks template: vf-social-links.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-social-links"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
if(runtime.contextOrFrameLookup(context, frame, "context")) {
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"variant");
frame.set("variant", t_1, true);
if(frame.topLevel) {
context.setVariable("variant", t_1);
}
if(frame.topLevel) {
context.addExport("variant", t_1);
}
;
}
output += "<svg aria-hidden=\"true\" display=\"none\" class=\"vf-icon-collection vf-icon-collection--social\">\n  <defs>\n    <g id=\"vf-social--linkedin\">\n      <rect xmlns=\"http://www.w3.org/2000/svg\" width=\"5\" height=\"14\" x=\"2\" y=\"8.5\" rx=\".5\" ry=\".5\"/><ellipse xmlns=\"http://www.w3.org/2000/svg\" cx=\"4.48\" cy=\"4\" rx=\"2.48\" ry=\"2.5\"/><path xmlns=\"http://www.w3.org/2000/svg\" d=\"M18.5,22.5h3A.5.5,0,0,0,22,22V13.6C22,9.83,19.87,8,16.89,8a4.21,4.21,0,0,0-3.17,1.27A.41.41,0,0,1,13,9a.5.5,0,0,0-.5-.5h-3A.5.5,0,0,0,9,9V22a.5.5,0,0,0,.5.5h3A.5.5,0,0,0,13,22V14.5a2.5,2.5,0,0,1,5,0V22A.5.5,0,0,0,18.5,22.5Z\"/>\n    </g>\n    <g id=\"vf-social--facebook\">\n      <path xmlns=\"http://www.w3.org/2000/svg\" d=\"m18.14 7.17a.5.5 0 0 0 -.37-.17h-3.77v-1.41c0-.28.06-.6.51-.6h3a.44.44 0 0 0 .35-.15.5.5 0 0 0 .14-.34v-4a.5.5 0 0 0 -.5-.5h-4.33c-4.8 0-5.17 4.1-5.17 5.35v1.65h-2.5a.5.5 0 0 0 -.5.5v4a.5.5 0 0 0 .5.5h2.5v11.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 .5-.5v-11.5h3.35a.5.5 0 0 0 .5-.45l.42-4a.5.5 0 0 0 -.13-.38z\"/>\n    </g>\n    <g id=\"vf-social--twitter\">\n      <path d=\"M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z\"></path>\n    </g>\n    <g id=\"vf-social--youtube\">\n      <path xmlns=\"http://www.w3.org/2000/svg\" d=\"M20.06,3.5H3.94A3.94,3.94,0,0,0,0,7.44v9.12A3.94,3.94,0,0,0,3.94,20.5H20.06A3.94,3.94,0,0,0,24,16.56V7.44A3.94,3.94,0,0,0,20.06,3.5ZM16.54,12,9.77,16.36A.5.5,0,0,1,9,15.94V7.28a.5.5,0,0,1,.77-.42l6.77,4.33a.5.5,0,0,1,0,.84Z\"/>\n    </g>\n    <g id=\"vf-social--instagram\">\n      <path xmlns=\"http://www.w3.org/2000/svg\" d=\"M17.5,0H6.5A6.51,6.51,0,0,0,0,6.5v11A6.51,6.51,0,0,0,6.5,24h11A6.51,6.51,0,0,0,24,17.5V6.5A6.51,6.51,0,0,0,17.5,0ZM12,17.5A5.5,5.5,0,1,1,17.5,12,5.5,5.5,0,0,1,12,17.5Zm6.5-11A1.5,1.5,0,1,1,20,5,1.5,1.5,0,0,1,18.5,6.5Z\"/>\n    </g>\n    <g id=\"vf-social--bluesky\">\n      <path xmlns=\"http://www.w3.org/2000/svg\" d=\"M12 10.8c-1.087 -2.114 -4.046 -6.053 -6.798 -7.995C2.566 0.944 1.561 1.266 0.902 1.565 0.139 1.908 0 3.08 0 3.768c0 0.69 0.378 5.65 0.624 6.479 0.815 2.736 3.713 3.66 6.383 3.364 0.136 -0.02 0.275 -0.039 0.415 -0.056 -0.138 0.022 -0.276 0.04 -0.415 0.056 -3.912 0.58 -7.387 2.005 -2.83 7.078 5.013 5.19 6.87 -1.113 7.823 -4.308 0.953 3.195 2.05 9.271 7.733 4.308 4.267 -4.308 1.172 -6.498 -2.74 -7.078a8.741 8.741 0 0 1 -0.415 -0.056c0.14 0.017 0.279 0.036 0.415 0.056 2.67 0.297 5.568 -0.628 6.383 -3.364 0.246 -0.828 0.624 -5.79 0.624 -6.478 0 -0.69 -0.139 -1.861 -0.902 -2.206 -0.659 -0.298 -1.664 -0.62 -4.3 1.24C16.046 4.748 13.087 8.687 12 10.8\"></path>\n    </g>\n  </defs>\n</svg>\n\n<div class=\"vf-social-links";
if(runtime.contextOrFrameLookup(context, frame, "variant") == "outline") {
output += "--outline";
;
}
if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
output += " | ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
;
}
output += "\">";
if(runtime.contextOrFrameLookup(context, frame, "heading")) {
output += "<h3 class=\"vf-social-links__heading\">\n      ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "heading"), env.opts.autoescape);
output += "\n    </h3>";
;
}
output += "<ul class=\"vf-social-links__list\">";
frame = frame.push();
var t_4 = runtime.contextOrFrameLookup(context, frame, "social_accounts");
if(t_4) {t_4 = runtime.fromIterator(t_4);
var t_3 = t_4.length;
for(var t_2=0; t_2 < t_4.length; t_2++) {
var t_5 = t_4[t_2];
frame.set("item", t_5);
frame.set("loop.index", t_2 + 1);
frame.set("loop.index0", t_2);
frame.set("loop.revindex", t_3 - t_2);
frame.set("loop.revindex0", t_3 - t_2 - 1);
frame.set("loop.first", t_2 === 0);
frame.set("loop.last", t_2 === t_3 - 1);
frame.set("loop.length", t_3);
output += "<li class=\"vf-social-links__item\">\n\n        <a class=\"vf-social-links__link\" href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_5),"url"), env.opts.autoescape);
output += "\" aria-label=\"Follow us on ";
output += runtime.suppressValue(runtime.memberLookup((t_5),"name"), env.opts.autoescape);
output += "\">\n          <svg aria-hidden=\"true\" class=\"vf-icon vf-icon--social vf-icon--";
output += runtime.suppressValue(runtime.memberLookup((t_5),"name"), env.opts.autoescape);
output += "\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" preserveAspectRatio=\"xMinYMin\">\n            <use xlink:href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_5),"useUrl"), env.opts.autoescape);
output += "\"></use>\n          </svg>\n        </a>\n\n      </li>";
;
}
}
frame = frame.pop();
output += "</ul>\n\n</div>\n";
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
