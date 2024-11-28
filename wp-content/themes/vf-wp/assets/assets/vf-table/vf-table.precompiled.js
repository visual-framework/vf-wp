/**
 * Precompiled Nunjucks template: vf-table.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-table"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<svg class=\"vf-icon-sprite vf-icon-sprite--tables\" display=\"none\">\n\t<defs>\n\t\t<g id=\"vf-table-sortable--up\">\n\t\t\t<path xmlns=\"http://www.w3.org/2000/svg\" d=\"M17.485,5.062,12.707.284a1.031,1.031,0,0,0-1.415,0L6.515,5.062a1,1,0,0,0,.707,1.707H10.25a.25.25,0,0,1,.25.25V22.492a1.5,1.5,0,1,0,3,0V7.019a.249.249,0,0,1,.25-.25h3.028a1,1,0,0,0,.707-1.707Z\"/>\n\t\t</g>\n\t\t<g id=\"vf-table-sortable--down\">\n\t\t\t<path xmlns=\"http://www.w3.org/2000/svg\" d=\"M17.7,17.838a1,1,0,0,0-.924-.617H13.75a.249.249,0,0,1-.25-.25V1.5a1.5,1.5,0,0,0-3,0V16.971a.25.25,0,0,1-.25.25H7.222a1,1,0,0,0-.707,1.707l4.777,4.778a1,1,0,0,0,1.415,0l4.778-4.778A1,1,0,0,0,17.7,17.838Z\"/>\n\t\t</g>\n\t\t<g id=\"vf-table-sortable\">\n\t\t\t<path xmlns=\"http://www.w3.org/2000/svg\" d=\"M9,19a1,1,0,0,0-.707,1.707l3,3a1,1,0,0,0,1.414,0l3-3A1,1,0,0,0,15,19H13.5a.25.25,0,0,1-.25-.25V5.249A.25.25,0,0,1,13.5,5H15a1,1,0,0,0,.707-1.707l-3-3a1,1,0,0,0-1.414,0l-3,3A1,1,0,0,0,9,5h1.5a.25.25,0,0,1,.25.25v13.5a.25.25,0,0,1-.25.25Z\"/>\n\t\t</g>\n\t</defs>\n</svg>\n\n<table class=\"vf-table";
if(runtime.contextOrFrameLookup(context, frame, "table_variant")) {
output += " ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "table_variant"), env.opts.autoescape);
;
}
if(runtime.contextOrFrameLookup(context, frame, "striped")) {
output += " vf-table--striped";
;
}
if(runtime.contextOrFrameLookup(context, frame, "sortable")) {
output += " vf-table--sortable";
;
}
if(runtime.contextOrFrameLookup(context, frame, "bordered")) {
output += " vf-table--bordered";
;
}
if(runtime.contextOrFrameLookup(context, frame, "tableLayoutFixed")) {
output += " vf-table--fixed";
;
}
output += "\">\n\n";
if(runtime.contextOrFrameLookup(context, frame, "table_caption")) {
output += "  <caption class=\"vf-table__caption\">";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "table_caption"), env.opts.autoescape);
output += "</caption>\n";
;
}
output += "\n  <thead class=\"vf-table__header\">\n";
if(runtime.contextOrFrameLookup(context, frame, "table_headings") == null) {
output += "    please add headings to table\n";
;
}
output += "    <tr class=\"vf-table__row\">";
if(runtime.contextOrFrameLookup(context, frame, "selectable")) {
output += "<th class=\"vf-table__heading\" scope=\"col\">\n";
var t_1;
t_1 = runtime.contextOrFrameLookup(context, frame, "count") + 1 + env.getFilter("random").call(context, (lineno = 33, colno = 49, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "range"), "range", context, [1,1000])));
frame.set("header_count", t_1, true);
if(frame.topLevel) {
context.setVariable("header_count", t_1);
}
if(frame.topLevel) {
context.addExport("header_count", t_1);
}
output += "          <input type=\"checkbox\" id=\"select-all-";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "header_count"), env.opts.autoescape);
output += "\" class=\"vf-form__checkbox | vf-table__checkbox\">\n          <label for=\"select-all-";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "header_count"), env.opts.autoescape);
output += "\" class=\"vf-form__label vf-table__label\">\n            <span class=\"vf-u-sr-only\">Select all rows</span>\n          </label>\n        </th>";
;
}
frame = frame.push();
var t_4 = runtime.contextOrFrameLookup(context, frame, "table_headings");
if(t_4) {t_4 = runtime.fromIterator(t_4);
var t_3 = t_4.length;
for(var t_2=0; t_2 < t_4.length; t_2++) {
var t_5 = t_4[t_2];
frame.set("cell", t_5);
frame.set("loop.index", t_2 + 1);
frame.set("loop.index0", t_2);
frame.set("loop.revindex", t_3 - t_2);
frame.set("loop.revindex0", t_3 - t_2 - 1);
frame.set("loop.first", t_2 === 0);
frame.set("loop.last", t_2 === t_3 - 1);
frame.set("loop.length", t_3);
output += "        <th class=\"vf-table__heading\" scope=\"col\"\n";
if(runtime.memberLookup((t_5),"colspans")) {
output += " colspan=\"";
output += runtime.suppressValue(runtime.memberLookup((t_5),"colspans"), env.opts.autoescape);
output += "\"";
;
}
output += "      >";
if(runtime.contextOrFrameLookup(context, frame, "sortable")) {
output += "<button class=\"vf-button vf-button--sm vf-button--icon vf-table__button vf-table__button--sortable\">\n          ";
output += runtime.suppressValue(runtime.memberLookup((t_5),"title"), env.opts.autoescape);
output += "\n          <svg width=\"12\" height=\"22\" xmlns=\"http://www.w3.org/2000/svg\">\n            ";
output += "\n            <path id=\"vf-table--sortable-top-arrow\"d=\"M6 0l6 10H0z\"/>\n            <path id=\"vf-table--sortable-bottom-arrow\" d=\"M6 22L0 12h12z\"/>\n          </svg>\n        </button>\n";
;
}
else {
output += "        ";
output += runtime.suppressValue(runtime.memberLookup((t_5),"title"), env.opts.autoescape);
output += "\n";
;
}
output += "      </th>\n";
;
}
}
frame = frame.pop();
if(runtime.contextOrFrameLookup(context, frame, "inline_actions")) {
output += "<th class=\"vf-table__heading vf-table__heading--actions\">\n          Actions\n        </th>";
;
}
output += "</tr>\n  </thead>\n\n  <tbody class=\"vf-table__body\">";
frame = frame.push();
var t_8 = runtime.contextOrFrameLookup(context, frame, "table_rows");
if(t_8) {t_8 = runtime.fromIterator(t_8);
var t_7 = t_8.length;
for(var t_6=0; t_6 < t_8.length; t_6++) {
var t_9 = t_8[t_6];
frame.set("row", t_9);
frame.set("loop.index", t_6 + 1);
frame.set("loop.index0", t_6);
frame.set("loop.revindex", t_7 - t_6);
frame.set("loop.revindex0", t_7 - t_6 - 1);
frame.set("loop.first", t_6 === 0);
frame.set("loop.last", t_6 === t_7 - 1);
frame.set("loop.length", t_7);
if(t_9) {
output += "    <tr class=\"vf-table__row\n";
if(runtime.contextOrFrameLookup(context, frame, "selected")) {
frame = frame.push();
var t_12 = t_9;
if(t_12) {t_12 = runtime.fromIterator(t_12);
var t_11 = t_12.length;
for(var t_10=0; t_10 < t_12.length; t_10++) {
var t_13 = t_12[t_10];
frame.set("cell", t_13);
frame.set("loop.index", t_10 + 1);
frame.set("loop.index0", t_10);
frame.set("loop.revindex", t_11 - t_10);
frame.set("loop.revindex0", t_11 - t_10 - 1);
frame.set("loop.first", t_10 === 0);
frame.set("loop.last", t_10 === t_11 - 1);
frame.set("loop.length", t_11);
if(runtime.memberLookup((t_13),"selected_row")) {
output += " vf-table__row--selected";
;
}
;
}
}
frame = frame.pop();
;
}
output += "\">";
if(runtime.contextOrFrameLookup(context, frame, "selectable")) {
output += "<th class=\"vf-table__cell vf-table__cell--selectable\" scope=\"row\">\n";
var t_14;
t_14 = runtime.contextOrFrameLookup(context, frame, "count") + 1 + env.getFilter("random").call(context, (lineno = 80, colno = 44, runtime.callWrap(runtime.contextOrFrameLookup(context, frame, "range"), "range", context, [1,1000])));
frame.set("count", t_14, true);
if(frame.topLevel) {
context.setVariable("count", t_14);
}
if(frame.topLevel) {
context.addExport("count", t_14);
}
output += "            <input type=\"checkbox\" id=\"checkbox-";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "count"), env.opts.autoescape);
output += "\" class=\"vf-form__checkbox | vf-table__checkbox\"\n";
if(runtime.contextOrFrameLookup(context, frame, "selected")) {
frame = frame.push();
var t_17 = t_9;
if(t_17) {t_17 = runtime.fromIterator(t_17);
var t_16 = t_17.length;
for(var t_15=0; t_15 < t_17.length; t_15++) {
var t_18 = t_17[t_15];
frame.set("cell", t_18);
frame.set("loop.index", t_15 + 1);
frame.set("loop.index0", t_15);
frame.set("loop.revindex", t_16 - t_15);
frame.set("loop.revindex0", t_16 - t_15 - 1);
frame.set("loop.first", t_15 === 0);
frame.set("loop.last", t_15 === t_16 - 1);
frame.set("loop.length", t_16);
if(runtime.memberLookup((t_18),"selected_row")) {
output += " checked";
;
}
;
}
}
frame = frame.pop();
;
}
output += ">\n            <label for=\"checkbox-";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "count"), env.opts.autoescape);
output += "\" class=\"vf-form__label vf-table__label\">\n              <span class=\"vf-u-sr-only\">Form Label</span>\n            </label>\n        </th>";
;
}
frame = frame.push();
var t_21 = t_9;
if(t_21) {t_21 = runtime.fromIterator(t_21);
var t_20 = t_21.length;
for(var t_19=0; t_19 < t_21.length; t_19++) {
var t_22 = t_21[t_19];
frame.set("cell", t_22);
frame.set("loop.index", t_19 + 1);
frame.set("loop.index0", t_19);
frame.set("loop.revindex", t_20 - t_19);
frame.set("loop.revindex0", t_20 - t_19 - 1);
frame.set("loop.first", t_19 === 0);
frame.set("loop.last", t_19 === t_20 - 1);
frame.set("loop.length", t_20);
if(runtime.memberLookup((t_22),"selected_row")) {
;
}
else {
if(runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "loop")),"first") && runtime.contextOrFrameLookup(context, frame, "firstCellIsHeader") == true) {
output += "            <th class=\"vf-table__cell | vf-table__heading\" scope=\"row\"\n";
if(runtime.memberLookup((t_22),"colspans")) {
output += " colspan=\"";
output += runtime.suppressValue(runtime.memberLookup((t_22),"colspans"), env.opts.autoescape);
output += "\"";
;
}
if(runtime.memberLookup((t_22),"rowspans")) {
output += " rowspan=\"";
output += runtime.suppressValue(runtime.memberLookup((t_22),"rowspans"), env.opts.autoescape);
output += "\"";
;
}
output += "            >";
output += runtime.suppressValue(runtime.memberLookup((t_22),"text"), env.opts.autoescape);
output += "</th>";
;
}
else {
output += "<td class=\"vf-table__cell\"\n";
if(runtime.memberLookup((t_22),"colspans")) {
output += " colspan=\"";
output += runtime.suppressValue(runtime.memberLookup((t_22),"colspans"), env.opts.autoescape);
output += "\"";
;
}
if(runtime.memberLookup((t_22),"rowspans")) {
output += " rowspan=\"";
output += runtime.suppressValue(runtime.memberLookup((t_22),"rowspans"), env.opts.autoescape);
output += "\"";
;
}
output += "            >";
output += runtime.suppressValue(runtime.memberLookup((t_22),"text"), env.opts.autoescape);
output += "</td>\n";
;
}
;
}
;
}
}
frame = frame.pop();
if(runtime.contextOrFrameLookup(context, frame, "inline_actions")) {
output += "<td class=\"vf-table__cell vf-table__cell--actions\">";
frame = frame.push();
var t_25 = runtime.contextOrFrameLookup(context, frame, "inline_actions");
if(t_25) {t_25 = runtime.fromIterator(t_25);
var t_24 = t_25.length;
for(var t_23=0; t_23 < t_25.length; t_23++) {
var t_26 = t_25[t_23];
frame.set("action", t_26);
frame.set("loop.index", t_23 + 1);
frame.set("loop.index0", t_23);
frame.set("loop.revindex", t_24 - t_23);
frame.set("loop.revindex0", t_24 - t_23 - 1);
frame.set("loop.first", t_23 === 0);
frame.set("loop.last", t_23 === t_24 - 1);
frame.set("loop.length", t_24);
output += "          <button class=\"vf-button vf-button--sm vf-button--icon\">";
output += runtime.suppressValue(t_26, env.opts.autoescape);
output += "</button>";
;
}
}
frame = frame.pop();
output += "</td>";
;
}
output += "</tr>\n";
;
}
;
}
}
frame = frame.pop();
output += "  </tbody>";
if(runtime.contextOrFrameLookup(context, frame, "table_footer")) {
output += "  <tfoot class=\"vf-table__footer\">\n    <tr class=\"vf-table__row\">\n";
frame = frame.push();
var t_29 = runtime.contextOrFrameLookup(context, frame, "table_footer");
if(t_29) {t_29 = runtime.fromIterator(t_29);
var t_28 = t_29.length;
for(var t_27=0; t_27 < t_29.length; t_27++) {
var t_30 = t_29[t_27];
frame.set("cell", t_30);
frame.set("loop.index", t_27 + 1);
frame.set("loop.index0", t_27);
frame.set("loop.revindex", t_28 - t_27);
frame.set("loop.revindex0", t_28 - t_27 - 1);
frame.set("loop.first", t_27 === 0);
frame.set("loop.last", t_27 === t_28 - 1);
frame.set("loop.length", t_28);
output += "      <td class=\"vf-table__cell\"\n";
if(runtime.memberLookup((t_30),"colspans")) {
output += " colspan=\"";
output += runtime.suppressValue(runtime.memberLookup((t_30),"colspans"), env.opts.autoescape);
output += "\"";
;
}
if(runtime.memberLookup((t_30),"rowspans")) {
output += " rowspan=\"";
output += runtime.suppressValue(runtime.memberLookup((t_30),"rowspans"), env.opts.autoescape);
output += "\"";
;
}
output += "      >\n        ";
output += runtime.suppressValue(runtime.memberLookup((t_30),"text"), env.opts.autoescape);
output += "\n      </td>\n";
;
}
}
frame = frame.pop();
output += "    </tr>\n  </tfoot>";
;
}
output += "</table>\n\n";
if(runtime.contextOrFrameLookup(context, frame, "selected")) {
output += "<div class=\"vf-table__actions\">";
frame = frame.push();
var t_33 = runtime.contextOrFrameLookup(context, frame, "actions");
if(t_33) {t_33 = runtime.fromIterator(t_33);
var t_32 = t_33.length;
for(var t_31=0; t_31 < t_33.length; t_31++) {
var t_34 = t_33[t_31];
frame.set("action", t_34);
frame.set("loop.index", t_31 + 1);
frame.set("loop.index0", t_31);
frame.set("loop.revindex", t_32 - t_31);
frame.set("loop.revindex0", t_32 - t_31 - 1);
frame.set("loop.first", t_31 === 0);
frame.set("loop.last", t_31 === t_32 - 1);
frame.set("loop.length", t_32);
output += "  <button class=\"vf-button vf-button--sm vf-button--icon\">";
output += runtime.suppressValue(t_34, env.opts.autoescape);
output += "</button>";
;
}
}
frame = frame.pop();
output += "</div>\n";
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
