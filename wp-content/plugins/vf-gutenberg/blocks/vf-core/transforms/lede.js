/**
 * Block transforms for: `vf/lede`
 */
import React from 'react';
import {createBlock} from '@wordpress/blocks';

export const fromCore = () => {
  return {
    type: 'block',
    blocks: ['core/heading', 'core/paragraph'],
    transform: attributes => {
      return createBlock('vf/lede', {
        text: attributes.content
      });
    }
  };
};
