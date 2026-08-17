/**
 * Precompiled Nunjucks template: embl-conditional-edit.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["embl-conditional-edit"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<div class=\"vf-box vf-box-vf-box--secondary vf-box--outline\" data-embl-js-conditional-edit>\n  <p class=\"vf-text vf-text-body--3\">Sample content with a condtional edit link beneatht it.</p>\n  <p class=\"vf-text vf-text-body--3\">This is done through JavaScript where a parent element has <code>data-embl-js-conditional-edit</code> and <code>?embl-conditional-edit=enabled</code> is appended to the URL.</p>\n  <p class=\"vf-text vf-text-body--3\"><a class=\"vf-link embl-conditional-edit\" href=\"#edit\" target=\"_blank\">Edit</a></p>\n</div>\n\n<div class=\"embl-coditional-edit__enabled | vf-box vf-box-vf-box--secondary vf-box--outline\">\n  <p class=\"vf-text vf-text-body--3\">This section is a non-JS solution where a parent element has `.embl-coditional-edit__enabled`.</p>\n  <p class=\"vf-text vf-text-body--3\"><a class=\"vf-link embl-conditional-edit\" href=\"#edit\" target=\"_blank\">Edit</a></p>\n</div>\n";
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
