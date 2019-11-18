/**
 * Precompiled Nunjucks template: vf-pagination--expanded.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-pagination--expanded"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<p class=\"vf-text--body vf-text-body--3\">\n  For when there are many pages and a need to filter past unwanted sections,\n  such as long alphabetical lists.\n</p>\n\n<nav class=\"vf-pagination\" aria-label=\"Pagination\">\n  <ul class=\"vf-pagination__list\">\n    <li class=\"vf-pagination__item vf-pagination__item--jump-back\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        First <span class=\"vf-sr-only\"> page</span>\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <span class=\"vf-pagination__label\">\n        ...\n      </span>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>6\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>7\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--is-active\">\n      <span class=\"vf-pagination__label\" aria-current=\"page\">\n        <span class=\"vf-sr-only\">Page </span>8\n      </span>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>9\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>10\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <span class=\"vf-pagination__label\">\n        ...\n      </span>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--jump-forward\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        Last <span class=\"vf-sr-only\"> page</span>\n      </a>\n    </li>\n  </ul>\n</nav>\n";
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
