/**
 * Precompiled Nunjucks template: embl-content-meta-properties.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["embl-content-meta-properties"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "<!-- Content descriptors -->\n<meta name=\"embl:who\" content=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta_who"), env.opts.autoescape);
output += "\"> <!-- the people, groups and teams involved -->\n<meta name=\"embl:where\" content=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta_where"), env.opts.autoescape);
output += "\"> <!-- at which EMBL sites the content applies -->\n<meta name=\"embl:what\" content=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta_what"), env.opts.autoescape);
output += "\"> <!-- the activities covered -->\n<meta name=\"embl:active\" content=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta_active"), env.opts.autoescape);
output += "\"> <!-- which of the who/what/where is active -->\n\n<!-- Content role -->\n<meta name=\"embl:utility\" content=\"-8\"> <!-- if content is task and work based or if is meant to inspire -->\n<meta name=\"embl:reach\" content=\"-5\"> <!-- if content is externally (public) or internally focused (those that work at EMBL) -->\n\n<!-- Page infromation -->\n<meta name=\"embl:maintainer\" content=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta_maintainer"), env.opts.autoescape);
output += "\"> <!-- the contact person or group responsible for the page -->\n<meta name=\"embl:last-review\" content=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta") - runtime.contextOrFrameLookup(context, frame, "last_review"), env.opts.autoescape);
output += "\"> <!-- the last time the page was reviewed or updated -->\n<meta name=\"embl:review-cycle\" content=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta_review_cycle"), env.opts.autoescape);
output += "\"> <!-- how long in days before the page should be checked -->\n<meta name=\"embl:expiry\" content=\"";
output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "meta_expiry"), env.opts.autoescape);
output += "\"> <!-- if there is a fixed point in time when the page is no longer relevant -->\n";
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
