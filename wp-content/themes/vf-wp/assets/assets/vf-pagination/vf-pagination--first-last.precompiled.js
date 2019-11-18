/**
 * Precompiled Nunjucks template: vf-pagination--first-last.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-pagination--first-last"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<p class=\"vf-text--body vf-text-body--3\">\n  Use this if there are many pages of results where users may go deep into the\n  result set, and want to know the total depth of results.\n</p>\n\n<nav class=\"vf-pagination\" aria-label=\"Pagination\">\n  <ul class=\"vf-pagination__list\">\n    <li class=\"vf-pagination__item vf-pagination__item--jump-back\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        First<span class=\"vf-sr-only\"> page</span> (1)\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--previous-page\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        Previous<span class=\"vf-sr-only\"> page</span> (5)\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--is-active\">\n      <span class=\"vf-pagination__label\" aria-current=\"page\">\n        <span class=\"vf-sr-only\">Page </span>6\n      </span>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--next-page\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        Next<span class=\"vf-sr-only\"> page</span> (7)\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--jump-forward\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        Last<span class=\"vf-sr-only\"> page</span> (88)\n      </a>\n    </li>\n  </ul>\n</nav>\n";
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
