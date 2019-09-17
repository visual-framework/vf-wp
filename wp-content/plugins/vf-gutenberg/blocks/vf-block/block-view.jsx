/**
 * BlockView (component)
 * In "view" mode; fetch a block template from the VF-WP plugin and render
 * within an iframe (to scope CSS and JavaScript).
 */
import React, {useEffect, useRef} from 'react';
import {useIFrame} from '../hooks';

const VFBlockView = ({html, uniqueId}) => {
  // Use existing iframe appended to the DOM
  const iframeId = `vfwp_${uniqueId}`;
  let iframe = document.getElementById(iframeId);

  // or create a new iframe element
  if (!iframe) {
    iframe = document.createElement('iframe');
    iframe.id = iframeId;
    iframe.className = 'vf-block__iframe';
    iframe.setAttribute('scrolling', 'no');
  }

  // Use an asynchronous hook to fetch the iframe content via WordPress API
  const rootEl = useRef();
  const {onLoad, onUnload} = useIFrame(iframe, html);

  // Append the iframe element on mount - we cannot use `<iframe onLoad={} />`
  // in React, this does not fire properly in Webkit browsers for
  // iframe elements when `src` is empty
  useEffect(() => {
    iframe.addEventListener('load', ev => onLoad(ev));
    rootEl.current.appendChild(iframe);

    // Cleanup after dismount
    return () => {
      onUnload();
    };
  });

  return <div className="vf-block__view" ref={rootEl} />;
};

// Memoize to avoid unnecessary heavy updates
export default React.memo(VFBlockView);
