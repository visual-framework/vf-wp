/**
 * DEPRECATED
 * These plugins exist as a legacy fallback
 * Only new ACF versions are visible to the editor
 *
 * Generate new Gutenberg block settings from defaults.
 * Provide `VFBlockFields` using `useVFPlugin` configuration.
 */
import React, {Fragment} from 'react';
import {InspectorControls} from '@wordpress/block-editor';
import {ToggleControl, PanelBody} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import {
  withTransientAttribute,
  withTransientACF
} from '../hooks/with-transient';
import useVFDefaults from './use-vf-defaults';
import useVFPlugin from './use-vf-plugin';
import VFBlockFields from '../vf-block/block-fields';
import VFBlock from '../vf-block';

const useVFPluginSettings = settings => {
  const defaults = useVFDefaults();

  // get block settings
  let {attributes, fields, supports, preview} = useVFPlugin(settings.name);

  // block options
  const hasFields = !!(Array.isArray(fields) && fields.length);

  // Setup block attributes
  attributes = {
    ...defaults.attributes,
    ...(attributes || {})
  };

  // Avoid future usage
  supports = {
    ...supports,
    inserter: false,
    reusable: false
  }

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

  // Add transient attribute for iframe preview if set
  Edit = withTransientAttribute(Edit, {
    key: 'preview',
    value: preview ? preview : false
  });

  // Return the Gutenberg settings
  return {
    ...defaults,
    name: settings.name,
    title: settings.title,
    category: settings.category,
    description: '',
    keywords: [...defaults.keywords],
    attributes: attributes,
    supports: supports,
    edit: Edit
  };
};

export default useVFPluginSettings;
