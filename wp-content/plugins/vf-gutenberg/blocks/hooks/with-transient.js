import {useStyleName} from './';

/**
 * Wrap block edit function to add transient property
 * assigned to custom attribute.
 */
export const withTransientAttribute = (attr, edit) => {
  return props => {
    props.transient = {
      ...(props.transient || {}),
      [attr.key]: attr.value || props.attributes[attr.key]
    };
    return edit(props);
  };
};

/**
 * Wrap block edit function to add block style as transient property
 * Optionally use BEM notation
 */
export const withTransientStyle = (opts, edit) => {
  return props => {
    const isBEM = 'BEM' in opts;
    const style = useStyleName(props.className);
    const name = props.name.replace(/^vf\//, 'vf-');
    const value = isBEM ? `${name}--${style}` : style;
    const wrapped = withTransientAttribute({key: opts.key, value}, edit);
    return isBEM && !style ? edit(props) : wrapped(props);
  };
};

/**
 * Wrap the Gutenberg block settings `edit` function.
 * Map block attributes to transient ones to support potential compatibility
 * changes to the Nunjucks template.
 * code example:

settings.edit = withTransientAttributeMap(
  [{from: 'text', to: 'vf_lede_text'}],
  settings.edit
);

 */
export const withTransientAttributeMap = (map, edit) => {
  return props => {
    props.transient = {
      ...(props.transient || {})
    };
    map.forEach(item => {
      if (props.attributes.hasOwnProperty(item.from)) {
        props.transient[item.to] = props.attributes[item.from];
      }
    });
    return edit(props);
  };
};
