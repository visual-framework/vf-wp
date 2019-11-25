/**
Block Name: Video
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientAttributeMap} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from './templates/vf-video.precompiled';
// import {fromCore} from './transforms/video';

const withRatioPadding = Edit => {
  return props => {
    const transient = {...(props.transient || {})};
    const ratio = props.attributes.ratio || '';
    const pattern = /(\d+):(\d+)/;
    if (pattern.test(ratio)) {
      const [, r1, r2] = ratio.match(pattern);
      transient.padding = (100 / r1) * r2;
    }
    return Edit({...props, transient});
  };
};

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
    },
    ratio: {
      type: 'string',
      default: ''
    }
  },
  fields: [
    {
      name: 'url',
      control: 'url',
      label: __('URL'),
      disableSuggestions: true
    },
    {
      name: 'ratio',
      control: 'select',
      label: __('Aspect Ratio'),
      inspector: true,
      options: [
        {label: __('Widescreen (16:9)'), value: '16:9'},
        {label: __('Standard (4:3)'), value: '4:3'},
        {label: __('Square (1:1)'), value: '1:1'}
      ]
    }
  ],
  withHOC: [
    [withRatioPadding],
    [withTransientAttributeMap, [{from: 'url', to: 'src'}]]
  ]
});
