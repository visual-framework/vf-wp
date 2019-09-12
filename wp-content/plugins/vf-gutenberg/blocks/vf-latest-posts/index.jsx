/**
 * VF-WP Latest Posts
 */

import {PluginEdit} from '../vf-plugin';
import {useDefaults} from '../hooks';

const {__} = wp.i18n;

const defaults = useDefaults();

export const settings = {
  ...defaults,
  name: 'vf/latest-posts',
  title: __('Latest Posts', 'vfwp'),
  category: 'vf/contenthub',
  keywords: [...defaults.keywords, __('Content Hub', 'vfwp')],
  edit: props => {
    return <PluginEdit {...props} />;
  }
};
