import React, {useState, useEffect} from 'react';
import hashsum from './utils/hashsum';
import VFBlock from './vf-block';
import VFBlockFields from './vf-block/block-fields';

const {__} = wp.i18n;

/**
 * Return unique hash of IDs array
 */
export const useUniqueId = ids => hashsum(ids);

/**
 * Generate a random ID for class or ID attributes
 */
export const useRandomId = seed =>
  hashsum(seed + Math.floor(Math.random() * Date.now()));

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
 * Hook to get block attributes for VF Plugin
 * mapped from ACF field object
 */
export const useVFPlugin = name => {
  const {plugins} = useVFGutenberg();
  let fields = [];
  let attrs = {};
  if (Object.keys(plugins).indexOf(name) > -1) {
    const config = plugins[name];
    if (config.hasOwnProperty('fields')) {
      fields = config.fields;
      fields.forEach(field => {
        const {name, type} = field;
        attrs[name] = {type: 'string'};
        if (type === 'range') {
          attrs[name]['type'] = 'integer';
        }
        if (type === 'checkbox') {
          attrs[name]['type'] = 'array';
        }
        if (type === 'toggle') {
          attrs[name]['type'] = 'integer';
        }
      });
    }
  }
  return {fields, attrs};
};

/**
 * Hook to return default VF Gutenberg block settings
 */
export const useDefaults = () => ({
  keywords: [__('VF'), __('Visual Framework')],
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
 * Generate new Gutenberg block settings from defaults.
 * Provide `VFBlockFields` using `useVFPlugin` configuration.
 */
export const useVFPluginSettings = (name, title) => {
  const defaults = useDefaults();
  const {fields, attrs} = useVFPlugin(name);
  const hasFields = Array.isArray(fields) && fields.length > 0;

  // Setup block attributes from the VF Plugin with defaults
  const attributes = {
    ...attrs,
    ...defaults.attributes
  };

  // Only enable "edit" mode when fields exist
  if (hasFields) {
    attributes.mode = {
      type: 'string',
      default: 'view'
    };
  }

  // Return the Gutenberg settings
  return {
    ...defaults,
    name: name,
    title: title,
    category: 'vf/wp',
    keywords: [...defaults.keywords, __('Content Hub')],
    attributes: attributes,
    edit: props => {
      return (
        <VFBlock {...props} hasFooter={hasFields}>
          {hasFields && <VFBlockFields {...props} fields={fields} />}
        </VFBlock>
      );
    }
  };
};

/**
 * Hook to fetch the VF Gutenberg block rendered template
 */
export const useVFRender = attrs => {
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
        requestAnimationFrame(function() {
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

  const onUnload = () => {
    window.removeEventListener('message', onMessage);
    iframe.vfActive = false;
  };

  return {onLoad, onUnload};
};
