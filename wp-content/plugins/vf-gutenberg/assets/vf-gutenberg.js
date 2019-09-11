/**
 * WARNING: deprecated
 * This code has been replaced with `vf-blocks` to manage
 * native Gutenberg blocks for VF-WP plugins
 */
(function() {
  window.vfGutenbergIFrame = function(iframe, html, js) {
    var body = iframe.contentWindow.document.body;
    body.innerHTML = html;

    // append iframeResizer content window script
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = js;
    script.onload = function() {
      script.onload = null;
      setTimeout(function() {
        iframe.iFrameResizer.resize();
      }, 100);
    };
    body.appendChild(script);

    // start iframeResizer
    window.iFrameResize({log: false, checkOrigin: false}, iframe);
  };
})();
