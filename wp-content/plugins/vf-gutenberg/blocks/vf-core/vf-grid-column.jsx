/**
 * VF Framework Grid Column
 */
import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor';
import {select} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';

const defaults = useVFDefaults();

const ver = '1.0.0';

const settings = {
  ...defaults,
  name: 'vf/grid-column',
  title: __('Grid Column'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  parent: ['vf/grid'],
  supports: {
    ...defaults.supports,
    inserter: false
  }
};

settings.save = props => {
  return (
    <div>
      <InnerBlocks.Content />
    </div>
  );
};

const withGridProps = Edit => {
  return props => {
    const {getBlockOrder} = select('core/block-editor');
    const hasChildBlocks = getBlockOrder(props.clientId).length > 0;
    return Edit({...props, hasChildBlocks});
  };
};

settings.edit = withGridProps(props => {
  props.setAttributes({ver: props.ver || ver});
  return (
    <div className="vf-block-column">
      <InnerBlocks
        templateLock={false}
        renderAppender={
          props.hasChildBlocks
            ? undefined
            : () => <InnerBlocks.ButtonBlockAppender />
        }
      />
    </div>
  );
});

export default settings;
