/**
Block Name: Video
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientAttributeMap} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from './templates/vf-embed.precompiled';

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
    let {ratio, width, height, maxWidth} = props.attributes;
    ratio = ratio || '';
    let padding = (100 / width) * height;
    const pattern = /(\d+):(\d+)/;
    if (pattern.test(ratio) && ratio in RATIOS) {
      const [, r1, r2] = ratio.match(pattern);
      padding = (100 / r1) * r2;
      props.setAttributes({
        ...RATIOS[ratio]
      });
    }
    let style = `padding-top: 0; padding-bottom: ${padding}%;`;
    if (maxWidth) {
      style += ` --vf-video-max-width: ${maxWidth}px;`;
      style += ' max-width: var(--vf-video-max-width);';
      // style += ` padding-bottom: calc(${height} / ${width} * (100% - (100% - var(--vf-video-max-width))));`;
      style += ` padding-bottom: calc(${height} / ${width} * var(--vf-video-max-width));`;
    }
    transient.style = style;
    return Edit({...props, transient});
  };
};

export default useVFCoreSettings({
  name: 'vf/embed',
  title: __('Embed'),
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
    },
    maxWidth: {
      type: 'integer',
      default: 0
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
      help: __('Deselect preset to set a custom width.'),
      inspector: true,
      max: 1920,
      min: 320
    },
    {
      name: 'height',
      control: 'number',
      label: __('Height'),
      help: __('Deselect preset to set a custom height.'),
      inspector: true,
      max: 1080,
      min: 180
    },
    {
      name: 'maxWidth',
      control: 'number',
      label: __('Maximum Width'),
      help: __('Restrict embed resize to this width.'),
      inspector: true,
      max: 1920,
      min: 0
    }
  ],
  withHOC: [
    [withRatioAttributes],
    [withTransientAttributeMap, [{from: 'url', to: 'src'}]]
  ]
});
