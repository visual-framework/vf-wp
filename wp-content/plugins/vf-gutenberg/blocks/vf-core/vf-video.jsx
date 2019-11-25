/**
Block Name: Video
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientAttributeMap} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from './templates/vf-video.precompiled';
// import {fromCore} from './transforms/video';

export default useVFCoreSettings({
  name: 'vf/video',
  title: __('Video / Embed'),
  attributes: {
    url: {
      type: 'string'
    },
    responsive: {
      type: 'integer',
      default: 1
    },
    width: {
      type: 'integer',
      default: 640
    },
    height: {
      type: 'integer',
      default: 360
    }
  },
  fields: [
    {
      name: 'url',
      control: 'url',
      label: 'URL',
      disableSuggestions: true
    }
  ],
  withHOC: [[withTransientAttributeMap, [{from: 'url', to: 'src'}]]]
});
