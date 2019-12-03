/**
 * VFBlock (component)
 * Base component for VF Gutenberg blocks.
 * The component supports "edit" and "view" modes.
 * See `useVFPluginSettings` and `useVFCoreSettings` for usage.
 */
import React, {Fragment, useState} from 'react';
import {Spinner} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import {useUniqueId} from '../hooks';
import useVFRender from '../hooks/use-vf-render';
import VFBlockControls from './block-controls';
import VFBlockEdit from './block-edit';
import VFBlockView from './block-view';

const VFBlock = props => {
  const {isEditing, isEditable, isPlugin, isRenderable, isSelected} = props;
  const uniqueId = useUniqueId(props);
  const [render, isLoading] = useVFRender(props);
  const [minHeight, setMinHeight] = useState(100);

  // ensure version is encoded in post content
  if (!props.attributes.ver) {
    const newAttr = {ver: props.ver || '1.0.0'};
    // use defaults for VF plugin blocks
    if (isPlugin) {
      newAttr.defaults = 1;
    }
    props.setAttributes(newAttr);
  }

  // callback to toggle block mode
  const onToggle = () => {
    props.setAttributes({mode: isEditing ? 'view' : 'edit'});
  };

  // show block controls if both modes exist
  const hasControls = isEditable && isRenderable;

  // show "edit" mode when edit state is active
  const hasEdit = isEditable && isEditing;

  // show "view" mode when not editing and render is available
  const hasView = !hasEdit && (!isLoading && render);

  // height change callback
  const onHeight = height => height !== minHeight && setMinHeight(height);
  if (hasEdit) {
    onHeight(100);
  }

  // add DOM attributes for styling
  const rootAttrs = {
    className: `vf-block ${props.className}`,
    'data-ver': props.attributes.ver,
    'data-name': props.name,
    'data-editing': isEditing,
    'data-loading': isLoading,
    'data-selected': isSelected,
    style: {
      minHeight: `${minHeight}px`
    }
  };

  return (
    <Fragment>
      {hasControls && <VFBlockControls {...{isEditing, onToggle}} />}
      <div {...rootAttrs}>
        {hasEdit && (
          <VFBlockEdit
            onToggle={isRenderable ? onToggle : null}
            children={props.children}
          />
        )}
        {hasView && (
          <VFBlockView
            html={render.html}
            uniqueId={uniqueId}
            onHeight={onHeight}
          />
        )}
        {isLoading && <Spinner />}
      </div>
    </Fragment>
  );
};

export default VFBlock;
