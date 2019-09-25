/**
 * VF Framework Badge
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import VFBlock from '../vf-block';
import VFBlockFields from '../vf-block/block-fields';
import template from '../templates/vf-badge';

const vfBadgeAttrs = {
  text: {
    type: 'string'
  }
};

const vfBadgeFields = [
  {
    name: 'text',
    control: 'text',
    label: 'Text'
  }
];

export default {
  name: 'vf/badge',
  category: 'vf/core',
  title: __('Badge'),
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
    render: {
      type: 'string',
      default: ''
    },
    ...vfBadgeAttrs
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
        <VFBlockFields {...props} fields={vfBadgeFields} />
      </VFBlock>
    );
  }
};
