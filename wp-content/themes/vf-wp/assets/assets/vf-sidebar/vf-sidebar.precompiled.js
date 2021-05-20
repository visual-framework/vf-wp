/**
 * Precompiled Nunjucks template: vf-sidebar.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-sidebar"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "\n";
if(runtime.contextOrFrameLookup(context, frame, "context")) {
var t_1;
t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"sidebar_position");
frame.set("sidebar_position", t_1, true);
if(frame.topLevel) {
context.setVariable("sidebar_position", t_1);
}
if(frame.topLevel) {
context.addExport("sidebar_position", t_1);
}
var t_2;
t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"sidebar_spacing");
frame.set("sidebar_spacing", t_2, true);
if(frame.topLevel) {
context.setVariable("sidebar_spacing", t_2);
}
if(frame.topLevel) {
context.addExport("sidebar_spacing", t_2);
}
var t_3;
t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"sidebar__main_content_width");
frame.set("sidebar__main_content_width", t_3, true);
if(frame.topLevel) {
context.setVariable("sidebar__main_content_width", t_3);
}
if(frame.topLevel) {
context.addExport("sidebar__main_content_width", t_3);
}
;
}
output += "\n<div class=\"vf-sidebar";
if((runtime.contextOrFrameLookup(context, frame, "sidebar_position") == "left") || (runtime.contextOrFrameLookup(context, frame, "sidebar_position") == "start")) {
output += " vf-sidebar--start";
;
}
else {
if((runtime.contextOrFrameLookup(context, frame, "sidebar_position") == "right") || (runtime.contextOrFrameLookup(context, frame, "sidebar_position") == "end")) {
output += " vf-sidebar--end";
;
}
;
}
if(runtime.contextOrFrameLookup(context, frame, "sidebar_spacing")) {
output += " vf-sidebar--";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "sidebar_spacing"), env.opts.autoescape);
;
}
output += "\"";
if(runtime.contextOrFrameLookup(context, frame, "sidebar__main_content_width")) {
output += "      style=\"--vf-sidebar-main-width: ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "sidebar__main_content_width"), env.opts.autoescape);
output += ";\"";
;
}
output += ">\n  <div class=\"vf-sidebar__inner\">";
if((runtime.contextOrFrameLookup(context, frame, "sidebar_position") == "left") || (runtime.contextOrFrameLookup(context, frame, "sidebar_position") == "start")) {
output += "    <div>\n      <img src=\"https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/CABANA_group02438.jpg?&width=300\" alt=\"A couple sat on a sofa looking at a laptop\">\n    </div>";
;
}
output += "<div>\n      <p class=\"vf-u-type__text-body--2 vf-u-margin--0\">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Incidunt, eum dolorem accusamus omnis ratione ex quidem, ducimus ab explicabo maxime animi ullam numquam nihil dignissimos quam vero. Recusandae, nihil nam? Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis nesciunt distinctio animi earum sint, cupiditate labore voluptate a pariatur maxime beatae dolor odit ducimus saepe? Laboriosam voluptatum delectus natus corporis.</p>\n\n    </div>";
if((runtime.contextOrFrameLookup(context, frame, "sidebar_position") == "right") || (runtime.contextOrFrameLookup(context, frame, "sidebar_position") == "end")) {
output += "    <div>\n      <img src=\"https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/CABANA_group02438.jpg?&width=300\" alt=\"A couple sat on a sofa looking at a laptop\">\n    </div>";
;
}
output += "</div>\n\n</div>\n";
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
