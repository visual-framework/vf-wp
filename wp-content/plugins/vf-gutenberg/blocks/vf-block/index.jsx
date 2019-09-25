/**
 * VFBlock (component)
 * Provide a base component for VF Gutenberg blocks.
 * Component has "edit" and "view" modes.
 */
import React from 'react';
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
  const {
    clientId,
    instanceId,
    isSelected,
    hasFooter,
    attributes: {ver, mode, ...attrs}
  } = props;

  const uniqueId = useUniqueId([clientId, instanceId]);

  // Ensure version is encoded in post content
  if (!ver) {
    props.setAttributes({ver: props.ver || 1});
  }

  // No `clientId` if rendered in the sidebar style preview
  const hasMode = clientId && typeof mode === 'string';
  const isEditing = hasMode && mode === 'edit';

  // Hook in conditional against the rules?
  const data = (() => {
    if ('render' in attrs) {
      const render = useVFTemplateRender(props.name, attrs);
      if (render) {
        props.setAttributes({render: render.html});
      }
      return {html: attrs.render};
    }
    if (isEditing) {
      return null;
    }
    return useVFPluginRender(props.name, attrs);
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
    'data-selected': isSelected
  };

  return (
    <>
      {hasMode && <VFBlockControls {...{isEditing, onToggle}} />}
      <div {...rootAttr}>
        {isEditing ? (
          <VFBlockEdit {...{hasFooter, onToggle}} children={props.children} />
        ) : isPreview ? (
          <VFBlockView html={data.html} uniqueId={uniqueId} />
        ) : (
          <Spinner />
        )}
      </div>
    </>
  );
};

// Wrap with HoC
export default withInstanceId(VFBlock);
