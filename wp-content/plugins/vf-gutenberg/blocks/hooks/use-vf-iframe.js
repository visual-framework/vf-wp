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
    window.requestAnimationFrame(() => {
      // TODO: now handled by global
      // iframe.style.height = `${data.height}px`;
      onHeight(data.height);
    });
  };

  const onLoad = () => {
    if (!iframe.vfActive) {
      window.addEventListener('message', onMessage);
    }
    iframe.vfActive = true;

    // set HTML content for block
    const body = iframe.contentWindow.document.body;
    if (iframeHTML) {
      body.innerHTML = iframeHTML;
    }

    // create and append script to handle automatic iframe resize
    // this cannot be inline of `html` for browser security
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.innerHTML = `
      window.vfResize = () => {
        requestAnimationFrame(() => {
          window.parent.postMessage({
              id: '${iframe.id}',
              height: document.documentElement.scrollHeight
            }, '*'
          );
        });
      };
      window.addEventListener('resize', vfResize);
      window.vfResize();
    `;
    body.appendChild(script);
  };

  // cleanup function for dismount
  const onUnload = () => {
    window.removeEventListener('message', onMessage);
    iframe.vfActive = false;
  };

  return {onLoad, onUnload};
};

export default useVFIFrame;
