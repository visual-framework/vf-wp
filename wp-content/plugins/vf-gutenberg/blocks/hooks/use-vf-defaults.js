/**
 * Return default Gutenberg block settings for a VF block
 */
const {__} = wp.i18n;

const useVFDefaults = () => ({
  description: __('Visual Framework (WordPress)'),
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
