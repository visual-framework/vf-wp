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
    label: __('Number of Columns')
  };
  if (props.isInspector) {
    control.help = __('Content may be reorganised when columns are reduced.');
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
              isLarge
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
