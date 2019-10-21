/**
 * Checkboxes (component)
 * Wrapper for multiple `CheckboxControl`
 */
import React from 'react';
import {BaseControl, CheckboxControl} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import {useHashsum} from '../hooks';

const CheckboxesControl = props => {
  const {attrs, field, label, name, onChange} = props;
  return (
    // Markup similar to `RadioControl` with multiple options
    <BaseControl label={label} className="components-radio-control">
      {field.options.map(option => (
        <div
          key={useHashsum(option)}
          className="components-radio-control__option">
          <CheckboxControl
            label={option.label}
            checked={(attrs[name] || []).includes(option.value)}
            onChange={checked => {
              // Remove checkbox value from attribute array
              const attr = (attrs[name] || []).filter(v => v !== option.value);
              // Re-append value if checked
              if (checked) {
                attr.push(option.value);
              }
              onChange(name, attr);
            }}
          />
        </div>
      ))}
    </BaseControl>
  );
};

export default CheckboxesControl;
