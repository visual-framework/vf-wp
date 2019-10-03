/**
 * VF Framework Badge
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import {
  withTransientInnerBlocks,
  withTransientAttributeMap
} from '../hooks/with-transient';
import template from '../templates/vf-activity-list';

const withActivityItems = Edit => {
  return props => {
    props.transient.list = [];
    props.transient.innerBlocks.forEach(block => {
      if (block.name === 'vf/activity-item') {
        props.transient.list.push(block.attributes.text);
      }
    });
    return Edit(props);
  };
};

export default useVFCoreSettings({
  name: 'vf/activity-list',
  title: __('Activity List'),
  attributes: {
    heading: {
      type: 'string',
      default: __('Activity List')
    }
  },
  fields: [
    {
      name: 'heading',
      control: 'text',
      label: __('Heading')
    }
  ],
  allowedBlocks: ['vf/activity-item'],
  withHOC: [
    [withTransientAttributeMap, [{from: 'heading', to: 'date'}]],
    [withActivityItems],
    [withTransientInnerBlocks]
  ]
});
