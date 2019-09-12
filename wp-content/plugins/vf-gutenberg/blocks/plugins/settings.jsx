/**
 * VF-WP Plugin block hook
 */

import {PluginEdit, PluginEditFields} from '../vf-plugin';
import {useDefaults, useVFPluginFields} from '../hooks';

const {__} = wp.i18n;

export default (name, title) => {
  const defaults = useDefaults();
  const {fields, attrs} = useVFPluginFields(name);
  const attributes = {
    ...attrs,
    ...defaults.attributes
  };
  if (fields.length) {
    attributes.mode = {
      type: 'string',
      default: 'view'
    };
  }
  return {
    ...defaults,
    name: name,
    title: title,
    category: 'vf/wp',
    keywords: [...defaults.keywords, __('Content Hub', 'vfwp')],
    attributes: attributes,
    edit: props => {
      if (fields.length) {
        return (
          <PluginEdit {...props}>
            <PluginEditFields {...props} fields={fields} />
          </PluginEdit>
        );
      } else {
        return <PluginEdit {...props} />;
      }
    }
  };
};
