/**
 * VF Framework Activity List Item
 */
import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

const settings = useVFCoreSettings({
  name: 'vf/grid-column',
  title: __('Grid Column'),
  isRenderable: false
});

settings.edit = props => {
  return (
    <div className={props.className}>
      <InnerBlocks />
    </div>
  );
};

settings.save = props => {
  return (
    <div>
      <InnerBlocks.Content />
    </div>
  );
};

export default settings;
