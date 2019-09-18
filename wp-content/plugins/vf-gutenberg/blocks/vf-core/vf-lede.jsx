/**
 * VF Framework Lede
 */
import React from 'react';
import VFBlock from '../vf-block';

const {__} = wp.i18n;
const {RichText} = wp.editor;
const {BaseControl} = wp.components;

const vfLedeAttrs = {
  text: {
    type: 'string'
  }
};

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
    const {attributes, setAttributes} = props;
    return (
      <VFBlock {...props} ver={1} hasFooter>
        <BaseControl>
          <RichText
            tagName="h1"
            value={attributes.text}
            placeholder={__('Type lede text…')}
            onChange={text => setAttributes({text})}
          />
        </BaseControl>
      </VFBlock>
    );
  }
};
