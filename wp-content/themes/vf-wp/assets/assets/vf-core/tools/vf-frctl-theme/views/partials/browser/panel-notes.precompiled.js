/**
 * Precompiled Nunjucks template: panel-notes.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["panel-notes"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<hr class=\"vf-divider vf-u-margin__bottom--md vf-u-margin__top--md\">\n<h3 class=\"vf-text vf-text-heading--4\">Notes</h3>\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"notes")) {
output += "<div class=\"vf-content\">\n  ";
output += runtime.suppressValue(env.getFilter("async").call(context, (lineno = 4, colno = 28, runtime.callWrap(runtime.memberLookup((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "frctl")),"docs")),"renderString"), "frctl[\"docs\"][\"renderString\"]", context, [runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "entity")),"notes"),runtime.contextOrFrameLookup(context, frame, "renderEnv")]))), env.opts.autoescape);
output += "\n</div>\n";
;
}
else {
output += "<p class=\"vf-text\">There are no notes for this item.</p>\n";
;
}
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
