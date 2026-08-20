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
import {addAction, removeAction} from '@wordpress/hooks';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';

const defaults = useVFDefaults();

const Edit = (props) => {
  const [acfId] = useState(acf.uniqid('block_'));
  const [isFetching, setFetching] = useState(true);
  const [isLoading, setLoading] = useState(true);
  const [previewHeight, setPreviewHeight] = useState(0);
  const [render, setRender] = useState('');
  const [script, setScript] = useState(null);
  const ref = useRef(null);
  const messageWindowRef = useRef(null);
  const latestRequestRef = useRef(0);

  const {clientId} = props;

  const onMessage = useCallback(
    (ev) => {
      if (ev.data !== Object(ev.data)) {
        return;
      }
      const {id} = ev.data;
      if (id && id.includes(acfId)) {
        const height = Number(ev.data.height);
        if (height && isFinite(height)) {
          setPreviewHeight(Math.ceil(height));
        }
        const targetWindow = ev.currentTarget || window;
        clearTimeout(targetWindow[`${id}_onMessage`]);
        targetWindow[`${id}_onMessage`] = targetWindow.setTimeout(() => {
          setLoading(false);
        }, 100);
      }
    },
    [clientId]
  );

  useEffect(() => {
    setLoading(true);
    setFetching(true);
    const targetWindow = ref.current?.ownerDocument?.defaultView || window;
    if (messageWindowRef.current) {
      messageWindowRef.current.removeEventListener('message', onMessage);
    }
    targetWindow.removeEventListener('message', onMessage);
    targetWindow.addEventListener('message', onMessage);
    messageWindowRef.current = targetWindow;
    const requestId = latestRequestRef.current + 1;
    latestRequestRef.current = requestId;

    const fetch = async () => {
      const fields = {is_plugin: 1, ...props.transient.fields};
      try {
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
        if (latestRequestRef.current !== requestId) {
          return;
        }
        if (response && response.preview) {
          const html = response.preview.split(/<script[^>]*?>/)[0];
          const script = response.preview.match(
            /<script[^>]*?>(.*)<\/script>/ms
          );
          setScript(Array.isArray(script) ? script[1] : null);
          setRender(html);
          setFetching(false);
        } else {
          setLoading(false);
        }
      } catch (err) {
        if (latestRequestRef.current !== requestId) {
          return;
        }
        setLoading(false);
      }
    };
    fetch();

    return () => {
      targetWindow.removeEventListener('message', onMessage);
    };
  }, [clientId, props.attributes.ref, props.transient.acfUpdate]);

  useEffect(() => {
    if (isFetching) {
      return;
    }
    ref.current.innerHTML = render;
    const targetWindow = ref.current?.ownerDocument?.defaultView || window;
    if (script) {
      const el = ref.current.ownerDocument.createElement('script');
      el.type = 'text/javascript';
      el.innerHTML = script;
      ref.current.appendChild(el);
      targetWindow.setTimeout(() => setLoading(false), 1500);
    } else {
      setLoading(false);
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
  if (previewHeight) {
    rootAttrs.style.minHeight = `${previewHeight}px`;
  }

  const viewStyle = {};
  if (isLoading) {
    viewStyle.visibility = 'hidden';
  }
  if (previewHeight) {
    viewStyle.minHeight = `${previewHeight}px`;
  }

  return (
    <div {...rootAttrs}>
      {isLoading && <Spinner />}
      <div ref={ref} style={viewStyle} className="vf-block__view" />
    </div>
  );
};

export const withACFUpdates = (Edit) => {
  return (props) => {
    const {clientId} = props;
    const fieldsRef = useRef({});
    const updateTimeoutRef = useRef(null);
    const [previewState, setPreviewState] = useState({
      fields: {},
      acfUpdate: 0
    });

    useEffect(() => {
      const namespace = `vf_plugin/${clientId}`;
      addAction('vf_plugin_acf_update', namespace, (data) => {
        if (data.fields && data.fields === Object(data.fields)) {
          fieldsRef.current = {
            ...fieldsRef.current,
            ...data.fields
          };
        } else {
          fieldsRef.current = {
            ...fieldsRef.current,
            [data.name]: data.value
          };
        }

        clearTimeout(updateTimeoutRef.current);
        updateTimeoutRef.current = setTimeout(() => {
          setPreviewState((state) => ({
            fields: {...fieldsRef.current},
            acfUpdate: state.acfUpdate + 1
          }));
        }, 150);
      });

      return () => {
        clearTimeout(updateTimeoutRef.current);
        removeAction('vf_plugin_acf_update', namespace);
      };
    }, [clientId]);

    return Edit({
      ...props,
      transient: {
        ...(props.transient || {}),
        fields: previewState.fields,
        acfUpdate: previewState.acfUpdate
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
