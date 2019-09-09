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

  // Bail early if Gutenberg editior is not available
  if (typeof wp !== 'object' || typeof wp.domReady !== 'function') {
    return;
  }

  /*
  wp.domReady(function() {
    var blocks = wp.blocks.getBlockTypes();

    const blockList = [
      'core/button',
      'core/file',
      'core/heading',
      'core/image',
      'core/list',
      'core/paragraph',
      'core/quote',
      'core/separator',
      'core/table'
    ];

    wp.blocks.getBlockTypes().forEach(function(blockType) {
      // Allow all custom non-core blocks
      if (blockType.name.indexOf('core') !== 0) {
        return;
      }
      // Allow all core embed blocks
      if (blockType.name.indexOf('core-embed') === 0) {
        return;
      }
      // Unregister based on whitelist
      if (blockList.indexOf(blockType.name) === -1) {
        wp.blocks.unregisterBlockType(blockType.name);
      }
    });

    // Remove block style variants
    wp.blocks.unregisterBlockStyle('core/quote', 'large');
  });

  wp.hooks.addFilter(
    'blocks.registerBlockType',
    'vf/registerBlockType',
    function(settings, name) {
      // Move all core blocks into "common" or "embed" category
      if (name.indexOf('core') === 0) {
        if (settings.category !== 'embed') {
          settings.category = 'common';
        }
      }
      // Remove seperator style variants
      if (name === 'core/separator') {
        delete settings.styles;
      }
      // Remove button align support
      if (name === 'core/button') {
        delete settings.attributes.align;
        settings.supports.align = false;
      }
      return settings;
    }
  );
  */
})();
