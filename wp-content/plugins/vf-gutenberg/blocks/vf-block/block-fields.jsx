/**
 * VFBlockFields (component)
 * Iterate the `fields` property and return an array of WordPress field
 * controls that update their corresponding attributes. The `fields` array
 * is mapped from ACF configuration.
 */
import React from 'react';
import {
  BaseControl,
  CheckboxControl,
  RadioControl,
  RangeControl,
  SelectControl,
  TextControl,
  TextareaControl,
  ToggleControl
} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import CheckboxesControl from '../components/checkboxes-control';
import TaxonomyControl from '../components/taxonomy-control';
import URLControl from '../components/url-control';
import RichControl from '../components/rich-control';

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
          <CheckboxesControl
            name={name}
            attrs={attrs}
            field={field}
            label={label}
            onChange={onChange}
          />
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
        const tag = field.tag || 'p';
        const placeholder = field.placeholder || __('Type content…');
        return (
          <RichControl
            label={label}
            value={attrs[name]}
            tag={tag}
            placeholder={placeholder}
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
            type="text"
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
