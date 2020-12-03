/**
Block Name: Video
*/
import React from 'react';
import {__} from '@wordpress/i18n';
import {withTransientAttributeMap} from '../hooks/with-transient';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

import '@visual-framework/vf-embed/vf-embed.precompiled';

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

    if (ratio && ratio in RATIOS) {
      width = RATIOS[ratio].width;
      height = RATIOS[ratio].height;
      props.setAttributes({width, height});
    }

    if (isNaN(width)) {
      width = RATIOS['16:9'].width;
      props.setAttributes({width});
    }

    if (isNaN(height)) {
      height = RATIOS['16:9'].height;
      props.setAttributes({height});
    }

    transient.vf_embed_variant_custom = true;
    transient.vf_embed_custom_ratio_X = width;
    transient.vf_embed_custom_ratio_Y = height;
    if (maxWidth > 0) {
      transient.vf_embed_max_width = `${maxWidth}px`;
    } else {
      transient.vf_embed_max_width = '100%';
    }
    transient.vf_embedded_content = '';
    if (transient.src) {
      transient.vf_embedded_content = `<iframe width="${width}" height="${height}" src="${transient.src}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
    }
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
      type: 'integer'
    },
    height: {
      type: 'integer'
    },
    maxWidth: {
      type: 'string',
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
