/**
 * Precompiled Nunjucks template: vf-masthead.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-masthead"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += runtime.suppressValue(env.getExtension("spaceless")["run"](context,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_1 = "";t_1 += "<div\n  class=\"vf-masthead";
if(runtime.contextOrFrameLookup(context, frame, "mastheadTheme")) {
t_1 += " vf-masthead-theme--";
t_1 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "mastheadTheme"), env.opts.autoescape);
;
}
if(runtime.contextOrFrameLookup(context, frame, "hasTitleBlock")) {
t_1 += " vf-masthead--with-title-block";
;
}
if(runtime.contextOrFrameLookup(context, frame, "mastheadImage")) {
t_1 += " vf-masthead--has-image";
;
}
t_1 += "  | vf-u-fullbleed\"\n";
if(runtime.contextOrFrameLookup(context, frame, "mastheadImage")) {
t_1 += "style=\"\n    --vf-masthead__bg-image: url(";
t_1 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "mastheadImage"), env.opts.autoescape);
t_1 += ");\n    background-image: var(--vf-masthead__bg-image)\" data-vf-js-masthead";
;
}
t_1 += ">\n    <div class=\"vf-masthead__title\">\n      <h1 class=\"vf-masthead__heading\">\n        <a href=\"";
t_1 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "masthead_href"), env.opts.autoescape);
t_1 += "\" class=\"vf-masthead__heading__link\">";
t_1 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "masthead_heading"), env.opts.autoescape);
t_1 += "</a>";
if(runtime.contextOrFrameLookup(context, frame, "masthead_heading_additional")) {
t_1 += "<span class=\"vf-masthead__heading--additional\">";
t_1 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "masthead_heading_additional"), env.opts.autoescape);
t_1 += "</span>";
;
}
t_1 += "</h1>";
if(runtime.contextOrFrameLookup(context, frame, "masthead_subheading")) {
t_1 += "<h2 class=\"vf-masthead__subheading\">\n";
frame = frame.push();
var t_4 = runtime.contextOrFrameLookup(context, frame, "masthead_subheading");
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
t_1 += "        <span class=\"vf-masthead__subheading__text\">";
t_1 += runtime.suppressValue(runtime.memberLookup((t_5),"masthead_subheading_text"), env.opts.autoescape);
t_1 += "</span>\n";
;
}
}
frame = frame.pop();
t_1 += "      </h2>";
;
}
t_1 += "</div>\n</div>\n";
cb(null, t_1);
;
return t_1;
}
,null), true && env.opts.autoescape);
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
