/**
 * Precompiled Nunjucks template: vf-article-meta-information.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-article-meta-information"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<aside class=\"vf-article-meta-information\">\n  <div class=\"vf-author | vf-article-meta-info__author\">\n    <p class=\"vf-author__name | vf-text-body--6\">\n      <a class=\"vf-link\" href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta__url"), env.opts.autoescape);
output += "\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "author__name"), env.opts.autoescape);
output += "</a>\n    </p>\n   <a class=\"vf-author--avatar__link | vf-link\" href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta__url"), env.opts.autoescape);
output += "\">\n     <!-- wrapping the avatar in a link is optional -->\n     <img class=\"vf-author--avatar\" src=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "author__avatar"), env.opts.autoescape);
output += "\" alt=\"FirstName Surname\" />\n   </a>\n  </div>\n  <div class=\"vf-meta__details\">\n    <p class=\"vf-meta__topics | vf-text-body--6\">Topic:\n";
frame = frame.push();
var t_3 = runtime.contextOrFrameLookup(context, frame, "meta__topics");
if(t_3) {t_3 = runtime.fromIterator(t_3);
var t_2 = t_3.length;
for(var t_1=0; t_1 < t_3.length; t_1++) {
var t_4 = t_3[t_1];
frame.set("item", t_4);
frame.set("loop.index", t_1 + 1);
frame.set("loop.index0", t_1);
frame.set("loop.revindex", t_2 - t_1);
frame.set("loop.revindex0", t_2 - t_1 - 1);
frame.set("loop.first", t_1 === 0);
frame.set("loop.last", t_1 === t_2 - 1);
frame.set("loop.length", t_2);
output += "      <a href=\"JavaScript:Void(0);\" class=\"vf-link\">";
output += runtime.suppressValue(t_4, env.opts.autoescape);
output += "</a>\n";
;
}
}
frame = frame.pop();
output += "    <p class=\"vf-meta__date | vf-text-body--6\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta__date"), env.opts.autoescape);
output += "</p>\n  </div>\n  <div class=\"vf-meta__comments\">\n    <p class=\"vf-text-body--6\">Comments — <a href=\"JavaScript:Void(0);\" class=\"vf-link\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta__comment_count"), env.opts.autoescape);
output += "</a></p>\n  </div>\n  <div class=\"vf-links | vf-article-meta-info__links\">\n    <h6 class=\"vf-text vf-text-body--6\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta__links_title"), env.opts.autoescape);
output += "</h6>\n";
frame = frame.push();
var t_7 = runtime.contextOrFrameLookup(context, frame, "meta__links");
if(t_7) {t_7 = runtime.fromIterator(t_7);
var t_6 = t_7.length;
for(var t_5=0; t_5 < t_7.length; t_5++) {
var t_8 = t_7[t_5];
frame.set("item", t_8);
frame.set("loop.index", t_5 + 1);
frame.set("loop.index0", t_5);
frame.set("loop.revindex", t_6 - t_5);
frame.set("loop.revindex0", t_6 - t_5 - 1);
frame.set("loop.first", t_5 === 0);
frame.set("loop.last", t_5 === t_6 - 1);
frame.set("loop.length", t_6);
output += "      <a href=\"JavaScript:Void(0);\" class=\"vf-link | vf-text-body--6\">";
output += runtime.suppressValue(t_8, env.opts.autoescape);
output += "</a>\n";
;
}
}
frame = frame.pop();
output += "  </div>\n</aside>\n";
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
