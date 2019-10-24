/**
 * Block transforms for: `vf/divider`
 */
import React from 'react';
import {createBlock} from '@wordpress/blocks';

export const fromCore = () => {
  return {
    type: 'block',
    blocks: ['core/separator'],
    transform: attributes => {
      return createBlock('vf/divider');
    }
  };
};
