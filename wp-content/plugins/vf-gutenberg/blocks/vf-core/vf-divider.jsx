/**
Block Name: Divider
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import template from '@visual-framework/vf-divider/vf-divider.precompiled';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

export default useVFCoreSettings({
  name: 'vf/divider',
  title: __('Divider'),
  attributes: {}
});
