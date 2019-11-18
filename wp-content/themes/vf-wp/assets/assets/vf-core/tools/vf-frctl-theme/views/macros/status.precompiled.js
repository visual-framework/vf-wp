/**
 * Precompiled Nunjucks template: status.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["status"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
var macro_t_1 = runtime.makeMacro(
["color", "label"], 
[], 
function (l_color, l_label, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("color", l_color);
frame.set("label", l_label);
var t_2 = "";t_2 += "<span class=\"vf-badge vf-badge--square\" style=\"background-color: ";
t_2 += runtime.suppressValue(l_color, env.opts.autoescape);
t_2 += ";\" title=\"";
t_2 += runtime.suppressValue(l_label, env.opts.autoescape);
t_2 += "\"></span>";
;
frame = callerFrame;
return new runtime.SafeString(t_2);
});
context.setVariable("_dot", macro_t_1);
output += "\n";
var macro_t_3 = runtime.makeMacro(
["color", "label"], 
[], 
function (l_color, l_label, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("color", l_color);
frame.set("label", l_label);
var t_4 = "";t_4 += "<span class=\"vf-badge\" style=\"margin-right: 8px; position: relative; top: 1px; background-color: ";
t_4 += runtime.suppressValue(l_color, env.opts.autoescape);
t_4 += "; border-color: ";
t_4 += runtime.suppressValue(l_color, env.opts.autoescape);
t_4 += ";\">";
t_4 += runtime.suppressValue(l_label, env.opts.autoescape);
t_4 += "</span>\n";
;
frame = callerFrame;
return new runtime.SafeString(t_4);
});
context.setVariable("_tag", macro_t_3);
output += "\n";
var macro_t_5 = runtime.makeMacro(
["status"], 
[], 
function (l_status, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("status", l_status);
var t_6 = "";if(runtime.memberLookup((l_status),"statuses")) {
frame = frame.push();
var t_9 = runtime.memberLookup((l_status),"statuses");
if(t_9) {t_9 = runtime.fromIterator(t_9);
var t_8 = t_9.length;
for(var t_7=0; t_7 < t_9.length; t_7++) {
var t_10 = t_9[t_7];
frame.set("stat", t_10);
frame.set("loop.index", t_7 + 1);
frame.set("loop.index0", t_7);
frame.set("loop.revindex", t_8 - t_7);
frame.set("loop.revindex0", t_8 - t_7 - 1);
frame.set("loop.first", t_7 === 0);
frame.set("loop.last", t_7 === t_8 - 1);
frame.set("loop.length", t_8);
t_6 += runtime.suppressValue((lineno = 9, colno = 7, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "_dot"), "_dot", context, [runtime.memberLookup((t_10),"color"),runtime.memberLookup((t_10),"label")])), env.opts.autoescape);
t_6 += "\n";
;
}
}
frame = frame.pop();
;
}
else {
t_6 += runtime.suppressValue((lineno = 12, colno = 7, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "_dot"), "_dot", context, [runtime.memberLookup((l_status),"color"),runtime.memberLookup((l_status),"label")])), env.opts.autoescape);
t_6 += "\n";
;
}
;
frame = callerFrame;
return new runtime.SafeString(t_6);
});
context.setVariable("_dots", macro_t_5);
output += "\n";
var macro_t_11 = runtime.makeMacro(
["status"], 
[], 
function (l_status, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("status", l_status);
var t_12 = "";if(runtime.memberLookup((l_status),"statuses")) {
frame = frame.push();
var t_15 = runtime.memberLookup((l_status),"statuses");
if(t_15) {t_15 = runtime.fromIterator(t_15);
var t_14 = t_15.length;
for(var t_13=0; t_13 < t_15.length; t_13++) {
var t_16 = t_15[t_13];
frame.set("stat", t_16);
frame.set("loop.index", t_13 + 1);
frame.set("loop.index0", t_13);
frame.set("loop.revindex", t_14 - t_13);
frame.set("loop.revindex0", t_14 - t_13 - 1);
frame.set("loop.first", t_13 === 0);
frame.set("loop.last", t_13 === t_14 - 1);
frame.set("loop.length", t_14);
t_12 += runtime.suppressValue((lineno = 19, colno = 7, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "_tag"), "_tag", context, [runtime.memberLookup((t_16),"color"),runtime.memberLookup((t_16),"label")])), env.opts.autoescape);
t_12 += "\n";
;
}
}
frame = frame.pop();
;
}
else {
t_12 += runtime.suppressValue((lineno = 22, colno = 7, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "_tag"), "_tag", context, [runtime.memberLookup((l_status),"color"),runtime.memberLookup((l_status),"label")])), env.opts.autoescape);
t_12 += "\n";
;
}
;
frame = callerFrame;
return new runtime.SafeString(t_12);
});
context.setVariable("_tags", macro_t_11);
output += "\n";
var macro_t_17 = runtime.makeMacro(
["status", "modifier"], 
[], 
function (l_status, l_modifier, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("status", l_status);
frame.set("modifier", l_modifier);
var t_18 = "";if(l_status) {
t_18 += "<div class=\"Status Status--labelled";
if(l_modifier) {
t_18 += " Status--";
t_18 += runtime.suppressValue(l_modifier, env.opts.autoescape);
;
}
t_18 += "\">\n    <label class=\"Status-label\">";
t_18 += runtime.suppressValue(runtime.memberLookup((l_status),"label"), env.opts.autoescape);
t_18 += " HELLO</label>\n    ";
t_18 += runtime.suppressValue((lineno = 30, colno = 12, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "_dots"), "_dots", context, [l_status])), env.opts.autoescape);
t_18 += "\n</div>\n";
;
}
;
frame = callerFrame;
return new runtime.SafeString(t_18);
});
context.addExport("labelled");
context.setVariable("labelled", macro_t_17);
output += "\n";
var macro_t_19 = runtime.makeMacro(
["status", "modifier"], 
[], 
function (l_status, l_modifier, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("status", l_status);
frame.set("modifier", l_modifier);
var t_20 = "";if(l_status) {
t_20 += runtime.suppressValue((lineno = 37, colno = 8, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "_dots"), "_dots", context, [l_status])), env.opts.autoescape);
t_20 += "\n";
;
}
;
frame = callerFrame;
return new runtime.SafeString(t_20);
});
context.addExport("unlabelled");
context.setVariable("unlabelled", macro_t_19);
output += "\n";
var macro_t_21 = runtime.makeMacro(
["status", "modifier"], 
[], 
function (l_status, l_modifier, kwargs) {
var callerFrame = frame;
frame = new runtime.Frame();
kwargs = kwargs || {};
if (Object.prototype.hasOwnProperty.call(kwargs, "caller")) {
frame.set("caller", kwargs.caller); }
frame.set("status", l_status);
frame.set("modifier", l_modifier);
var t_22 = "";if(l_status) {
t_22 += "\n";
t_22 += runtime.suppressValue((lineno = 44, colno = 7, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "_tag"), "_tag", context, [runtime.memberLookup((l_status),"color"),runtime.memberLookup((l_status),"label")])), env.opts.autoescape);
t_22 += "\n\n";
;
}
;
frame = callerFrame;
return new runtime.SafeString(t_22);
});
context.addExport("tag");
context.setVariable("tag", macro_t_21);
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
