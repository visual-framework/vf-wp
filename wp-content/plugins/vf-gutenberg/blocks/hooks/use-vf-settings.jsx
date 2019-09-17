/**
 * Generate new Gutenberg block settings from defaults.
 * Provide `VFBlockFields` using `useVFPlugin` configuration.
 */
import React from 'react';
import useVFDefaults from './use-vf-defaults';
import useVFPlugin from './use-vf-plugin';
import VFBlockFields from '../vf-block/block-fields';
import VFBlock from '../vf-block';

const {__} = wp.i18n;

const useVFSettings = (name, title) => {
  const defaults = useVFDefaults();
  const {fields, attrs} = useVFPlugin(name);
  const hasFields = Array.isArray(fields) && fields.length > 0;

  // Setup block attributes from the VF Plugin with defaults
  const attributes = {
    ...attrs,
    ...defaults.attributes
  };

  // Only enable "edit" mode when fields exist
  if (hasFields) {
    attributes.mode = {
      type: 'string',
      default: 'view'
    };
  }

  // Return the Gutenberg settings
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

export default useVFSettings;
