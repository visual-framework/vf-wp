/**
 * VF-WP Example
 */

const {__} = wp.i18n;

import {PluginEdit, PluginEditFields} from '../vf-plugin';
import {useVFPluginFields} from '../hooks';

const {fields, attributes} = useVFPluginFields('vf/example');

export const settings = {
  name: 'vf/example',
  title: __('Example', 'vfwp'),
  category: 'vf/contenthub',
  keywords: [
    __('VF', 'vfwp'),
    __('Visual Framework', 'vfwp'),
    __('Content Hub', 'vfwp')
  ],
  attributes: {
    ...attributes,
    ver: {
      type: 'integer'
    },
    mode: {
      type: 'string',
      default: 'view'
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
    return (
      <PluginEdit {...props}>
        <PluginEditFields {...props} fields={fields} />
      </PluginEdit>
    );
  }
};
