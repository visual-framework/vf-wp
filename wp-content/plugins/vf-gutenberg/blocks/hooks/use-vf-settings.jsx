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
const {InspectorControls} = wp.editor;
const {ToggleControl, PanelBody} = wp.components;

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

  // Use the ACF defaults in the template
  attributes.defaults = {
    type: 'integer',
    default: 0
  };

  // Return the Gutenberg settings
  return {
    ...defaults,
    name: name,
    title: title,
    category: 'vf/wp',
    keywords: [...defaults.keywords, __('Content Hub')],
    attributes: attributes,
    edit: props => {
      const isDefaults = !!props.attributes.defaults;

      const DefaultsControl = () => {
        return (
          <ToggleControl
            label={__('Use defaults')}
            checked={isDefaults}
            onChange={value => props.setAttributes({defaults: value ? 1 : 0})}
            help={__('Disable custom settings and use the block defaults.')}
          />
        );
      };

      return (
        <>
          {hasFields && (
            <InspectorControls>
              <PanelBody title={__('Block Settings')}>
                <DefaultsControl />
              </PanelBody>
            </InspectorControls>
          )}
          <VFBlock {...props} hasFooter={hasFields}>
            {hasFields && (
              <VFBlockFields {...props} fields={isDefaults ? [] : fields}>
                {isDefaults && <DefaultsControl />}
              </VFBlockFields>
            )}
          </VFBlock>
        </>
      );
    }
  };
};

export default useVFSettings;
