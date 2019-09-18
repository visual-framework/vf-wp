/**
 * VF Framework Box
 */
import React from 'react';
import VFBlock from '../vf-block';

const {__} = wp.i18n;
const {RichText} = wp.editor;
const {BaseControl} = wp.components;

const vfBoxAttrs = {
  heading: {
    type: 'string'
  },
  text: {
    type: 'string'
  }
};

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
    const {attributes, setAttributes} = props;
    return (
      <VFBlock {...props} ver={1} hasFooter>
        <BaseControl>
          <RichText
            tagName="h3"
            value={attributes.heading}
            placeholder={__('Type heading…')}
            onChange={heading => setAttributes({heading})}
          />
        </BaseControl>
        <BaseControl>
          <RichText
            tagName="p"
            value={attributes.text}
            placeholder={__('Type paragraph…')}
            onChange={text => setAttributes({text})}
          />
        </BaseControl>
      </VFBlock>
    );
  }
};
