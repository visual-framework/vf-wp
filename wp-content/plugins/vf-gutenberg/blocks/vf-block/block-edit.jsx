/**
 * BlockEdit (component)
 * In "edit" mode render the editing controls. This component is sparse
 * because blocks will provide their own children, or use `VFBlockFields`
 * to automatically generate controls.
 */
import React from 'react';
import {Button} from '@wordpress/components';
import {__} from '@wordpress/i18n';

// "Update" button optionally appended to `VFBlockEdit` footer
const UpdateButton = ({onClick}) => (
  <Button isDefault isLarge onClick={onClick}>
    {__('Preview')}
  </Button>
);

const VFBlockEdit = props => {
  const {hasFooter, onToggle} = props;
  return (
    <div className="vf-block__edit">
      {props.children}
      {hasFooter && <UpdateButton onClick={onToggle} />}
    </div>
  );
};

export default VFBlockEdit;
