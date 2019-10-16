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
import {useHashsum} from '../hooks';
import CheckboxesControl from '../components/checkboxes-control';
import ColumnsControl from '../components/columns-control';
import DateControl from '../components/date-control';
import TaxonomyControl from '../components/taxonomy-control';
import URLControl from '../components/url-control';
import RichControl from '../components/rich-control';

// Allow control name variations to match ACF fields
const DATE_CONTROLS = ['date', 'date_picker'];
const RICH_CONTROLS = ['rich', 'wysiwyg'];
const TEXT_CONTROLS = ['text', 'email'];
const BOOL_CONTROLS = ['bool', 'boolean', 'toggle', 'true_false'];

// Fields component
const VFBlockFields = props => {
  const {attributes: attrs, setAttributes, fields} = props;

  // Generic event handler to update an attribute
  const handleChange = (name, value) => {
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
      let {control, help, label, name, onChange} = field;
      const key = useHashsum(field);

      // Fallback to default handler
      onChange = typeof onChange === 'function' ? onChange : handleChange;

      // The ACF "checkbox" field returns an array of one or more checked
      // values whereas "true_false" (here "toggle") uses a boolean value
      if (control === 'checkbox') {
        return (
          <CheckboxesControl
            key={key}
            name={name}
            attrs={attrs}
            field={field}
            label={label}
            onChange={onChange}
          />
        );
      }
      // Custom control to manage number of grid columns
      if (control === 'columns') {
        const min = parseInt(field.min) || 1;
        const max = parseInt(field.max) || 6;
        const value = parseInt(field.value) || 0;
        return (
          <ColumnsControl
            key={key}
            min={min}
            max={max}
            help={help}
            value={value}
            onChange={field.onChange}
          />
        );
      }
      if (DATE_CONTROLS.includes(control)) {
        let date = new Date(attrs[name]);
        if (isNaN(date.getTime())) {
          date = Date.now();
        }
        return (
          <DateControl
            key={key}
            label={label}
            currentDate={date}
            onChange={value => onChange(name, value)}
          />
        );
      }
      if (control === 'number') {
        const min = parseInt(field['min']) || 1;
        const max = parseInt(field['max']) || 10;
        return (
          <TextControl
            key={key}
            type="number"
            label={label}
            value={parseInt(attrs[name]) || min}
            onChange={value => onChange(name, parseInt(value))}
            min={min}
            max={max}
          />
        );
      }
      if (control === 'radio') {
        return (
          <RadioControl
            key={key}
            label={label}
            selected={attrs[name]}
            onChange={value => onChange(name, value)}
            options={[...field.options]}
          />
        );
      }
      if (control === 'range') {
        const min = parseInt(field['min']) || 1;
        const max = parseInt(field['max']) || 10;
        const step = parseInt(field['step']) || 1;
        return (
          <RangeControl
            key={key}
            label={label}
            value={parseInt(attrs[name]) || min}
            onChange={value => onChange(name, value)}
            step={step}
            min={min}
            max={max}
          />
        );
      }
      if (RICH_CONTROLS.includes(control)) {
        const tag = field.tag || 'p';
        const placeholder = field.placeholder || __('Type content…');
        return (
          <RichControl
            key={key}
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
            key={key}
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
            key={key}
            taxonomy={field.taxonomy}
            label={label}
            value={attrs[name]}
            onChange={value => onChange(name, value)}
          />
        );
      }
      if (TEXT_CONTROLS.includes(control)) {
        return (
          <TextControl
            key={key}
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
            key={key}
            label={label}
            value={attrs[name]}
            onChange={value => onChange(name, value)}
          />
        );
      }
      // Return integer value to match ACF field instead of boolean
      if (BOOL_CONTROLS.includes(control)) {
        return (
          <ToggleControl
            key={key}
            help={help}
            label={label}
            checked={attrs[name]}
            onChange={value => onChange(name, value ? 1 : 0)}
          />
        );
      }
      if (control === 'url') {
        return (
          <URLControl
            key={key}
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
