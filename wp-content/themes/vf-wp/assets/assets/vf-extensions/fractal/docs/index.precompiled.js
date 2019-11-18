/**
 * Precompiled Nunjucks template: index.njk
 */
(function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["index"] = (function() {
function root(env, context, frame, runtime, cb) {
var lineno = 0;
var colno = 0;
var output = "";
try {
var parentTemplate = null;
output += "---\ntitle: Component documentation\norder: 100\nisIndex: true\n---\n\nThis is an index of all the Visual Framework components available to this 11ty installation.\n\n---\n\nIf you'd like to add any component-specific documentation, you can do so here.\n\nFor tips and documentation on how to do so, [see the Fractal guide](https://fractal.build/guide/documentation/).\n";
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
