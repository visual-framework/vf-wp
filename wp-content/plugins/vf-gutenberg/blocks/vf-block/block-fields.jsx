/**
 * VFBlockFields (component)
 * Iterate the `fields` property and return an array of WordPress field
 * controls that update their corresponding attributes. The `fields` array
 * is mapped from ACF configuration.
 */
import React from 'react';

const {
  BaseControl,
  CheckboxControl,
  RadioControl,
  RangeControl,
  SelectControl,
  TextControl,
  TextareaControl,
  ToggleControl
} = wp.components;

const VFBlockFields = props => {
  const {attributes: attrs, setAttributes, fields} = props;

  // Generic event handler to update an attribute
  const onChange = (name, value) => {
    const attr = {};
    attr[name] = value;
    setAttributes({...attr});
  };

  // Map fields and return array of controls
  return fields.map(field => {
    const {name, type, label} = field;

    // The ACF "checkbox" field returns an array of one or more checked
    // values whereas "true_false" (here "toggle") uses a boolean value
    if (type === 'checkbox') {
      return (
        // Markup similar to `RadioControl` with multiple options
        <BaseControl label={label} className="components-radio-control">
          {field.options.map(option => (
            <div className="components-radio-control__option">
              <CheckboxControl
                label={option.label}
                checked={(attrs[name] || []).includes(option.value)}
                onChange={checked => {
                  // Remove checkbox value from attribute array
                  const attr = (attrs[name] || []).filter(
                    v => v !== option.value
                  );
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
    }
    if (type === 'radio') {
      return (
        <RadioControl
          label={label}
          selected={attrs[name]}
          onChange={value => onChange(name, value)}
          options={[...field.options]}
        />
      );
    }
    if (type === 'range') {
      return (
        <RangeControl
          label={label}
          value={parseInt(attrs[name])}
          onChange={value => onChange(name, value)}
          min={parseInt(field['min'])}
          max={parseInt(field['max'])}
        />
      );
    }
    if (type === 'select') {
      return (
        <SelectControl
          label={label}
          value={attrs[name]}
          onChange={value => onChange(name, value)}
          options={[...field.options]}
        />
      );
    }
    if (type === 'text') {
      return (
        <TextControl
          label={label}
          value={attrs[name]}
          onChange={value => onChange(name, value)}
        />
      );
    }
    if (type === 'textarea') {
      return (
        <TextareaControl
          label={label}
          value={attrs[name]}
          onChange={value => onChange(name, value)}
        />
      );
    }
    // Return integer value to match ACF field instead of boolean
    if (type === 'toggle') {
      return (
        <ToggleControl
          label={label}
          checked={attrs[name]}
          onChange={value => onChange(name, value ? 1 : 0)}
        />
      );
    }
  });
};

export default VFBlockFields;
