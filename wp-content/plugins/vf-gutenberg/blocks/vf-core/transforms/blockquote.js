/**
 * Block transforms for: `vf/blockquote`
 */
import React from 'react';
import {createBlock} from '@wordpress/blocks';

export const fromParagraph = () => {
  return {
    type: 'block',
    blocks: ['core/paragraph'],
    transform: attributes => {
      return createBlock('vf/blockquote', {
        html: attributes.content
      });
    }
  };
};
export const fromQuote = () => {
  return {
    type: 'block',
    blocks: ['core/quote'],
    transform: attributes => {
      let {citation, value} = attributes;
      if (/^\s*$/.test(citation)) {
        citation = '';
      }
      return createBlock('vf/blockquote', {
        html: value + citation
      });
    }
  };
};
