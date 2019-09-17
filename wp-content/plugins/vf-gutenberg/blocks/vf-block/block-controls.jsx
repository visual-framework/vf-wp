/**
 * VFBlockControls
 * Toolbar to toggle between "edit" and "view" modes.
 */
import React from 'react';

const {__} = wp.i18n;
const {BlockControls} = wp.editor;
const {Button, IconButton, Toolbar} = wp.components;

// The togglable "Edit" button added to `BlockControls`
const EditButton = ({onClick}) => (
  <IconButton label={__('Edit')} icon="edit" onClick={onClick} />
);

// The togglable "View" button added to `BlockControls`
const ViewButton = ({onClick}) => (
  <IconButton label={__('Preview')} icon="visibility" onClick={onClick} />
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
