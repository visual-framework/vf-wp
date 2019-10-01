/**
 * VF Framework Box
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientStyle} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from '../templates/vf-box';

export default useVFCoreSettings({
  name: 'vf/box',
  title: __('Box'),
  attributes: {
    heading: {
      type: 'string'
    },
    text: {
      type: 'string'
    }
  },
  fields: [
    {
      name: 'heading',
      control: 'rich',
      default: '',
      label: '',
      tag: 'h3',
      placeholder: __('Type box heading…')
    },
    {
      name: 'text',
      control: 'rich',
      default: '',
      label: '',
      tag: 'p',
      placeholder: __('Type box content…')
    }
  ],
  styles: [
    {
      name: 'default',
      label: __('Default'),
      isDefault: true
    },
    {
      name: 'factoid',
      label: __('Factoid')
    },
    {
      name: 'inlay',
      label: __('Inlay')
    }
  ],
  withHOC: [[withTransientStyle, {key: 'class', BEM: true}]]
});
