/**
 * Precompiled Nunjucks template: doc.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["doc"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
env.getTemplate((runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "request")),"isPjax")?"layouts/pjax.njk":"layouts/frame.njk"), true, "doc", false, function(t_3,t_2) {
if(t_3) { cb(t_3); return; }
parentTemplate = t_2
for(var t_1 in parentTemplate.blocks) {
context.addBlock(t_1, parentTemplate.blocks[t_1]);
}
output += "\n";
(parentTemplate ? function(e, c, f, r, cb) { cb(""); } : context.getBlock("content"))(env, context, frame, runtime, function(t_5,t_4) {
if(t_5) { cb(t_5); return; }
output += t_4;
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
function b_content(env, context, frame, runtime, cb) {
var lineno = 2;
var colno = 3;
var output = "";
try {
var frame = frame.push(true);
output += "\n\n  <section class=\"vf-intro | embl-grid embl-grid--has-centered-content\">\n    <div>\n        <!-- empty -->\n    </div>\n    <div>\n      <h1 class=\"vf-intro__heading vf-intro__heading--has-tag\">";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "page")),"title"), env.opts.autoescape);
output += " <a href=\"\" class=\"vf-badge vf-badge--primary vf-badge--phases\">";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "status")),"length") >= 3) {
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "status")),"val"), env.opts.autoescape);
;
}
else {
output += "alpha";
;
}
output += "</a></h1>\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "page")),"lede")) {
output += "        <p class=\"vf-lede\">";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "page")),"lede"), env.opts.autoescape);
output += "</p>\n";
;
}
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "page")),"intro")) {
output += "        <p class=\"vf-intro__text\">";
output += runtime.suppressValue(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "page")),"intro"), env.opts.autoescape);
output += "</p>\n";
;
}
output += "    </div>\n  </section>\n";
if(runtime.contextOrFrameLookup(context, frame, "docContent")) {
output += "  <div class=\"vf-grid vf-grid__col-1\">\n    <div class=\"vf-content\">\n\n";
context.getBlock("docContent")(env, context, frame, runtime, function(t_7,t_6) {
if(t_7) { cb(t_7); return; }
output += t_6;
output += "\n    </div>\n  </div>\n";
});
}
output += "\n";
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "request")),"path") == "/") {
output += "  ";
output += "\n";
var tasks = [];
tasks.push(
function(callback) {
env.getTemplate("partials/content/overview.njk", false, "doc", false, function(t_9,t_8) {
if(t_9) { cb(t_9); return; }
callback(null,t_8);});
});
tasks.push(
function(template, callback){
template.render(context.getVariables(), frame, function(t_11,t_10) {
if(t_11) { cb(t_11); return; }
callback(null,t_10);});
});
tasks.push(
function(result, callback){
output += result;
callback(null);
});
env.waterfall(tasks, function(){
});
}
output += "\n\n";
cb(null, output);
;
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
function b_docContent(env, context, frame, runtime, cb) {
var lineno = 23;
var colno = 9;
var output = "";
try {
var frame = frame.push(true);
cb(null, output);
;
} catch (e) {
  cb(runtime.handleError(e, lineno, colno));
}
}
return {
b_content: b_content,
b_docContent: b_docContent,
root: root
};

})();
})();
