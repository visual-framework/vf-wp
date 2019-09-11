/**
 * VF-WP Latest Posts
 */

const {__} = wp.i18n;

import PluginEdit from '../vf-plugin';

export const settings = {
  name: 'vf/latest-posts',
  title: __('Latest Posts', 'vfwp'),
  category: 'vf/contenthub',
  keywords: [
    __('VF', 'vfwp'),
    __('Visual Framework', 'vfwp'),
    __('Content Hub', 'vfwp')
  ],
  attributes: {
    ver: {
      type: 'integer'
    },
    mode: {
      type: 'string'
    }
  },
  supports: {
    align: false,
    html: false,
    className: false,
    customClassName: false
  },
  save: () => null,
  edit: props => {
    return <PluginEdit {...props}></PluginEdit>;
  }
};
