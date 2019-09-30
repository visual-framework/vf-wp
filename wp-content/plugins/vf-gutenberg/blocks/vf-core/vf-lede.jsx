/**
 * VF Framework Lede
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientAttributeMap} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from '../templates/vf-lede';

const settings = useVFCoreSettings({
  name: 'vf/lede',
  title: __('Lede'),
  attributes: {
    text: {
      type: 'string',
      default: __('Lede heading…')
    }
  },
  fields: [
    {
      name: 'text',
      control: 'rich',
      label: '',
      tag: 'h1',
      placeholder: __('Type lede heading…')
    }
  ]
});

settings.edit = withTransientAttributeMap(
  [{from: 'text', to: 'vf_lede_text'}],
  settings.edit
);

export default settings;
