/**
 * DateControl (component)
 * Wrapper for `DateControl`
 */
import React from 'react';
import {BaseControl, DatePicker} from '@wordpress/components';

const DateControl = props => {
  return (
    <BaseControl label={props.label}>
      <DatePicker currentDate={props.currentDate} onChange={props.onChange} />
    </BaseControl>
  );
};

export default DateControl;
