/**
 * ButtonControl (component)
 * Wrapper for `Button`
 */
import React from 'react';
import {BaseControl, Button} from '@wordpress/components';

const ButtonControl = (props) => {
  return (
    <BaseControl>
      <div className='components-base-control__button'>
        <Button {...props.field}>{props.label}</Button>
      </div>
    </BaseControl>
  );
};

export default ButtonControl;
