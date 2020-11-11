import React, {Fragment, useCallback, useEffect, useRef, useState} from 'react';
import {Spinner} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';

const defaults = useVFDefaults();

const Edit = (props) => {
  const [acfId] = useState(acf.uniqid('block_'));
  const [isFetching, setFetching] = useState(true);
  const [isLoading, setLoading] = useState(true);
  const [render, setRender] = useState('');
  const [script, setScript] = useState(null);
  const ref = useRef(null);

  const {clientId} = props;

  const onMessage = useCallback(
    (ev) => {
      const {id} = ev.data;
      if (id && id.includes(acfId)) {
        clearTimeout(window[`${id}_onMessage`]);
        window[`${id}_onMessage`] = setTimeout(() => {
          window.removeEventListener('message', onMessage);
          setLoading(false);
        }, 100);
      }
    },
    [clientId]
  );

  useEffect(() => {
    setLoading(true);
    setFetching(true);
    window.removeEventListener('message', onMessage);
    window.addEventListener('message', onMessage);
    let name = props.attributes.ref.replaceAll('_', '-');
    name = name.replace('vf-', 'vf-container-');
    const fetch = async () => {
      const response = await wp.ajax.post('acf/ajax/fetch-block', {
        query: {
          preview: true
        },
        nonce: acf.get('nonce'),
        post_id: acf.get('post_id'),
        block: JSON.stringify({
          id: acfId,
          name: `acf/${name}`,
          data: {defaults: '1'},
          align: '',
          mode: 'preview'
        })
      });
      if (response && response.preview) {
        const html = response.preview.split(/<script[^>]*?>/)[0];
        const script = response.preview.match(/<script[^>]*?>(.*)<\/script>/ms);
        setScript(Array.isArray(script) ? script[1] : null);
        setRender(html);
        setFetching(false);
      }
    };
    fetch();
  }, [clientId]);

  useEffect(() => {
    if (isFetching) {
      return;
    }
    ref.current.innerHTML = render;
    if (script) {
      const el = document.createElement('script');
      el.type = 'text/javascript';
      el.innerHTML = script;
      ref.current.appendChild(el);
    }
  }, [isFetching]);

  // add DOM attributes for styling
  const rootAttrs = {
    className: `vf-block ${props.className}`,
    'data-name': props.name,
    'data-editing': false,
    'data-loading': isLoading,
    style: {}
  };

  if (isLoading) {
    rootAttrs.style.minHeight = '100px';
  }

  const viewStyle = {};
  if (isLoading) {
    viewStyle.visibility = 'hidden';
  }

  return (
    <div {...rootAttrs}>
      {isLoading && <Spinner />}
      <div ref={ref} style={viewStyle} className="vf-block__view" />
    </div>
  );
};

export default {
  ...defaults,
  name: 'vf/plugin',
  title: __('Preview'),
  category: 'vf/wp',
  description: '',
  attributes: {
    ref: {
      type: 'string'
    }
  },
  supports: {
    ...defaults.supports,
    inserter: false,
    reusable: false
  },
  edit: Edit,
  save: () => null
};
