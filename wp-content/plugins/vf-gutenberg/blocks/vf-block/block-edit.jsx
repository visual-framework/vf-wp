/**
 * BlockEdit (component)
 * In "edit" mode render the editing controls. This component is sparse
 * because blocks will provide their own children, or use `VFBlockFields`
 * to automatically generate controls.
 */
import React from 'react';

const {__} = wp.i18n;
const {Button} = wp.components;

// "Update" button optionally appended to `VFBlockEdit` footer
const UpdateButton = ({onClick}) => (
  <Button isDefault isLarge onClick={onClick}>
    {__('Update')}
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
