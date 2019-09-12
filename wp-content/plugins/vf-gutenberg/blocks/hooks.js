import React, {useState, useEffect} from 'react';
import hashsum from './utils/hashsum';

const {__} = wp.i18n;

/**
 * Hook to return default VF Gutenberg block settings
 */
export const useDefaults = () => ({
  keywords: [__('VF', 'vfwp'), __('Visual Framework', 'vfwp')],
  attributes: {
    ver: {
      type: 'integer'
    }
  },
  supports: {
    align: false,
    className: false,
    customClassName: false,
    html: false
  },
  edit: () => null,
  save: () => null
});

/**
 * Hook to use global VF Gutenberg settings from `wp_localize_script`
 */
const vfGutenberg = {
  postId: 0,
  nonce: '',
  plugins: {}
};

export const useVFGutenberg = () => {
  const vf = window.vfGutenberg || {};
  for (let [key, value] of Object.entries(vfGutenberg)) {
    if (!vf.hasOwnProperty(key)) {
      vf[key] = value;
    }
  }
  return vf;
};

/**
 * Hook to fetch the VF Gutenberg block rendered template
 */
export const useVFPluginData = attrs => {
  const [data, setData] = useState(null);
  const [isLoading, setLoading] = useState(false);

  const fetchData = async () => {
    const {postId, nonce} = useVFGutenberg();
    setLoading(true);
    try {
      const data = await wp.ajax.post('vf/gutenberg/fetch_block', {
        ...attrs,
        postId,
        nonce
      });
      setData(data);
      setLoading(false);
    } catch (err) {}
  };

  useEffect(() => {
    fetchData();
  }, [hashsum(attrs)]);

  return [data, isLoading];
};

/**
 * Hook to get block attributes for VF Plugin
 * mapped from ACF field object
 */
export const useVFPluginFields = name => {
  const {plugins} = useVFGutenberg();
  let fields = [];
  let attrs = {};
  if (Object.keys(plugins).indexOf(name) > -1) {
    const config = plugins[name];
    if (config.hasOwnProperty('fields')) {
      fields = config.fields;
      fields.forEach(field => {
        const {name} = field;
        attrs[name] = {type: 'string'};
        if (name === 'range') {
          attrs[name]['type'] = 'integer';
        }
        if (field['type'] === 'checkbox') {
          attrs[name]['type'] = 'array';
        }
        if (field['type'] === 'toggle') {
          attrs[name]['type'] = 'integer';
        }
      });
    }
  }
  return {fields, attrs};
};

/**
 * Hook to provide load/unload functions for an iframe
 * and adjust iframe height automatically
 */
export const useIFrame = (iframe, html) => {
  // update iframe height from `postMessage` event
  const onMessage = ({data}) => {
    if (data !== Object(data) || data.id !== iframe.id) {
      return;
    }
    window.requestAnimationFrame(() => {
      iframe.style.height = `${data.height}px`;
    });
  };

  const onLoad = () => {
    if (!iframe.vfActive) {
      window.addEventListener('message', onMessage);
    }
    iframe.vfActive = true;

    // set HTML content for block
    const body = iframe.contentWindow.document.body;
    const div = '<div style="clear:both;height:0;"></div>';
    body.innerHTML = `${div}${html}${div}`;

    // create and append script to handle automatic iframe resize
    // this cannot be inline of `html` for browser security
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.innerHTML = `
      window.vfResize = function() {
        window.parent.postMessage({
            id: '${iframe.id}',
            height: document.documentElement.scrollHeight
          }, '*'
        );
      };
      window.addEventListener('resize', window.vfResize);
      setTimeout(window.vfResize, 1);
    `;
    body.appendChild(script);
  };

  const onUnload = () => {
    window.removeEventListener('message', onMessage);
    iframe.vfActive = false;
  };

  return {onLoad, onUnload};
};

// const withId = Component => {
//   return props => {
//     const id = Date.now() * Math.random();
//     return <Component {...props} instanceId={id} />;
//   };
// };
