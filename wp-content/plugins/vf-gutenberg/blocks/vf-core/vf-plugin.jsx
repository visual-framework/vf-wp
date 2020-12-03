/**
Block Name: Plugin
Notes:
  * This is not actually a VF component
  * It's named `vf/plugin` to avoid breaking existing usage
  * VF_Block and VF_Container plugins have default content, e.g.:

  <!-- wp:vf/plugin {"ref":"vf_masthead"} /-->

  */
import React, {Fragment, useCallback, useEffect, useRef, useState} from 'react';
import {Spinner} from '@wordpress/components';
import {addAction, hasAction} from '@wordpress/hooks';
import {__} from '@wordpress/i18n';
import {useHashsum} from '../hooks';
import useVFDefaults from '../hooks/use-vf-defaults';

const defaults = useVFDefaults();

const renderStore = {};

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

    const fetch = async () => {
      let render;
      const fields = {is_plugin: 1, ...props.transient.fields};
      const renderHash = useHashsum(fields);
      if (renderStore.hasOwnProperty(renderHash)) {
        render = await new Promise((resolve) =>
          setTimeout(() => {
            resolve(renderStore[renderHash]);
          }, 1)
        );
      } else {
        const response = await wp.ajax.post('acf/ajax/fetch-block', {
          query: {
            preview: true
          },
          nonce: acf.get('nonce'),
          post_id: acf.get('post_id'),
          block: JSON.stringify({
            id: acfId,
            name: props.attributes.ref,
            data: fields,
            align: '',
            mode: 'preview'
          })
        });
        if (response && response.preview) {
          render = response.preview;
          renderStore[renderHash] = render;
        }
      }
      if (render) {
        const html = render.split(/<script[^>]*?>/)[0];
        const script = render.match(/<script[^>]*?>(.*)<\/script>/ms);
        setScript(Array.isArray(script) ? script[1] : null);
        setRender(html);
        setFetching(false);
      }
    };
    fetch();
  }, [clientId, props.attributes.__acfUpdate]);

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
    'data-ver': props.attributes.ver,
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

export const withACFUpdates = (Edit) => {
  const transient = {fields: {}};
  return (props) => {
    const {clientId} = props;
    useEffect(() => {
      if (hasAction('vf_plugin_acf_update', 'vf_plugin')) {
        return;
      }
      addAction('vf_plugin_acf_update', 'vf_plugin', (data) => {
        transient.fields[data.name] = data.value;
        props.setAttributes({__acfUpdate: Date.now()});
      });
    }, [clientId]);
    return Edit({
      ...props,
      transient: {
        ...(props.transient || {}),
        ...transient
      }
    });
  };
};

export default {
  ...defaults,
  name: 'vf/plugin',
  title: __('Preview'),
  category: 'vf/wp',
  description: '',
  attributes: {
    ...defaults.attributes,
    ref: {
      type: 'string'
    }
  },
  supports: {
    ...defaults.supports,
    inserter: false,
    reusable: false
  },
  edit: withACFUpdates(Edit),
  save: () => null
};
