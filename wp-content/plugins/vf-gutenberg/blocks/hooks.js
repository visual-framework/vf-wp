import React, {useState, useEffect} from 'react';
import hashsum from './hashsum';

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
  if (!vf.hasOwnProperty('plugins') || !vf.plugins) {
    vf.plugins = {};
  }
  return vf;
};

/**
 * Hook to fetch the VF Gutenberg block rendered template
 */
const useVFPlugin = postData => {
  const {postId, nonce} = useVF();
  const [data, setData] = useState({});
  const [isLoading, setLoading] = useState(true);

  const fetchData = async () => {
    setLoading(true);
    try {
      const data = await wp.ajax.post('vf/gutenberg/fetch_block', {
        ...postData,
        postId,
        nonce
      });
      setData(data);
      setLoading(false);
    } catch (err) {}
  };

  useEffect(() => {
    fetchData();
  }, [hashsum(postData)]);

  return {data, isLoading};
};

/**
 * Hook to get block attributes for VF Plugin
 * mapped from ACF field object
 */
const useVFPluginFields = name => {
  const {plugins} = useVF();
  let fields = [];
  let attrs = {};
  if (Object.keys(plugins).indexOf(name) > -1) {
    const config = plugins[name];
    if (config.hasOwnProperty('fields')) {
      fields = config.fields;
      fields.forEach(field => {
        attrs[field['name']] = {type: 'string'};
        if (field['type'] === 'range') {
          attrs[field['name']]['type'] = 'integer';
        }
      });
    }
  }
  return {fields, attrs};
};

/**
 * Hook to append iFrameResizer to content window
 */
const useIFrame = (iframe, data) => {
  const {iframeResizer} = useVF();
  const onLoad = () => {
    const onMessage = ev => {
      if (ev.data !== iframe.id) {
        return;
      }
      window.removeEventListener('message', onMessage);
      if (iframe.iFrameResizer) {
        return;
      }
      window.iFrameResize(
        {
          log: false,
          checkOrigin: false
        },
        iframe
      );
      setTimeout(() => {
        iframe.iFrameResizer.resize();
      }, 1);
      setTimeout(() => {
        iframe.iFrameResizer.resize();
      }, 500);
    };

    window.addEventListener('message', onMessage);

    const body = iframe.contentWindow.document.body;
    body.innerHTML = data.html;
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = iframeResizer;
    script.onload = function() {
      parent.postMessage(iframe.id, '*');
      script.onload = null;
    };
    body.appendChild(script);
  };
  return {onLoad};
};

// const withId = Component => {
//   return props => {
//     const id = Date.now() * Math.random();
//     return <Component {...props} instanceId={id} />;
//   };
// };

export {useVF, useVFPlugin, useVFPluginFields, useIFrame};
