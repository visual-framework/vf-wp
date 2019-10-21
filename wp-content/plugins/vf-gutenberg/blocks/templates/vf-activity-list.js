/**
 * Precompiled Nunjucks template: @visual-framework/vf-activity-list
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-activity-list"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-activity\">\n\n  <p class=\"vf-activity__date\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "date"), env.opts.autoescape);
output += "</p>\n\n  <ul class=\"vf-activity__list | vf-list\">\n\n";
frame = frame.push();
var t_3 = runtime.contextOrFrameLookup(context, frame, "list");
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
output += "\n    <li class=\"vf-activity__item\">\n      ";
output += runtime.suppressValue(t_4, env.opts.autoescape);
output += "\n    </li>\n\n";
;
}
}
frame = frame.pop();
output += "\n  </ul>\n</div>\n";
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
