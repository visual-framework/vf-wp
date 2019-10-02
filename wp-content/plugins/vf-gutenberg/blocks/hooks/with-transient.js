import {select} from '@wordpress/data';
import {useStyleName} from './';

/**
 * Wrap block edit function to add transient property
 * assigned to custom attribute.
 */
export const withTransientAttribute = (Edit, attr) => {
  return props => {
    return Edit({
      ...props,
      transient: {
        ...(props.transient || {}),
        [attr.key]: attr.value || props.attributes[attr.key]
      }
    });
  };
};

/**
 * Wrap block edit function to add block style as transient property
 * Optionally use BEM notation
 */
export const withTransientStyle = (Edit, opts) => {
  return props => {
    const isBEM = 'BEM' in opts;
    const style = useStyleName(props.className);
    const name = props.name.replace(/^vf\//, 'vf-');
    const value = isBEM ? `${name}--${style}` : style;
    if (isBEM && !style) {
      return Edit(props);
    }
    return withTransientAttribute(Edit, {
      key: opts.key,
      value
    })(props);
  };
};

/**
 * Wrap the Gutenberg block settings `edit` function.
 * Map block attributes to transient ones to support potential compatibility
 * changes to the Nunjucks template; example:

  settings.edit = withTransientAttributeMap(settings.edit, [
    {from: 'text', to: 'vf_lede_text'}
  ]);

 */
export const withTransientAttributeMap = (Edit, map) => {
  return props => {
    // props.transient = {
    //   ...(props.transient || {})
    // };
    const transient = {...(props.transient || {})};
    map.forEach(item => {
      if (props.attributes.hasOwnProperty(item.from)) {
        transient[item.to] = props.attributes[item.from];
      }
    });
    return Edit({...props, transient});
  };
};

/**
 * Wrap the Gutenberg block settings `edit` function.
 * Add `<InnerBlocks.Content />` content as a transient property.
 */
export const withTransientInnerBlocks = Edit => {
  return props => {
    const innerBlocks = select('core/block-editor').getBlocks(props.clientId);
    const transient = {
      ...(props.transient || {}),
      innerBlocks: []
    };
    innerBlocks.forEach(block =>
      transient.innerBlocks.push({
        name: block.name,
        attributes: {...block.attributes}
      })
    );
    return Edit({...props, transient});
  };
};
