/**
 * VF Framework Badge
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from '../templates/vf-badge';

export default useVFCoreSettings({
  name: 'vf/badge',
  title: __('Badge'),
  attributes: {
    text: {
      type: 'string',
      default: __('Badge')
    }
  },
  fields: [
    {
      name: 'text',
      control: 'text',
      label: __('Text')
    }
  ]
});
