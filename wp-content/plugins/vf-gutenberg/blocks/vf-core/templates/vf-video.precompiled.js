/**
 * Precompiled Nunjucks template: vf-video.njk

<div class="vf-video">
    <iframe width="{{ width }}" height="{{ height }}" src="{{ src }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
</div>

 */
(function() {
  (window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})[
    'vf-video'
  ] = (function() {
    function root(env, context, frame, runtime, cb) {
      var lineno = 0;
      var colno = 0;
      var output = '';
      try {
        var parentTemplate = null;
        output += '<div class="vf-video">\n    <iframe width="';
        output += runtime.suppressValue(
          runtime.contextOrFrameLookup(context, frame, 'width'),
          env.opts.autoescape
        );
        output += '" height="';
        output += runtime.suppressValue(
          runtime.contextOrFrameLookup(context, frame, 'height'),
          env.opts.autoescape
        );
        output += '" src="';
        output += runtime.suppressValue(
          runtime.contextOrFrameLookup(context, frame, 'src'),
          env.opts.autoescape
        );
        output +=
          '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>\n</div>';
        if (parentTemplate) {
          parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
        } else {
          cb(null, output);
        }
      } catch (e) {
        cb(runtime.handleError(e, lineno, colno));
      }
    }
    return {
      root: root
    };
  })();
})();
