/**
 * Return default Gutenberg block settings for a VF block
 */
import {__} from '@wordpress/i18n';
import Icon from '../components/icon';

const useVFDefaults = () => ({
  icon: Icon,
  keywords: [__('VF'), __('Visual Framework'), __('EMBL')],
  attributes: {
    ver: {
      type: 'string'
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
