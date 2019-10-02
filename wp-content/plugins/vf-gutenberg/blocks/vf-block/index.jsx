/**
 * VFBlock (component)
 * Provide a base component for VF Gutenberg blocks.
 * Component has "edit" and "view" modes.
 */
import React, {Fragment, useState} from 'react';
import {Spinner} from '@wordpress/components';
import {__} from '@wordpress/i18n';
// import {withInstanceId} from '@wordpress/compose';
import {useUniqueId, useHashsum} from '../hooks';
import useVFRender from '../hooks/use-vf-render';
import VFBlockControls from './block-controls';
import VFBlockView from './block-view';
import VFBlockEdit from './block-edit';

const VFBlock = props => {
  const uniqueId = useUniqueId(props);

  // ensure version is encoded in post content
  if (!props.attributes.ver) {
    props.setAttributes({ver: props.ver || 1});
  }

  // No `props.clientId` if rendered in the sidebar style preview
  const isEditable =
    props.clientId && typeof props.attributes.mode === 'string';
  const isEditing = isEditable && props.attributes.mode === 'edit';
  const isRenderable = props.hasRender !== false;

  const render = useVFRender(props, {isEditing});

  const isLoading = isRenderable && render === null;
  const isPreview = !isLoading && render;

  const onToggle = () => {
    props.setAttributes({mode: !isEditing ? 'edit' : 'view'});
  };

  const rootAttr = {
    className: `vf-block ${props.className}`,
    'data-ver': props.attributes.ver,
    'data-name': props.name,
    'data-editing': isEditing,
    'data-loading': isLoading,
    'data-selected': props.isSelected
  };

  return (
    <Fragment>
      {isEditable && isRenderable && (
        <VFBlockControls {...{isEditing, onToggle}} />
      )}
      <div {...rootAttr}>
        {isEditing ? (
          <VFBlockEdit
            onToggle={props.hasFooter ? onToggle : null}
            children={props.children}
          />
        ) : isPreview ? (
          <VFBlockView html={render.html} uniqueId={uniqueId} />
        ) : (
          <Spinner />
        )}
      </div>
    </Fragment>
  );
};

export default VFBlock;
