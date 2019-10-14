/**
 * VF Framework Grid
 */
import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

const settings = useVFCoreSettings({
  name: 'vf/grid',
  title: __('Grid'),
  isRenderable: false
});

settings.edit = props => {
  return (
    <div className={props.className}>
      <InnerBlocks
        allowedBlocks={['vf/grid-column']}
        template={[['vf/grid-column'], ['vf/grid-column']]}
        templateLock="all"
      />
    </div>
  );
};

settings.save = props => {
  return (
    <div className="vf-grid">
      <InnerBlocks.Content />
    </div>
  );
};

export default settings;
