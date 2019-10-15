/**
 * VF Framework Grid
 */
import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';

const defaults = useVFDefaults();

const ver = '1.0.0';

const settings = {
  ...defaults,
  name: 'vf/grid',
  title: __('Grid'),
  category: 'vf/core',
  description: __('Visual Framework (core)')
};

settings.save = props => {
  return (
    <div className="vf-grid">
      <InnerBlocks.Content />
    </div>
  );
};

settings.edit = props => {
  props.setAttributes({ver: props.ver || ver});
  return (
    <div className="vf-block-grid">
      <InnerBlocks
        allowedBlocks={['vf/grid-column']}
        template={[['vf/grid-column'], ['vf/grid-column']]}
        templateLock="all"
      />
    </div>
  );
};

export default settings;
