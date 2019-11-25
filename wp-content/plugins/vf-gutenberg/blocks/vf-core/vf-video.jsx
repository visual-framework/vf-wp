/**
Block Name: Video
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientAttributeMap} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from './templates/vf-video.precompiled';
// import {fromCore} from './transforms/video';

const RATIOS = {
  '2:1': {
    label: __('(2:1) Cinema'),
    ratio: '2:1',
    width: 640,
    height: 320
  },
  '16:9': {
    label: __('(16:9) Widescreen'),
    ratio: '16:9',
    width: 640,
    height: 360
  },
  '4:3': {
    label: __('(4:3) Standard'),
    ratio: '4:3',
    width: 640,
    height: 480
  },
  '1:1': {
    label: __('(1:1) Square'),
    ratio: '1:1',
    width: 640,
    height: 640
  }
};

const withRatioAttributes = Edit => {
  return props => {
    const transient = {...(props.transient || {})};
    let {ratio, width, height} = props.attributes;
    ratio = ratio || '';
    transient.padding = (100 / width) * height;
    const pattern = /(\d+):(\d+)/;
    if (pattern.test(ratio) && ratio in RATIOS) {
      const [, r1, r2] = ratio.match(pattern);
      transient.padding = (100 / r1) * r2;
      props.setAttributes({
        ...RATIOS[ratio]
      });
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
    ratio: {
      type: 'string',
      default: '16:9'
    },
    width: {
      type: 'integer',
      default: RATIOS['16:9'].width
    },
    height: {
      type: 'integer',
      default: RATIOS['16:9'].height
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
      label: __('Preset Ratio'),
      inspector: true,
      options: Object.keys(RATIOS).map(key => ({
        label: RATIOS[key].label,
        value: key
      }))
    },
    {
      name: 'width',
      control: 'number',
      label: __('Width'),
      inspector: true
    },
    {
      name: 'height',
      control: 'number',
      label: __('Height'),
      inspector: true
    }
  ],
  withHOC: [
    [withRatioAttributes],
    [withTransientAttributeMap, [{from: 'url', to: 'src'}]]
  ]
});
