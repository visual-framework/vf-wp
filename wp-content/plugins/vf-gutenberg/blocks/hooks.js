import React, {useState, useEffect} from 'react';

/**
 * Hook to use global VF Gutenberg settings from `wp_localize_script`
 */
const useVF = () => {
  const vf = window.vfBlocks || {};
  if (!vf.hasOwnProperty('postId')) {
    vf.postId = 0;
  }
  if (!vf.hasOwnProperty('nonce')) {
    vf.nonce = '';
  }
  if (!vf.hasOwnProperty('plugins')) {
    vf.plugins = [];
  }
  return vf;
};

/**
 * Hook to fetch the VF Gutenberg block rendered template
 */
const useVFPlugin = attr => {
  const {postId, nonce} = useVF();
  const [data, setData] = useState({});
  const [isLoading, setLoading] = useState(true);

  const fetchData = async () => {
    setLoading(true);
    try {
      const data = await wp.ajax.post('vf/gutenberg/fetch_block', {
        ...attr,
        postId,
        nonce
      });
      setData(data);
      setLoading(false);
    } catch (err) {}
  };

  useEffect(() => {
    fetchData();
  }, [...Object.values(attr)]);

  return {data, isLoading};
};

/**
 * Hook to append iFrameResizer to content window
 */
const useIFrame = (iframeEl, html) => {
  const {iframeResizer} = useVF();
  const onLoad = () => {
    const iframe = iframeEl.current;
    const body = iframe.contentWindow.document.body;
    body.innerHTML = html;
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = iframeResizer;
    script.onload = function() {
      script.onload = null;
      iframe.iFrameResizer.resize();
      setInterval(function() {
        iframe.iFrameResizer.resize();
      }, 500);
    };
    body.appendChild(script);
    window.iFrameResize(
      {
        log: false,
        checkOrigin: false
      },
      iframe
    );
  };
  return {onLoad};
};

// const withId = Component => {
//   return props => {
//     const id = Date.now() * Math.random();
//     return <Component {...props} instanceId={id} />;
//   };
// };

export {useVF, useVFPlugin, useIFrame};
