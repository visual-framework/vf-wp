/**
 * Precompiled Nunjucks template: foot.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["foot"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
frame = frame.push();
var t_3 = (lineno = 0, colno = 32, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"get"), "frctl[\"theme\"][\"get\"]", context, ["scripts"]));
if(t_3) {t_3 = runtime.fromIterator(t_3);
var t_2 = t_3.length;
for(var t_1=0; t_1 < t_3.length; t_1++) {
var t_4 = t_3[t_1];
frame.set("script", t_4);
frame.set("loop.index", t_1 + 1);
frame.set("loop.index0", t_1);
frame.set("loop.revindex", t_2 - t_1);
frame.set("loop.revindex0", t_2 - t_1 - 1);
frame.set("loop.first", t_1 === 0);
frame.set("loop.last", t_1 === t_2 - 1);
frame.set("loop.length", t_2);
output += "<!--<script src=\"";
output += runtime.suppressValue((lineno = 1, colno = 24, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, [t_4])), env.opts.autoescape);
output += "?cachebust=";
output += runtime.suppressValue((lineno = 1, colno = 64, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"get"), "frctl[\"theme\"][\"get\"]", context, ["version"])), env.opts.autoescape);
output += "\"></script>-->\n";
;
}
}
frame = frame.pop();
if(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"env")),"server")) {
output += "<script src=\"";
output += runtime.suppressValue((lineno = 4, colno = 20, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "path"), "path", context, ["/scripts/scripts.js"])), env.opts.autoescape);
output += "?cachebust=";
output += runtime.suppressValue((lineno = 4, colno = 75, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"theme")),"get"), "frctl[\"theme\"][\"get\"]", context, ["version"])), env.opts.autoescape);
output += "\"></script>\n";
;
}
else {
output += "<script src=\"https://dev.assets.emblstatic.net/vf/develop/scripts/scripts.js\"></script>\n";
;
}
output += "\n<script>\n  var resizeIframeChecks = 0;\n  function resizeIframe(obj) {\n    var obj = obj || document.getElementsByClassName(\"Preview-iframe\")[0];\n    var newHeight = obj.contentWindow.document.body.scrollHeight;\n    if (newHeight < 10) {\n      if (resizeIframeChecks < 2) {\n        // if less than 10px high, that's weird check again. maybe some JS is at play?\n        setTimeout(resizeIframe, 1000);\n        resizeIframeChecks ++;\n      }\n    } else {\n      obj.style.height = newHeight + 'px';\n    }\n  }\n</script>\n";
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
