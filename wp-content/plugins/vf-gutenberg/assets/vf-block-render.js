(function () {
  var $preview;

  function postHeight(height) {
    window.parent.postMessage(
      {
        id: $preview.id,
        height: height
      },
      '*'
    );
  }

  function onReady() {
    if ('ResizeObserver' in window) {
      function onResize(entries) {
        entries.forEach(function resize(entry) {
          const newHeight = entry.contentRect.height;
          if (newHeight > 0) {
            postHeight(newHeight);
          }
        });
      }
      var observer = new window.ResizeObserver(onResize);
      observer.observe($preview);
    } else {
      function onResize() {
        const newHeight = $preview.getBoundingClientRect().height;
        if (newHeight > 0) {
          postHeight(newHeight);
        }
      }
      window.addEventListener('resize', onResize);
      window.setTimeout(onResize, 100);
      onResize();
    }
  }

  // var now = Date.now();
  function onFrame() {
    // if (Date.now() - now > 5000) {
    //   return;
    // }
    $preview = document.querySelector('.vf-block-render');
    if ($preview) {
      onReady();
    } else {
      window.requestAnimationFrame(onFrame);
    }
  }
  onFrame();
})();
