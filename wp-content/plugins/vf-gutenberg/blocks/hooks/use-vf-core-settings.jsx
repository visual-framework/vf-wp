/**
 * Generate new Gutenberg block settings from defaults.
 * Provide `VFBlockFields` configuration.
 */
import React, {Fragment} from 'react';
import {__} from '@wordpress/i18n';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody} from '@wordpress/components';
import useVFDefaults from './use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';
import VFBlock from '../vf-block';

const useVFCoreSettings = settings => {
  const defaults = useVFDefaults();

  // get block settings
  let {attributes, fields, styles, allowedBlocks} = settings;

  // block options
  const hasBlocks = Array.isArray(allowedBlocks);
  const hasFields = Array.isArray(fields) && fields.length;
  const hasStyles = Array.isArray(styles) && styles.length;

  // Assume true unless specifically opted out
  const isRenderable = settings.isRenderable !== false;

  // Setup block attributes
  attributes = {
    ...defaults.attributes,
    ...(attributes || {})
  };

  // Enable `render` attribute for Nunjucks template
  if (isRenderable) {
    attributes.render = {
      type: 'string',
      default: ''
    };
  }

  // Enable `mode` attribute
  if (hasFields || hasBlocks) {
    attributes.mode = {
      type: 'string',
      default: 'edit'
    };
  }

  // Sort the fields into their locations
  const blockFields = [];
  const inspectorFields = [];
  if (hasFields) {
    fields.forEach(field => {
      if (field.inspector) {
        inspectorFields.push(field);
      } else {
        blockFields.push(field);
      }
    });
  }

  const Save = () => {
    return hasBlocks ? (
      <Fragment>
        <InnerBlocks.Content />
      </Fragment>
    ) : null;
  };

  let Edit = props => {
    const ver = settings.ver;
    const isEditable = !!(props.clientId && 'mode' in attributes);
    const isEditing = props.attributes.mode === 'edit';
    return (
      <Fragment>
        <VFBlock
          {...props}
          ver={ver}
          isRenderable={isRenderable}
          isEditable={isEditable}
          isEditing={isEditing}>
          {!!blockFields.length && (
            <VFBlockFields {...props} fields={blockFields} />
          )}
          {hasBlocks && <InnerBlocks allowedBlocks={allowedBlocks} />}
        </VFBlock>
        {!!inspectorFields.length && (
          <InspectorControls>
            <PanelBody title={__('Settings')} initialOpen={false}>
              <VFBlockFields {...props} fields={inspectorFields} />
            </PanelBody>
          </InspectorControls>
        )}
      </Fragment>
    );
  };

  // Wrap higher-order components
  if (Array.isArray(settings.withHOC)) {
    settings.withHOC.forEach(([HoC, ...args]) => (Edit = HoC(Edit, ...args)));
  }

  // Return the Gutenberg settings
  return {
    ...defaults,
    name: settings.name,
    title: settings.title,
    category: 'vf/core',
    parent: settings.parent || null,
    description: __('Visual Framework (core)'),
    keywords: [...defaults.keywords],
    attributes: attributes,
    styles: hasStyles ? styles : [],
    supports: {
      ...defaults.supports,
      customClassName: hasStyles
    },
    edit: Edit,
    save: Save
  };
};

export default useVFCoreSettings;
