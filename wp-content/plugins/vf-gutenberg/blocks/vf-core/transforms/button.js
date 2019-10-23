/**
 * Block transforms for: `vf/button`
 */
import React from 'react';
import {createBlock} from '@wordpress/blocks';

export const fromButton = () => {
  return {
    type: 'block',
    blocks: ['core/button'],
    transform: attributes => {
      const {url, text, className} = attributes;
      const outline = /\-outline/.test(className) ? 1 : 0;
      return createBlock('vf/button', {
        text,
        outline,
        href: url
      });
    }
  };
};
