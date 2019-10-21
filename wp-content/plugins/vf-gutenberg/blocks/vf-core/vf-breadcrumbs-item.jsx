/**
Block Name: Breadcrumbs Item
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

export default useVFCoreSettings({
  name: 'vf/breadcrumbs-item',
  title: __('Breadcrumbs Item'),
  parent: ['vf/breadcrumbs'],
  isRenderable: false,
  attributes: {
    text: {
      type: 'string',
      default: __('Breadcrumb')
    },
    url: {
      type: 'string',
      default: '/'
    }
  },
  fields: [
    {
      name: 'text',
      control: 'text',
      label: __('Text')
    },
    {
      name: 'url',
      control: 'url',
      label: __('URL')
    }
  ]
});
