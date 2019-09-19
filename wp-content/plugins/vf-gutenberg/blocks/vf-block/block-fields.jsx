/**
 * VFBlockFields (component)
 * Iterate the `fields` property and return an array of WordPress field
 * controls that update their corresponding attributes. The `fields` array
 * is mapped from ACF configuration.
 */
import React from 'react';
import TaxonomyControl from '../components/taxonomy-control';
import URLControl from '../components/url-control';
import RichControl from '../components/rich-control';

const {__} = wp.i18n;

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

  // Add any initial controls from children
  const controls = [];
  if (props.children) {
    controls.push(props.children);
  }

  // Map fields and add array of controls
  controls.push(
    fields.map(field => {
      const {name, control, label} = field;

      // The ACF "checkbox" field returns an array of one or more checked
      // values whereas "true_false" (here "toggle") uses a boolean value
      if (control === 'checkbox') {
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
      if (control === 'number') {
        return (
          <TextControl
            type="number"
            label={label}
            value={parseInt(attrs[name])}
            onChange={value => onChange(name, parseInt(value))}
            min={parseInt(field['min'])}
            max={parseInt(field['max'])}
          />
        );
      }
      if (control === 'radio') {
        return (
          <RadioControl
            label={label}
            selected={attrs[name]}
            onChange={value => onChange(name, value)}
            options={[...field.options]}
          />
        );
      }
      if (control === 'range') {
        return (
          <RangeControl
            label={label}
            value={parseInt(attrs[name])}
            onChange={value => onChange(name, value)}
            min={parseInt(field['min'])}
            max={parseInt(field['max'])}
            step={parseInt(field['step']) || 1}
          />
        );
      }
      if (control === 'rich') {
        return (
          <RichControl
            label={label}
            value={attrs[name]}
            tag={field.tag || 'p'}
            placeholder={__('Type content…')}
            onChange={value => onChange(name, value)}
          />
        );
      }
      if (control === 'select') {
        return (
          <SelectControl
            label={label}
            value={attrs[name]}
            onChange={value => onChange(name, value)}
            options={[{label: __('Select…'), value: ''}, ...field.options]}
          />
        );
      }
      if (control === 'taxonomy') {
        return (
          <TaxonomyControl
            taxonomy={field.taxonomy}
            label={label}
            value={attrs[name]}
            onChange={value => onChange(name, value)}
          />
        );
      }
      if (control === 'text') {
        return (
          <TextControl
            type={field.acf}
            label={label}
            value={attrs[name]}
            onChange={value => onChange(name, value)}
          />
        );
      }
      if (control === 'textarea') {
        return (
          <TextareaControl
            label={label}
            value={attrs[name]}
            onChange={value => onChange(name, value)}
          />
        );
      }
      // Return integer value to match ACF field instead of boolean
      if (control === 'true_false') {
        return (
          <ToggleControl
            label={label}
            checked={attrs[name]}
            onChange={value => onChange(name, value ? 1 : 0)}
          />
        );
      }
      if (control === 'url') {
        return (
          <URLControl
            label={label}
            value={attrs[name]}
            onChange={value => onChange(name, value)}
          />
        );
      }
    })
  );

  return controls;
};

export default VFBlockFields;
