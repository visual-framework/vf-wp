/**
 * BlockView (component)
 * In "view" mode; fetch a block template from the VF-WP plugin and render
 * within an iframe (to scope CSS and JavaScript).
 */
import React, {useEffect, useRef} from 'react';
import {SandBox} from '@wordpress/components';
import useVFGutenberg from '../hooks/use-vf-gutenberg';
import useVFIFrame from '../hooks/use-vf-iframe';

const VFBlockView = props => {
  const {html, uniqueId, isExample} = props;
  const {renderPrefix, renderSuffix} = useVFGutenberg();

  // `SandBox` component does not update height properly...
  /*
  return (
    <div className="vf-block__view">
      <SandBox
        html={`${renderPrefix}${html}${renderSuffix}`}
        type="vf-block-frame"
        title="Preview"
      />
    </div>
  );
  */

  // Use an asynchronous hook to fetch the iframe content via WordPress API
  const {iframe, onLoad, onUnload} = useVFIFrame(
    `vfwp_${uniqueId}`,
    `${renderPrefix}${html}${renderSuffix}`,
    isExample
  );

  const rootEl = useRef();

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
