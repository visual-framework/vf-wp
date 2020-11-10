/**
Block Name: Blockquote
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import {fromParagraph, fromQuote} from './transforms/blockquote';

import '@visual-framework/vf-blockquote/vf-blockquote.precompiled';

export default useVFCoreSettings({
  name: 'vf/blockquote',
  title: __('Blockquote'),
  attributes: {
    html: {
      type: 'string'
    }
  },
  fields: [
    {
      name: 'html',
      control: 'rich',
      default: '',
      label: '',
      tag: 'p',
      placeholder: __('Type blockquote…')
    }
  ],
  transforms: {
    from: [fromParagraph(), fromQuote()]
  }
});
