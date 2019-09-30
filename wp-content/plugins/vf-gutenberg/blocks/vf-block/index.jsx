/**
 * VFBlock (component)
 * Provide a base component for VF Gutenberg blocks.
 * Component has "edit" and "view" modes.
 */
import React, {Fragment} from 'react';
import {Spinner} from '@wordpress/components';
import {withInstanceId} from '@wordpress/compose';
import {__} from '@wordpress/i18n';
import {useUniqueId} from '../hooks';
import useVFPluginRender from '../hooks/use-vf-plugin-render';
import useVFTemplateRender from '../hooks/use-vf-template-render';
import VFBlockControls from './block-controls';
import VFBlockView from './block-view';
import VFBlockEdit from './block-edit';

const VFBlock = props => {
  const uniqueId = useUniqueId([props.clientId, props.instanceId]);

  // extract attributes
  const {
    attributes: {ver, mode, ...attrs}
  } = props;

  // ensure version is encoded in post content
  if (!ver) {
    props.setAttributes({ver: props.ver || 1});
  }

  // No `props.clientId` if rendered in the sidebar style preview
  const isEditable = props.clientId && typeof mode === 'string';
  const isEditing = isEditable && mode === 'edit';

  // Hook in conditional against the rules?
  const data = (() => {
    /**
     * Include transient properties in the attributes passed to the render
     * function that will not be saved to the block JSON.
     */
    const renderAttrs = {...attrs, ...(props.transient || {})};
    // render using Nunjucks template
    if ('render' in attrs) {
      const render = useVFTemplateRender(props.name, renderAttrs);
      if (!render) {
        return null;
      }
      let html = attrs.render;
      // update attribute for main block only and not style previews
      if (props.clientId) {
        props.setAttributes({render: render.html});
      } else {
        html = render.html;
      }
      return {html};
    }
    // render using server-side plugin
    return isEditing ? null : useVFPluginRender(props.name, renderAttrs);
  })();

  const isLoading = data === null;
  const isPreview = !isLoading && data;

  const onToggle = () => {
    props.setAttributes({mode: !isEditing ? 'edit' : 'view'});
  };

  const rootAttr = {
    className: `vf-block ${props.className}`,
    'data-ver': ver,
    'data-name': props.name,
    'data-editing': isEditing,
    'data-loading': isLoading,
    'data-selected': props.isSelected
  };

  return (
    <Fragment>
      {isEditable && <VFBlockControls {...{isEditing, onToggle}} />}
      <div {...rootAttr}>
        {isEditing ? (
          <VFBlockEdit
            onToggle={onToggle}
            children={props.children}
            hasFooter={props.hasFooter}
          />
        ) : isPreview ? (
          <VFBlockView html={data.html} uniqueId={uniqueId} />
        ) : (
          <Spinner />
        )}
      </div>
    </Fragment>
  );
};

// Wrap with HoC
export default withInstanceId(VFBlock);
