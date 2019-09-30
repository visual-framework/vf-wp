/**
 * VF Framework Divider
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from '../templates/vf-divider';

export default useVFCoreSettings({
  name: 'vf/divider',
  title: __('Divider'),
  attributes: {}
});
