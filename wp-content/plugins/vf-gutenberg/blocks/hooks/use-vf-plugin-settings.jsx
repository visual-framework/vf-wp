/**
 * Generate new Gutenberg block settings from defaults.
 * Provide `VFBlockFields` using `useVFPlugin` configuration.
 */
import React, {Fragment} from 'react';
import {InspectorControls} from '@wordpress/block-editor';
import {ToggleControl, PanelBody} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import useVFDefaults from './use-vf-defaults';
import useVFPlugin from './use-vf-plugin';
import VFBlockFields from '../vf-block/block-fields';
import VFBlock from '../vf-block';

const useVFPluginSettings = (name, title) => {
  const defaults = useVFDefaults();
  const {fields, attrs} = useVFPlugin(name);
  const hasFields = Array.isArray(fields) && fields.length > 0;

  // Setup block attributes from the VF Plugin with defaults
  const attributes = {
    ...attrs,
    ...defaults.attributes
  };

  // Enable "edit" mode when fields exist
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
    description: __('Visual Framework (WordPress)'),
    keywords: [...defaults.keywords, __('EMBL Content Hub')],
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
        <Fragment>
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
        </Fragment>
      );
    }
  };
};

export default useVFPluginSettings;
