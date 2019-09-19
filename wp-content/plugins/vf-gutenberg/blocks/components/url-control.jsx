/**
 * URLControl (component)
 * Wrapper for `URLInput`
 */
import React from 'react';

const {__} = wp.i18n;
const {URLInput} = wp.editor;
const {BaseControl} = wp.components;

const URLControl = props => {
  return (
    <BaseControl label={props.label}>
      <URLInput
        value={props.value}
        onChange={props.onChange}
        autoFocus={false}
      />
    </BaseControl>
  );
};

export default URLControl;
