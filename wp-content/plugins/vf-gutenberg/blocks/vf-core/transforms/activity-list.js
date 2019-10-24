/**
 * Block transforms for: `vf/divider`
 */
import React from 'react';
import {createBlock} from '@wordpress/blocks';

export const fromCore = () => {
  return {
    type: 'block',
    blocks: ['core/list'],
    transform: attributes => {
      const innerBlocks = [];
      // Only transform browser-side via DOM to parse HTML in `value` attribute
      if (typeof window !== 'object') {
        return createBlock('vf/activity-list');
      }
      const list = window.document.createElement('ul');
      list.innerHTML = attributes.values;
      list.children.forEach(el => {
        if (el.nodeName.toLowerCase() === 'li') {
          innerBlocks.push(
            createBlock('vf/activity-item', {text: el.innerHTML})
          );
        }
      });
      return createBlock('vf/activity-list', {}, innerBlocks);
    }
  };
};
