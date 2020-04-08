/**
 * BlockView (component)
 * In "view" mode; fetch a block template from the VF-WP plugin and render
 * within an iframe (to scope CSS and JavaScript).
 */
import React, {useEffect, useRef} from 'react';
import {SandBox} from '@wordpress/components';
import useVFGutenberg from '../hooks/use-vf-gutenberg';
import useVFIFrame from '../hooks/use-vf-iframe';

const VFBlockView = ({render, uniqueId, onHeight}) => {
  const {renderPrefix, renderSuffix} = useVFGutenberg();

  // Use existing iframe appended to the DOM
  const iframeId = `vfwp_${uniqueId}`;
  const iframeHTML = render.html
    ? `${renderPrefix}${render.html}${renderSuffix}`
    : '';
  let iframe = document.getElementById(iframeId);

  // or create a new iframe element
  if (!iframe) {
    iframe = document.createElement('iframe');
    iframe.id = iframeId;
    iframe.className = 'vf-block__iframe';
    iframe.setAttribute('scrolling', 'no');
    if (render.src) {
      iframe.src = render.src;
    }
  }

  // Use an asynchronous hook to fetch the iframe content via WordPress API
  const rootEl = useRef();
  const {onLoad, onUnload} = useVFIFrame(iframe, iframeHTML, onHeight);

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
