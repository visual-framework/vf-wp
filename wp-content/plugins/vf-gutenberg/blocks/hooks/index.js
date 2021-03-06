/**
 * Misc hooks
 */
import {useState} from 'react';
import hashsum from '../utils/hashsum';

/**
 * Return a unique hash of any object
 */
export const useHashsum = obj => hashsum(obj);

/**
 * Return a unique ID for Gutenberg block instance
 */
const idStore = {};
export const useUniqueId = ({clientId, name}) => {
  const [uniqueId, setUniqueId] = useState(null);
  if (!uniqueId) {
    if (!idStore.hasOwnProperty(name)) {
      idStore[name] = 0;
    }
    idStore[name]++;
    setUniqueId(useHashsum([clientId, idStore[name]]));
  }
  return uniqueId;
};

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
