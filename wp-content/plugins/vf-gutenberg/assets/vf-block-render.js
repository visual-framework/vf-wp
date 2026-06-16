(function () {
  var $preview;

  function postHeight(height) {
    height = Math.ceil(height);
    if (height < 1) {
      return;
    }

    window.parent.postMessage(
      {
        id: $preview.id,
        height: height
      },
      '*'
    );
  }

  function getHeight() {
    const rect = $preview.getBoundingClientRect();
    return Math.max(
      rect.height,
      $preview.scrollHeight,
      document.body ? document.body.scrollHeight : 0,
      document.documentElement ? document.documentElement.scrollHeight : 0
    );
  }

  function postCurrentHeight() {
    postHeight(getHeight());
  }

  function postCurrentHeightSoon(delay) {
    window.setTimeout(postCurrentHeight, delay);
  }

  function onReady() {
    if ('ResizeObserver' in window) {
      function onResize(entries) {
        postCurrentHeight();
      }
      var observer = new window.ResizeObserver(onResize);
      observer.observe($preview);
      if (document.body) {
        observer.observe(document.body);
      }
    } else {
      window.addEventListener('resize', postCurrentHeight);
    }

    Array.prototype.forEach.call(
      $preview.querySelectorAll('img'),
      function bindImage(img) {
        if (!img.complete) {
          img.addEventListener('load', postCurrentHeight);
          img.addEventListener('error', postCurrentHeight);
        } else {
          postCurrentHeightSoon(0);
        }
      }
    );

    postCurrentHeight();
    postCurrentHeightSoon(50);
    postCurrentHeightSoon(100);
    postCurrentHeightSoon(500);
    postCurrentHeightSoon(1000);
    postCurrentHeightSoon(2000);
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(postCurrentHeight);
    }
    window.addEventListener('load', postCurrentHeight);
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
