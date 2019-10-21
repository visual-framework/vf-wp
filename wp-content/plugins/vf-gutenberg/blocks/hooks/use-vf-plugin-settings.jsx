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

const useVFPluginSettings = settings => {
  const defaults = useVFDefaults();

  // get block settings
  let {attributes, fields} = useVFPlugin(settings.name);

  // block options
  const hasFields = Array.isArray(fields) && fields.length;

  // Setup block attributes
  attributes = {
    ...defaults.attributes,
    ...(attributes || {})
  };

  // Enable `mode` attribute
  if (hasFields) {
    attributes.mode = {
      type: 'string',
      default: 'view'
    };
  }

  // Enable `defaults` attribute to use ACF template defaults
  attributes.defaults = {
    type: 'integer',
    default: 0
  };

  const Edit = props => {
    const isDefaults = !!props.attributes.defaults;
    const isEditing = props.attributes.mode === 'edit';

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
        <VFBlock
          {...props}
          isEditable={hasFields}
          isEditing={isEditing}
          isRenderable>
          {hasFields && (
            <VFBlockFields {...props} fields={isDefaults ? [] : fields}>
              {isDefaults && <DefaultsControl />}
            </VFBlockFields>
          )}
        </VFBlock>
        {hasFields && (
          <InspectorControls>
            <PanelBody title={__('Block Settings')}>
              <DefaultsControl />
            </PanelBody>
          </InspectorControls>
        )}
      </Fragment>
    );
  };

  // Return the Gutenberg settings
  return {
    ...defaults,
    name: settings.name,
    title: settings.title,
    category: 'vf/wp',
    description: __('Visual Framework (WordPress)'),
    keywords: [...defaults.keywords, __('EMBL Content Hub')],
    attributes: attributes,
    edit: Edit
  };
};

export default useVFPluginSettings;
