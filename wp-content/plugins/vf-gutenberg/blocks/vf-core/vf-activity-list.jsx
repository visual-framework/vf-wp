/**
 * VF Framework Badge
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import {withTransientInnerBlocks} from '../hooks/with-transient';
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
  allowedBlocks: ['vf/activity-item'],
  withHOC: [[withActivityItems], [withTransientInnerBlocks]]
});
