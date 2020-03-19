/**
 * Precompiled Nunjucks template: vf-box.njk
 * This template exists because there is an issue using the vf-core precompiled template which expects nested box.box_title values
 * https://github.com/visual-framework/vf-core/issues/804
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-box"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  if(runtime.contextOrFrameLookup(context, frame, "deprecated_text")) {
  output += "<!-- ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "deprecated_text"), env.opts.autoescape);
  output += " -->";
  ;
  }
  output += "\n";
  output += "\n\n";
  if(runtime.contextOrFrameLookup(context, frame, "box_href")) {
  var t_1;
  t_1 = "a";
  frame.set("tags", t_1, true);
  if(frame.topLevel) {
  context.setVariable("tags", t_1);
  }
  if(frame.topLevel) {
  context.addExport("tags", t_1);
  }
  ;
  }
  else {
  var t_2;
  t_2 = "div";
  frame.set("tags", t_2, true);
  if(frame.topLevel) {
  context.setVariable("tags", t_2);
  }
  if(frame.topLevel) {
  context.addExport("tags", t_2);
  }
  ;
  }
  output += "<";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
  output += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "tags") == "a") {
  output += " href=\"";
  output += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "box_href")?runtime.contextOrFrameLookup(context, frame, "box_href"):"#"), env.opts.autoescape);
  output += "\"";
  ;
  }
  output += "\n  ";
  if(runtime.contextOrFrameLookup(context, frame, "id")) {
  output += " id=\"";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
  output += "\"";
  ;
  }
  output += "\n  ";
  output += "\n  class=\"vf-box";
  if(runtime.contextOrFrameLookup(context, frame, "box_href")) {
  output += " vf-box--is-link";
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "box_modifier")) {
  output += " ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_modifier"), env.opts.autoescape);
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
  output += " | ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
  ;
  }
  output += "\">\n  <h3 class=\"vf-box__heading\">";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_heading"), env.opts.autoescape);
  output += "</h3>\n  <p class=\"vf-box__text\">";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_text"), env.opts.autoescape);
  output += "</p>\n</";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
  output += ">\n";
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
  