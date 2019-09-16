/**
 * VF-WP Plugin block hook
 */

import {VFBlock, VFBlockFields} from '../vf-plugin';
import {useDefaults, useVFPluginFields} from '../hooks';

const {__} = wp.i18n;

export default (name, title) => {
  const defaults = useDefaults();
  const {fields, attrs} = useVFPluginFields(name);
  const hasFields = fields.length > 0;
  const attributes = {
    ...attrs,
    ...defaults.attributes
  };
  if (hasFields) {
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
    keywords: [...defaults.keywords, __('Content Hub')],
    attributes: attributes,
    edit: props => {
      return (
        <VFBlock {...props} hasFooter={hasFields}>
          {hasFields && <VFBlockFields {...props} fields={fields} />}
        </VFBlock>
      );
    }
  };
};
