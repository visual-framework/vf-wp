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
  let {attributes, fields, styles, allowedBlocks} = settings;

  const defaults = useVFDefaults();

  const hasBlocks = Array.isArray(allowedBlocks);
  const hasFields = Array.isArray(fields) && fields.length;
  const hasStyles = Array.isArray(styles) && styles.length;
  const hasRender = settings.hasRender !== false;

  // Setup block attributes
  attributes = {
    ...defaults.attributes,
    ...(attributes || {})
  };

  // Enable "render" attribute for templates
  if (hasRender) {
    attributes.render = {
      type: 'string',
      default: ''
    };
  }

  // Enable "edit" attribute for fields or inner blocks
  if (hasFields || hasBlocks) {
    attributes.mode = {
      type: 'string',
      default: hasRender ? 'view' : 'edit'
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
    return (
      <Fragment>
        <VFBlock {...props} ver={1} hasRender={hasRender} hasFooter={hasRender}>
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
