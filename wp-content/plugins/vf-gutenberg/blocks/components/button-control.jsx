/**
 * ButtonControl (component)
 * Wrapper for `Button`
 */
import React from 'react';
import {BaseControl, Button} from '@wordpress/components';

const ButtonControl = (props) => {
  const {help, label, ...buttonProps} = props.field;
  return (
    <BaseControl help={help}>
      <div className='components-base-control__button'>
        <Button {...buttonProps}>{label}</Button>
      </div>
    </BaseControl>
  );
};

export default ButtonControl;
