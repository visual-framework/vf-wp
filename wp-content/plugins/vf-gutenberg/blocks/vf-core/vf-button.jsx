/**
 * VF Framework Button
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientStyle} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from '../templates/vf-button';

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
  withHOC: [[withTransientStyle, {key: 'theme'}]]
});
