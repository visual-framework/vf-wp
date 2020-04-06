/**
 * VFBlockControls (component)
 * Toolbar to toggle between "edit" and "view" modes.
 */
import React from 'react';
import {BlockControls} from '@wordpress/block-editor';
import {Button, Toolbar} from '@wordpress/components';
import {__} from '@wordpress/i18n';

// The togglable "Edit" button added to `BlockControls`
const EditButton = ({onClick}) => (
  <Button label={__('Edit')} icon="edit" onClick={onClick} />
);

// The togglable "View" button added to `BlockControls`
const ViewButton = ({onClick}) => (
  <Button label={__('Preview')} icon="visibility" onClick={onClick} />
);

const VFBlockControls = props => {
  const {isEditing, onToggle} = props;
  return (
    <BlockControls>
      <Toolbar>
        {isEditing ? (
          <ViewButton onClick={onToggle} />
        ) : (
          <EditButton onClick={onToggle} />
        )}
      </Toolbar>
    </BlockControls>
  );
};

export default VFBlockControls;
