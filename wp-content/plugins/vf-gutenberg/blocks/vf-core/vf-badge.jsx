/**
Block Name: Badge
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientStyle} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

import '@visual-framework/vf-badge/vf-badge.precompiled';

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
  name: 'vf/badge',
  title: __('Badge'),
  attributes: {
    text: {
      type: 'string',
      default: __('Badge')
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
      label: __('Text')
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
      name: 'default',
      label: __('Default'),
      isDefault: true
    },
    {
      name: 'primary',
      label: __('Primary')
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
  withHOC: [[withBEMModifiers], [withTransientStyle, {key: 'theme_class'}]]
});
