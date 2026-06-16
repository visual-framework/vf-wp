/**
 * Return `onLoad` and `onUnload` functions for an iframe.
 * Adjust iframe height automatically whilst mounted.
 */
const useVFIFrame = (iframe, iframeHTML, onHeight) => {
  // TOTO: handle by global in `vf-blocks.jsx` - is onHeight necessary?
  // update iframe height from `postMessage` event
  const onMessage = ({data}) => {
    if (data !== Object(data) || data.id !== iframe.id) {
      return;
    }
    const targetWindow = iframe.ownerDocument.defaultView || window;
    targetWindow.requestAnimationFrame(() => {
      // TODO: now handled by global
      // iframe.style.height = `${data.height}px`;
      onHeight(data.height);
    });
  };

  const onLoad = () => {
    const targetWindow = iframe.ownerDocument.defaultView || window;
    if (!iframe.vfActive) {
      targetWindow.addEventListener('message', onMessage);
    }
    iframe.vfActive = true;

    // set HTML content for block
    const body = iframe.contentWindow.document.body;
    if (iframeHTML) {
      body.innerHTML = iframeHTML;
    }

    // create and append script to handle automatic iframe resize
    // this cannot be inline of `html` for browser security
    const script = iframe.contentWindow.document.createElement('script');
    script.type = 'text/javascript';
    script.innerHTML = `
if (ResizeObserver) {
  const observer = new ResizeObserver(entries => {
    entries.forEach(entry => {
      window.parent.postMessage({
          id: '${iframe.id}',
          height: entry.contentRect.height
        }, '*'
      );
    });
  });
  observer.observe(document.body);
} else {
  const vfResize = () => {
    window.parent.postMessage({
        id: '${iframe.id}',
        height: document.documentElement.scrollHeight
      }, '*'
    );
  };
  window.addEventListener('resize', vfResize);
  setTimeout(vfResize, 100);
  vfResize();
}
    `;
    body.appendChild(script);
  };

  // cleanup function for dismount
  const onUnload = () => {
    const targetWindow = iframe.ownerDocument.defaultView || window;
    targetWindow.removeEventListener('message', onMessage);
    iframe.vfActive = false;
  };

  return {onLoad, onUnload};
};

export default useVFIFrame;
