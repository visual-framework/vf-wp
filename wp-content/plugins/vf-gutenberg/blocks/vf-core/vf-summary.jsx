/**
 * VF Framework Lede (WIP)
 * TODO: Summary title link (hard-coded in Nunjucks template)
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientAttributeMap} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from '../templates/vf-summary';

export default useVFCoreSettings({
  name: 'vf/summary',
  title: __('Summary'),
  attributes: {
    title: {
      type: 'string',
      default: __('Summary heading…')
    },
    text: {
      type: 'string',
      default: __('Summary text…')
    }
  },
  fields: [
    {
      name: 'title',
      control: 'rich',
      label: '',
      tag: 'h3',
      placeholder: __('Type summary heading…')
    },
    {
      name: 'text',
      control: 'rich',
      label: '',
      tag: 'p',
      placeholder: __('Type summary text…')
    }
  ],
  withHOC: [
    [
      withTransientAttributeMap,
      [
        {from: 'title', to: 'summary__title'},
        {from: 'text', to: 'summary__text'}
      ]
    ]
  ]
});
