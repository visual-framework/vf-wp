/**
 * Precompiled Nunjucks template: vf-pagination--compact.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-pagination--compact"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<p class=\"vf-text--body vf-text-body--3\">\n  The most simple of pagers, best used for non-complex needs, such as blogs.\n<p>\n\n\n<nav class=\"vf-pagination\" aria-label=\"Pagination\">\n  <ul class=\"vf-pagination__list\">\n    <li class=\"vf-pagination__item vf-pagination__item--previous-page\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        Previous<span class=\"vf-sr-only\"> page</span>\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--next-page\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        Next<span class=\"vf-sr-only\"> page</span>\n      </a>\n    </li>\n  </ul>\n</nav>\n";
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
