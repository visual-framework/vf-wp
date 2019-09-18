/**
 * VF Framework Button
 */
import React from 'react';
import VFBlock from '../vf-block';

const {__} = wp.i18n;
const {InspectorControls, URLInput} = wp.editor;
const {BaseControl, PanelBody, SelectControl, TextControl} = wp.components;

const vfButtonAttrs = {
  url: {
    type: 'string'
  },
  label: {
    type: 'string'
  },
  size: {
    type: 'string',
    default: 'regular'
  }
};

const vfButtonStyles = [
  {
    name: 'primary',
    label: __('Primary'),
    isDefault: true
  },
  {
    name: 'secondary',
    label: __('Secondary')
  },
  {
    name: 'tertiary',
    label: __('Tertiary')
  }
];

const vfButtonSizes = [
  {label: __('Small'), value: 'small'},
  {label: __('Regular'), value: 'regular'},
  {label: __('Large'), value: 'large'}
];

export default {
  name: 'vf/button',
  category: 'vf/core',
  title: __('Button'),
  description: __('Visual Framework'),
  keywords: [__('VF'), __('Visual Framework')],
  attributes: {
    ver: {
      type: 'integer'
    },
    mode: {
      type: 'string',
      default: 'edit'
    },
    ...vfButtonAttrs
  },
  styles: [...vfButtonStyles],
  supports: {
    align: false,
    className: false,
    customClassName: true,
    html: false
  },
  save: () => null,
  edit: props => {
    const {attributes, setAttributes} = props;
    return (
      <>
        <VFBlock {...props} ver={2} hasFooter>
          <TextControl
            label={__('Label')}
            value={attributes.label}
            onChange={label => setAttributes({label})}
          />
          <BaseControl label={__('URL')}>
            <URLInput
              value={attributes.url}
              onChange={url => setAttributes({url})}
              autoFocus={false}
            />
          </BaseControl>
        </VFBlock>
        <InspectorControls>
          <PanelBody title={__('Settings')} initialOpen={false}>
            <SelectControl
              label={__('Size')}
              value={attributes['size']}
              onChange={size => setAttributes({size})}
              options={vfButtonSizes}
            />
          </PanelBody>
        </InspectorControls>
      </>
    );
  }
};
