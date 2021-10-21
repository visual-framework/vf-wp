/**
 * Precompiled Nunjucks template: vf-location-nearest.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-location-nearest"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "\n";
if(runtime.contextOrFrameLookup(context, frame, "context")) {
output += "  ";
output += "\n";
;
}
output += "\n<div class=\"vf-location-nearest | vf-content\">\n  /* This is a demo only, to use this utility component follow the README.md to graft onto your UI */\n\n";
output += runtime.suppressValue(env.getExtension("markdown")["run"](context,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_1 = "";t_1 += "\n  ### Detected location\n\n  Your detected city is: <span data-vf-js-location-nearest-name><em>loading</em></span>\n\n  ### Available locations\n\n  <div data-vf-js-location-nearest-override-widget><em>loading</em></div>\n\n  ### Element activation\n\n  Elements tagged with `data-vf-js-location-nearest-activation-target=\"{locationId}\"` will receive clicks on location change. This is a simple method to activate diverse elements.\n\n";
cb(null, t_1);
;
return t_1;
}
,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_2 = "";t_2 += 4;
cb(null, t_2);
;
return t_2;
}
), true && env.opts.autoescape);
output += "\n  <div class=\"vf-tabs\">\n    <ul class=\"vf-tabs__list\" data-vf-js-tabs>\n      <li class=\"vf-tabs__item\">\n        <a class=\"vf-tabs__link\" href=\"#vf-tabs__section--1\" data-vf-js-location-nearest-activation-target=\"default\">Default</a>\n      </li>\n      <li class=\"vf-tabs__item\">\n        <a class=\"vf-tabs__link\" href=\"#vf-tabs__section--2\" data-vf-js-location-nearest-activation-target=\"heidelberg\">Heidelberg</a>\n      </li>\n      <li class=\"vf-tabs__item\">\n        <a class=\"vf-tabs__link\" href=\"#vf-tabs__section--3\" data-vf-js-location-nearest-activation-target=\"grenoble\">Grenoble</a>\n      </li>\n    </ul>\n  </div>\n\n  <div class=\"vf-tabs-content\" data-vf-js-tabs-content>\n    <section class=\"vf-tabs__section\" id=\"vf-tabs__section--1\">\n";
output += runtime.suppressValue(env.getExtension("markdown")["run"](context,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_3 = "";t_3 += "      A sample `data-vf-js-location-nearest-activation-target='default'` activation target\n";
cb(null, t_3);
;
return t_3;
}
,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_4 = "";t_4 += 8;
cb(null, t_4);
;
return t_4;
}
), true && env.opts.autoescape);
output += "    </section>\n    <section class=\"vf-tabs__section\" id=\"vf-tabs__section--2\">\n";
output += runtime.suppressValue(env.getExtension("markdown")["run"](context,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_5 = "";t_5 += "      A sample `data-vf-js-location-nearest-activation-target='heidelberg'` activation target\n";
cb(null, t_5);
;
return t_5;
}
,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_6 = "";t_6 += 8;
cb(null, t_6);
;
return t_6;
}
), true && env.opts.autoescape);
output += "    </section>\n    <section class=\"vf-tabs__section\" id=\"vf-tabs__section--3\">\n";
output += runtime.suppressValue(env.getExtension("markdown")["run"](context,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_7 = "";t_7 += "      A sample `data-vf-js-location-nearest-activation-target='grenoble'` activation target\n";
cb(null, t_7);
;
return t_7;
}
,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_8 = "";t_8 += 8;
cb(null, t_8);
;
return t_8;
}
), true && env.opts.autoescape);
output += "    </section>\n  </div>\n</div>\n\n\n  <div class=\"vf-tabs\">\n    <ul class=\"vf-tabs__list\" data-vf-js-tabs>\n      <li class=\"vf-tabs__item\">\n        <a class=\"vf-tabs__link\" href=\"#vf-tabs__section--1\" data-vf-js-location-nearest-activation-target=\"default\">Default tabset 2</a>\n      </li>\n      <li class=\"vf-tabs__item\">\n        <a class=\"vf-tabs__link\" href=\"#vf-tabs__section--2\" data-vf-js-location-nearest-activation-target=\"heidelberg\">Heidelberg tabset 2</a>\n      </li>\n    </ul>\n  </div>\n\n  <div class=\"vf-tabs-content\" data-vf-js-tabs-content>\n    <section class=\"vf-tabs__section\" id=\"vf-tabs__section--1\">\n";
output += runtime.suppressValue(env.getExtension("markdown")["run"](context,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_9 = "";t_9 += "      Showing a second set of tabs\n";
cb(null, t_9);
;
return t_9;
}
,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_10 = "";t_10 += 8;
cb(null, t_10);
;
return t_10;
}
), true && env.opts.autoescape);
output += "    </section>\n    <section class=\"vf-tabs__section\" id=\"vf-tabs__section--2\">\n";
output += runtime.suppressValue(env.getExtension("markdown")["run"](context,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_11 = "";t_11 += "      Showing a second set of tabs\n";
cb(null, t_11);
;
return t_11;
}
,function(cb) {
if(!cb) { cb = function(err) { if(err) { throw err; }}}
var t_12 = "";t_12 += 8;
cb(null, t_12);
;
return t_12;
}
), true && env.opts.autoescape);
output += "    </section>\n  </div>\n</div>\n\n\n<script type=\"text/javascript\">\n  window.onload = function () {\n    // You should do this in your central JS (scripts.js) as appropriate\n\n    // Configure an object of your locations to detect\n    let vfLocationNearestLocations = {\n      default: {\n        name: \"Heidelberg (default)\",\n        latlon: \"49.40768, 8.69079\"\n      },\n      barcelona: {\n        name: \"Barcelona\",\n        latlon: \"41.38879, 2.15899\"\n      },\n      grenoble: {\n        name: \"Grenoble\",\n        latlon: \"45.16667, 5.71667\"\n      },\n      hamburg: {\n        name: \"Hamburg\",\n        latlon: \"53.57532, 10.01534\"\n      },\n      heidelberg: {\n        name: \"Heidelberg\",\n        latlon: \"49.40768, 8.69079\"\n      },\n      hinxton: {\n        name: \"EMBL-EBI Hinxton\",\n        latlon: \"52.2, 0.11667\"\n      },\n      rome: {\n        name: \"Rome\",\n        latlon: \"41.89193, 12.51133\"\n      }\n    }\n    // Bootstrap location detection\n    vfLocationNearest(vfLocationNearestLocations);\n  };\n</script>\n";
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
