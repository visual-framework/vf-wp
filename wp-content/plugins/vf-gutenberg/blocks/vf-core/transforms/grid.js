/**
 * Block transforms for: `vf/grid`, `vf/embl-grid`, and `core/columns`
 */
import React from 'react';
import {createBlock} from '@wordpress/blocks';

// Map inner blocks and create new `vf/grid-column` with their children
// New columns are appended to match minimum
// End columns are merged to match maximum
export const fromColumns = (fromBlock, toBlock, min, max) => {
  return {
    type: 'block',
    blocks: [fromBlock],
    // Match function (ignore initial placeholder state)
    isMatch: attributes => attributes.placeholder !== 1,
    // Transform function
    transform: (attributes, innerBlocks) => {
      // Map column props
      let innerProps = innerBlocks.map(block => ({
        attributes: {...block.attributes},
        innerBlocks: [...block.innerBlocks]
      }));
      // Fill empty props to match min number of columns
      while (innerProps.length < min) {
        innerProps.push({});
      }
      // Merge end props to match max number of columns
      while (innerProps.length > max) {
        const mergeProps = innerProps.pop();
        innerProps[innerProps.length - 1].innerBlocks.push(
          ...mergeProps.innerBlocks
        );
      }
      // Return new grid block with inner columns
      return createBlock(
        toBlock,
        {columns: innerProps.length},
        innerProps.map(props =>
          createBlock(
            'vf/grid-column',
            props.attributes || {},
            props.innerBlocks || []
          )
        )
      );
    }
  };
};
