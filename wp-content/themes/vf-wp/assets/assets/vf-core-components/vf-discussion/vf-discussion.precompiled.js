/**
 * Precompiled Nunjucks template: vf-discussion.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-discussion"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<section class=\"vf-discussion\">\n  <h3 class=\"vf-discussion__title\"><span>";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "number"), env.opts.autoescape);
output += "</span> ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "text"), env.opts.autoescape);
output += "</h3>\n\n  <ol class=\"vf-discussion__list | vf-list\">\n";
frame = frame.push();
var t_3 = runtime.contextOrFrameLookup(context, frame, "discussions");
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
output += "    <li class=\"vf-discussion__item\">\n      <div class=\"vf-discussion__meta\">\n        <img class=\"vf-discussion__author-avatar\" src=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, runtime.memberLookup((t_4),"imageUrl")), env.opts.autoescape);
output += "\" alt=\"\" loading=\"lazy\">\n        <cite class=\"vf-discussion__author\">";
output += runtime.suppressValue(runtime.memberLookup((t_4),"name"), env.opts.autoescape);
output += "</cite><span> says:</span>\n        <p class=\"vf-discussion__date\">";
output += runtime.suppressValue(runtime.memberLookup((t_4),"date"), env.opts.autoescape);
output += "</p>\n      </div>\n      <div class=\"vf-discussion__comment | vf-content\">\n        ";
output += runtime.suppressValue(runtime.memberLookup((t_4),"content"), env.opts.autoescape);
output += "\n      </div>\n      <button class=\"vf-button vf-button--outline vf-button--primary | vf-discussion__reply\">";
output += runtime.suppressValue(runtime.memberLookup((t_4),"button_text"), env.opts.autoescape);
output += "</button>\n";
if(runtime.memberLookup((t_4),"secondary_button")) {
output += "      <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_4),"vf_discussion_href"), env.opts.autoescape);
output += "\" class=\"vf-button vf-button--outline vf-button--tertiary | vf-discussion__action\">";
output += runtime.suppressValue(runtime.memberLookup((t_4),"secondary_button"), env.opts.autoescape);
output += "</a>\n";
;
}
output += "\n";
if(runtime.memberLookup((t_4),"nested")) {
output += "        <ol class=\"vf-discussion__list | vf-list\">\n";
frame = frame.push();
var t_7 = runtime.memberLookup((t_4),"nested");
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
output += "          <li class=\"vf-discussion__item\">\n            <div class=\"vf-discussion__meta\">\n              <img class=\"vf-discussion__author-avatar\" src=\"";
output += runtime.suppressValue(env.getFilter("path").call(context, runtime.memberLookup((t_8),"imageUrl")), env.opts.autoescape);
output += "\" alt=\"\" loading=\"lazy\">\n              <cite class=\"vf-discussion__author\">";
output += runtime.suppressValue(runtime.memberLookup((t_8),"name"), env.opts.autoescape);
output += "</cite><span> says:</span>\n              <p class=\"vf-discussion__date\">";
output += runtime.suppressValue(runtime.memberLookup((t_8),"date"), env.opts.autoescape);
output += "</p>\n            </div>\n            <div class=\"vf-discussion__comment | vf-content\">\n              ";
output += runtime.suppressValue(runtime.memberLookup((t_8),"content"), env.opts.autoescape);
output += "\n            </div>\n            <button class=\"vf-button vf-button--outline vf-button--primary | vf-discussion__reply\">";
output += runtime.suppressValue(runtime.memberLookup((t_8),"button_text"), env.opts.autoescape);
output += "</button>\n";
if(runtime.memberLookup((t_8),"secondary_button")) {
output += "            <a href=\"";
output += runtime.suppressValue(runtime.memberLookup((t_8),"vf_discussion_href"), env.opts.autoescape);
output += "\" class=\"vf-button vf-button--outline vf-button--tertiary | vf-discussion__action\">";
output += runtime.suppressValue(runtime.memberLookup((t_8),"secondary_button"), env.opts.autoescape);
output += "</a>\n";
;
}
output += "          </li>\n";
;
}
}
frame = frame.pop();
output += "        </ol>\n";
;
}
output += "\n    </li>\n";
;
}
}
frame = frame.pop();
output += "\n  </ol>\n</section>\n";
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
