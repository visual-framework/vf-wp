/**
 * VF Framework Lede
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import VFBlock from '../vf-block';
import VFBlockFields from '../vf-block/block-fields';

const vfLedeAttrs = {
  text: {
    type: 'string'
  }
};

const vfLedeFields = [
  {
    name: 'text',
    control: 'rich',
    default: '',
    label: '',
    tag: 'h1',
    placeholder: 'Type lede heading…'
  }
];

export default {
  name: 'vf/lede',
  category: 'vf/core',
  title: __('Lede'),
  description: __('Visual Framework'),
  keywords: [__('VF'), __('Visual Framework')],
  attributes: {
    ver: {
      type: 'integer'
    },
    mode: {
      type: 'string',
      default: 'edit'
    },
    ...vfLedeAttrs
  },
  supports: {
    align: false,
    className: false,
    customClassName: true,
    html: false
  },
  save: () => null,
  edit: props => {
    return (
      <VFBlock {...props} ver={1} hasFooter>
        <VFBlockFields {...props} fields={vfLedeFields} />
      </VFBlock>
    );
  }
};
