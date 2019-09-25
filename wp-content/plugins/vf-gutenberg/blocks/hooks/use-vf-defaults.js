/**
 * Return default Gutenberg block settings for a VF block
 */
import {__} from '@wordpress/i18n';

const useVFDefaults = () => ({
  keywords: [__('VF'), __('Visual Framework')],
  attributes: {
    ver: {
      type: 'integer'
    }
  },
  supports: {
    align: false,
    className: false,
    customClassName: false,
    html: false
  },
  edit: () => null,
  save: () => null
});

export default useVFDefaults;
