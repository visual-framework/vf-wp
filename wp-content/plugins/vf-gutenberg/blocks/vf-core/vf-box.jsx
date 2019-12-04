/**
Block Name: Box
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import template from '@visual-framework/vf-box/vf-box.precompiled';
import {withTransientStyle} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

export default useVFCoreSettings({
  ver: '1.0.2',
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
  example: {
    attributes: {
      heading: __('Did You Know?'),
      text: __(
        'Invasive cancer is the leading cause of death in the developed world and the second leading in the developing world.'
      )
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
