import React, {useState, useEffect} from 'react';

/**
 * Hook to use global VF Gutenberg settings from `wp_localize_script`
 */
const useVF = () => {
  const vf = window.vfGutenberg || {};
  if (!vf.hasOwnProperty('postId')) {
    vf.postId = 0;
  }
  if (!vf.hasOwnProperty('nonce')) {
    vf.nonce = '';
  }
  return vf;
};

/**
 * Hook to fetch the VF Gutenberg block rendered template
 */
const useVFBlock = attr => {
  const {postId, nonce} = useVF();
  const [data, setData] = useState({});
  const [isLoading, setLoading] = useState(true);

  const fetchData = async () => {
    setLoading(true);
    try {
      const data = await wp.ajax.post('vf_gutenberg_fetch_block', {
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
const useIFrameResize = (iframeEl, html, config) => {
  config = {
    log: false,
    checkOrigin: false,
    ...(config || {})
  };
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
      setTimeout(function() {
        iframe.iFrameResizer.resize();
      }, 100);
    };
    body.appendChild(script);
    window.iFrameResize(config, iframe);
  };
  return {onLoad};
};

export {useVF, useVFBlock, useIFrameResize};
