/**
 * VF Framework Grid Column
 */
import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor';
import {select} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';

const withGridProps = Edit => {
  return props => {
    const {getBlockOrder} = select('core/block-editor');
    const hasChildBlocks = getBlockOrder(props.clientId).length > 0;
    return Edit({...props, hasChildBlocks});
  };
};

const settings = useVFCoreSettings({
  name: 'vf/grid-column',
  title: __('Grid Column'),
  isRenderable: false,
  isInsertable: false,
  parent: ['vf/grid']
});

settings.save = props => {
  return (
    <div>
      <InnerBlocks.Content />
    </div>
  );
};

settings.edit = withGridProps(props => {
  return (
    <div className={props.className}>
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
