/**
Block Name: Activity List
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
    const transient = {...(props.transient || {})};
    transient.list = [];
    if (Array.isArray(transient.innerBlocks)) {
      transient.innerBlocks.forEach(block => {
        if (block.name === 'vf/activity-item') {
          transient.list.push(block.attributes.text);
        }
      });
    }
    return Edit({...props, transient});
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
