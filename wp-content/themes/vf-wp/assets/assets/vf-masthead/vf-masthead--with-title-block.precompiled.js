/**
 * Precompiled Nunjucks template: vf-masthead--with-title-block.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-masthead--with-title-block"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-masthead vf-masthead--with-title-block\" style=\"background-image: url('";
output += runtime.suppressValue(env.getFilter("path").call(context, "../../assets/vf-masthead/assets/group-bg.png"), env.opts.autoescape);
output += "')\">\n  <div class=\"vf-masthead__inner\">\n    <div class=\"vf-masthead__title\">\n      <h1 class=\"vf-masthead__heading\">\n        <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "masthead_link"), env.opts.autoescape);
output += "\" class=\"vf-masthead__heading__link\">Strategy &amp; Communications</a>\n      </h1>\n      <h2 class=\"vf-masthead__subheading\">\n        <span class=\"vf-masthead__location\">VF Hamburg</span>\n        <span class=\"vf-masthead__group\">Structural Biology</span>\n      </h2>\n    </div>\n\n    <form action=\"\" class=\"vf-masthead__form--search\">\n      <div class=\"vf-masthead__form__item\">\n        <label for=\"\" class=\"vf-masthead__form__label\">Search This Project</label>\n        <input type=\"text\" class=\"vf-masthead__form__input--text\" placeholder=\"Enter gene query…\">\n        <button class=\"vf-masthead__button vf-u-text-replacement\"><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 451 451\"><path d=\"M447.05 428l-109.6-109.6c29.4-33.8 47.2-77.9 47.2-126.1C384.65 86.2 298.35 0 192.35 0 86.25 0 .05 86.3.05 192.3s86.3 192.3 192.3 192.3c48.2 0 92.3-17.8 126.1-47.2L428.05 447c2.6 2.6 6.1 4 9.5 4s6.9-1.3 9.5-4c5.2-5.2 5.2-13.8 0-19zM26.95 192.3c0-91.2 74.2-165.3 165.3-165.3 91.2 0 165.3 74.2 165.3 165.3s-74.1 165.4-165.3 165.4c-91.1 0-165.3-74.2-165.3-165.4z\"/></svg></button>\n      </div>\n      <span class=\"search-examples\">Examples: <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "seacrch_example_href_1"), env.opts.autoescape);
output += "\">ASPM</a>, <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "seacrch_example_href_2"), env.opts.autoescape);
output += "\">Apoptosis</a>, <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "seacrch_example_href_3"), env.opts.autoescape);
output += "\">ENSMUSG00000021789</a></span>\n    </form>\n  </div>\n</div>\n";
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
