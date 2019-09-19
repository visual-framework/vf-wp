/**
 * VF Framework Box
 */
import React from 'react';
import VFBlock from '../vf-block';
import VFBlockFields from '../vf-block/block-fields';

const {__} = wp.i18n;

const vfBoxAttrs = {
  heading: {
    type: 'string'
  },
  text: {
    type: 'string'
  }
};

const vfBoxFields = [
  {
    name: 'heading',
    control: 'rich',
    default: '',
    label: '',
    tag: 'h3',
    placeholder: 'Type box heading…'
  },
  {
    name: 'text',
    control: 'rich',
    default: '',
    label: '',
    tag: 'p',
    placeholder: 'Type box content…'
  }
];

const vfBoxStyles = [
  {
    name: 'default',
    label: __('Default'),
    isDefault: true
  },
  {
    name: 'factoid',
    label: __('Factoid')
  },
  {
    name: 'inlay',
    label: __('Inlay')
  }
];

export default {
  name: 'vf/box',
  category: 'vf/core',
  title: __('Box'),
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
    ...vfBoxAttrs
  },
  styles: [...vfBoxStyles],
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
        <VFBlockFields {...props} fields={vfBoxFields} />
      </VFBlock>
    );
  }
};
