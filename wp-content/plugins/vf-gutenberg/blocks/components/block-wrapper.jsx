import React from 'react';
import {
  useBlockProps,
  __experimentalBlock as ExperimentalBlock
} from '@wordpress/block-editor';

const BlockWrapper = ({ blockProps, forSave, children }) => {
  if (ExperimentalBlock && ExperimentalBlock.div) {
   if (forSave) {
     return <div {...blockProps}>{children}</div>
   }
    return <ExperimentalBlock.div {...blockProps}>{children}</ExperimentalBlock.div>
  }
  if (useBlockProps) {
    const allProps = forSave ? useBlockProps.save(blockProps) : useBlockProps(blockProps);
    return <div {...allProps}>{children}</div>
  }

};

export default BlockWrapper;
