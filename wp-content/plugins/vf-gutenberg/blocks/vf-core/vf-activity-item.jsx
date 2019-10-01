/**
 * VF Framework Badge
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

export default useVFCoreSettings({
  name: 'vf/activity-item',
  title: __('Activity Item'),
  parent: ['vf/activity-list'],
  hasRender: false,
  attributes: {
    text: {
      type: 'string'
    }
  },
  fields: [
    {
      name: 'text',
      control: 'rich',
      label: '',
      tag: 'p',
      placeholder: __('Type activity…')
    }
  ]
});
