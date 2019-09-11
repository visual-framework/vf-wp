/**
 * VF-WP Latest Posts
 */

const {__} = wp.i18n;
const {
  Button,
  BaseControl,
  CheckboxControl,
  RadioControl,
  RangeControl,
  SelectControl,
  TextControl,
  TextareaControl,
  ToggleControl
} = wp.components;

import PluginEdit from '../vf-plugin';

export const settings = {
  name: 'vf/example',
  title: __('Example', 'vfwp'),
  category: 'vf/contenthub',
  keywords: [
    __('VF', 'vfwp'),
    __('Visual Framework', 'vfwp'),
    __('Content Hub', 'vfwp')
  ],
  attributes: {
    ver: {
      type: 'integer'
    },
    mode: {
      type: 'string'
    },
    text: {
      type: 'string'
    },
    textarea: {
      type: 'string'
    },
    select: {
      type: 'integer'
    },
    range: {
      type: 'integer'
    },
    radio: {
      type: 'string'
    },
    checkbox: {
      type: 'boolean'
    }
  },
  supports: {
    align: false,
    html: false,
    className: false,
    customClassName: false
  },
  save: () => null,
  edit: props => {
    const onUpdate = () => {
      props.setAttributes({
        mode: props.attributes.mode === 'edit' ? 'view' : 'edit'
      });
    };
    const onTextChange = value => {
      props.setAttributes({
        text: value
      });
    };
    const onTextareaChange = value => {
      props.setAttributes({
        textarea: value
      });
    };
    const onSelectChange = option => {
      props.setAttributes({
        select: option
      });
    };
    const onRangeChange = option => {
      props.setAttributes({
        range: option
      });
    };
    const onRadioChange = option => {
      props.setAttributes({
        radio: option
      });
    };
    const onCheckboxChange = checked => {
      props.setAttributes({
        checkbox: checked
      });
    };
    return (
      <PluginEdit {...props}>
        <TextControl
          label="Text"
          value={props.attributes.text}
          onChange={onTextChange}
        />
        <TextareaControl
          label="Textarea"
          value={props.attributes.textarea}
          onChange={onTextareaChange}
        />
        <SelectControl
          label="Select"
          value={props.attributes.select}
          options={[
            {label: 'One', value: 0},
            {label: 'Two', value: 1},
            {label: 'Three', value: 2}
          ]}
          onChange={onSelectChange}
        />
        <RangeControl
          label="Range"
          value={props.attributes.range}
          onChange={onRangeChange}
          min={1}
          max={10}
        />
        <RadioControl
          label="Radio"
          selected={props.attributes.radio}
          options={[
            {label: 'One', value: '0'},
            {label: 'Two', value: '1'},
            {label: 'Three', value: '2'}
          ]}
          onChange={onRadioChange}
        />
        <CheckboxControl
          heading="Checkbox"
          label="checkbox"
          checked={props.attributes.checkbox}
          onChange={onCheckboxChange}
        />
        <Button isDefault isLarge onClick={onUpdate}>
          {__('Update', 'vfwp')}
        </Button>
      </PluginEdit>
    );
  }
};
