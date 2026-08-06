/**
 * Precompiled Nunjucks template: vf-pagination--full.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-pagination--full"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<p class=\"vf-text--body vf-text-body--3\">\n  Generally reserved for deep and enganging databses of infromation,\n  ulitmately you should try to avoid needing such a complex pager and\n  mitigate through search and faceting filters.\n</p>\n\n<nav class=\"vf-pagination\" aria-label=\"Pagination\">\n  <ul class=\"vf-pagination__list\">\n    <li class=\"vf-pagination__item vf-pagination__item--previous-page\">\n      <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "previous_url"), env.opts.autoescape);
output += "\" class=\"vf-pagination__link\">\n        ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "previous_text"), env.opts.autoescape);
output += "<span class=\"vf-sr-only\"> page</span>\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--jump-back\">\n      <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "jumpBack_url"), env.opts.autoescape);
output += "\" class=\"vf-pagination__link\">\n        ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "jumpBack_text"), env.opts.autoescape);
output += "<span class=\"vf-sr-only\"> page</span>\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>1\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>2\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <span class=\"vf-pagination__label\">\n        ...\n      </span>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>17\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--is-active\">\n      <span class=\"vf-pagination__label\" aria-current=\"page\">\n        <span class=\"vf-sr-only\">Page </span>18\n      </span>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>19\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <span class=\"vf-pagination__label\">\n        ...\n      </span>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>91\n      </a>\n    </li>\n    <li class=\"vf-pagination__item\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        <span class=\"vf-sr-only\">Page </span>92\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--jump-forward\">\n      <a href=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "jumpForward_url"), env.opts.autoescape);
output += "\" class=\"vf-pagination__link\">\n        ";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "jumpForward_text"), env.opts.autoescape);
output += "<span class=\"vf-sr-only\"> pages</span>\n      </a>\n    </li>\n    <li class=\"vf-pagination__item vf-pagination__item--next-page\">\n      <a href=\"#\" class=\"vf-pagination__link\">\n        Next<span class=\"vf-sr-only\"> page</span>\n      </a>\n    </li>\n\n    <li class=\"vf-pagination__item vf-pagination__item--pages-per\">\n\n      <div class=\"vf-form__item--inline\">\n        <label class=\"vf-form__label\" for=\"vf-form__select\">Results per page</label>\n\n        <select class=\"vf-form__select\" id=\"vf-form__select\">\n          <option value=\"10\" selected>10</option>\n          <option value=\"20\">20</option>\n          <option value=\"30\">30</option>\n          <option value=\"40\">40</option>\n          <option value=\"50\">50</option>\n        </select>\n      </div>\n    </li>\n  </ul>\n</nav>\n";
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
