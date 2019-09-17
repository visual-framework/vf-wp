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
