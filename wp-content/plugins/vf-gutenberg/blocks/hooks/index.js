/**
 * Misc hooks
 */
import hashsum from '../utils/hashsum';

/**
 * Return a unique hash of any object
 */
export const useHashsum = obj => hashsum(obj);

/**
 * Return a unique ID from hash of IDs array
 */
export const useUniqueId = ids => useHashsum(ids);

/**
 * Return a random ID using a random hash value
 */
export const useRandomId = seed =>
  useHashsum(seed + Math.floor(Math.random() * Date.now()));

/**
 * Return the block style name from the class attribute
 */
export const useStyleName = className => {
  const match = (className || '').match(/is-style-([^\s"]+)/);
  return match ? match[1] : '';
};

/**
 * Wrap block edit function to add transient property
 * assigned to custom attribute.
 */
export const withTransientAttribute = (attr, edit) => {
  return props => {
    props.transient = {
      ...(props.transient || {}),
      [attr]: useStyleName(props.className) || props.attributes[attr]
    };
    return edit(props);
  };
};
