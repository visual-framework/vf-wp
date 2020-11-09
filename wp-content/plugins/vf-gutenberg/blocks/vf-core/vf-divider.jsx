/**
Block Name: Divider
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import {fromCore} from './transforms/divider';

import '@visual-framework/vf-divider/vf-divider.precompiled';

export default useVFCoreSettings({
  name: 'vf/divider',
  title: __('Divider'),
  attributes: {},
  transforms: {
    from: [fromCore()]
  }
});
