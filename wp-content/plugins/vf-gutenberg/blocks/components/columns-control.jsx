/**
 * Columns (component)
 * Wrapper for `ButtonGroup` to select number of columns
 */
import React, {Fragment} from 'react';
import {__} from '@wordpress/i18n';
import {BaseControl, Button, ButtonGroup} from '@wordpress/components';

const ColumnsControl = props => {
  const {value, min, max, onChange} = props;
  const control = {
    label: __('Number of Columns'),
    className: 'components-vf-control'
  };
  if (props.help) {
    control.help = props.help;
  }
  const isPressed = i => i + min === value;
  return (
    <BaseControl {...control}>
      <ButtonGroup aria-label={control.label}>
        {Array(max - min + 1)
          .fill()
          .map((x, i) => (
            <Button
              key={i}
              isPrimary={isPressed(i)}
              aria-pressed={isPressed(i)}
              onClick={() => onChange(i + min)}>
              {i + min}
            </Button>
          ))}
      </ButtonGroup>
    </BaseControl>
  );
};

export default ColumnsControl;
