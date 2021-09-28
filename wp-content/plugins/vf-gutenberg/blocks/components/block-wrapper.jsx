import React from 'react';
import {
  useBlockProps,
  __experimentalBlock as ExperimentalBlock
} from '@wordpress/block-editor';

const BlockWrapper = ({ blockProps, children }) => {
  if (ExperimentalBlock && ExperimentalBlock.div) {
    return <ExperimentalBlock.div {...blockProps}>{children}</ExperimentalBlock.div>
  }
  if (useBlockProps) {
    const allProps = useBlockProps(blockProps);
    return <div {...allProps}>{children}</div>
  }

};

export default BlockWrapper;
