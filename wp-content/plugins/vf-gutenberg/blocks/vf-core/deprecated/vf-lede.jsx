/**
Block Name: Lede
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientAttributeMap} from '../../hooks/with-transient';
import useVFCoreSettings from '../../hooks/use-vf-core-settings';
import {fromCore} from '../transforms/lede';

import '@visual-framework/vf-lede/vf-lede.precompiled';

const settings = useVFCoreSettings({
  name: 'vf/lede',
  title: __('Lede'),
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
      tag: 'h1'
    }
  ],
  transforms: {
    from: [fromCore()]
  },
  withHOC: [[withTransientAttributeMap, [{from: 'text', to: 'vf_lede_text'}]]]
});

export default {
  ...settings,
  supports: {
    ...settings.supports,
    inserter: false
  }
};
