/**
 * Return `onLoad` and `onUnload` functions for an iframe.
 * Adjust iframe height automatically whilst mounted.
 */

import React, {useState} from 'react';

const useVFIFrame = (iframeId, html, hasWidth) => {
  const [height, setHeight] = useState(30);
  const [width, setWidth] = useState(100);

  // Use existing iframe appended to the DOM
  let iframe = document.getElementById(iframeId);

  // or create a new iframe element
  if (!iframe) {
    iframe = document.createElement('iframe');
    iframe.id = iframeId;
    iframe.className = 'vf-block__iframe';
    iframe.setAttribute('scrolling', 'no');
  }

  iframe.style.height = `${height}px`;
  if (hasWidth) {
    iframe.style.width = `${width}px`;
  }

  // update iframe height from `postMessage` event
  const onMessage = ({data}) => {
    if (data !== Object(data) || data.id !== iframe.id) {
      return;
    }
    window.requestAnimationFrame(() => {
      setHeight(data.height);
      if (hasWidth) {
        setWidth(data.width);
      }
      // iframe.style.height = `${data.height}px`;
      // if (hasWidth) {
      //   iframe.style.width = `${data.width}px`;
      // }
    });
  };

  const onLoad = () => {
    if (!iframe.vfActive) {
      window.addEventListener('message', onMessage);
    }
    iframe.vfActive = true;

    // set HTML content for block
    const body = iframe.contentWindow.document.body;
    body.innerHTML = `<!DOCTYPE html>
<html lang="en-US" class="vf-no-js">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
${html}
</body>
</html>`;

    if (hasWidth) {
      body.style.width = 'max-content';
    }

    // create and append script to handle automatic iframe resize
    // this cannot be inline of `html` for browser security
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.innerHTML = `
      window.vfResize = function() {
        requestAnimationFrame(function() {
          var clientBoundingRect = document.body.getBoundingClientRect();
          window.parent.postMessage({
              id: '${iframe.id}',
              height: document.documentElement.scrollHeight,
              width: clientBoundingRect.width
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

  return {iframe, onLoad, onUnload};
};

export default useVFIFrame;
