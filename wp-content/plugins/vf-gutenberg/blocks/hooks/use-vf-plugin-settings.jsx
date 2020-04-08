/**
 * Generate new Gutenberg block settings from defaults.
 * Provide `VFBlockFields` using `useVFPlugin` configuration.
 */
import React, {Fragment} from 'react';
import {InspectorControls} from '@wordpress/block-editor';
import {ToggleControl, PanelBody} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import {withTransientACF} from '../hooks/with-transient';
import useVFDefaults from './use-vf-defaults';
import useVFPlugin from './use-vf-plugin';
import VFBlockFields from '../vf-block/block-fields';
import VFBlock from '../vf-block';

const useVFPluginSettings = settings => {
  const defaults = useVFDefaults();

  // get block settings
  let {attributes, fields, supports} = useVFPlugin(settings.name);

  // block options
  const hasFields = !!(Array.isArray(fields) && fields.length);

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

  let Edit = props => {
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
          isPlugin
          isRenderable
          isEditable={hasFields}
          isEditing={isEditing}>
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

  // Wrap higher-order components
  if (settings.name === 'vf/plugin') {
    Edit = withTransientACF(Edit);
  }

  // Return the Gutenberg settings
  return {
    ...defaults,
    name: settings.name,
    title: settings.title,
    category: settings.category,
    description: __('EMBL – Content Hub'),
    keywords: [...defaults.keywords, __('EMBL – Content Hub')],
    attributes: attributes,
    supports: supports,
    edit: Edit
  };
};

export default useVFPluginSettings;
