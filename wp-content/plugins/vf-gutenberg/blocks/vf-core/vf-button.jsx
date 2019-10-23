/**
Block Name: Button
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import template from '@visual-framework/vf-button/vf-button.precompiled';
import {withTransientStyle} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

const withBEMModifiers = Edit => {
  return props => {
    const transient = {...(props.transient || {})};
    transient.style = [];
    if (props.attributes.outline) {
      transient.style.push('outline');
    }
    if (props.attributes.rounded) {
      transient.style.push('rounded');
    }
    if (props.attributes.pill) {
      transient.style.push('pill');
    }
    return Edit({...props, transient});
  };
};

export default useVFCoreSettings({
  name: 'vf/button',
  title: __('Button'),
  attributes: {
    href: {
      type: 'string',
      default: '/'
    },
    text: {
      type: 'string',
      default: __('Button')
    },
    theme: {
      type: 'string',
      default: 'primary'
    },
    size: {
      type: 'string',
      default: ''
    },
    outline: {
      type: 'integer'
    },
    rounded: {
      type: 'integer'
    },
    pill: {
      type: 'integer'
    }
  },
  fields: [
    {
      name: 'text',
      control: 'text',
      label: __('Label')
    },
    {
      name: 'href',
      control: 'url',
      label: __('URL')
    },
    {
      name: 'size',
      control: 'select',
      label: __('Size'),
      inspector: true,
      options: [
        {label: __('Small'), value: 'sm'},
        {label: __('Large'), value: 'lg'}
      ]
    },
    {
      name: 'outline',
      control: 'true_false',
      label: __('Outline'),
      inspector: true
    },
    {
      name: 'rounded',
      control: 'true_false',
      label: __('Rounded'),
      inspector: true
    },
    {
      name: 'pill',
      control: 'true_false',
      label: __('Pill'),
      inspector: true
    }
  ],
  styles: [
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
  ],
  withHOC: [[withBEMModifiers], [withTransientStyle, {key: 'theme'}]]
});
