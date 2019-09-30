/**
 * Generate new Gutenberg block settings from defaults.
 * Provide `VFBlockFields` configuration.
 */
import React, {Fragment} from 'react';
import {__} from '@wordpress/i18n';
import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody} from '@wordpress/components';
import useVFDefaults from './use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';
import VFBlock from '../vf-block';

const useVFCoreSettings = settings => {
  let {name, title, attributes, fields} = settings;

  const defaults = useVFDefaults();
  const hasFields = Array.isArray(fields) && fields.length > 0;

  // Setup block attributes
  attributes = {
    ...defaults.attributes,
    ...attributes,
    render: {
      type: 'string',
      default: ''
    }
  };

  // Enable "edit" mode when fields exist
  if (hasFields) {
    attributes.mode = {
      type: 'string',
      default: 'view'
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

  // Return the Gutenberg settings
  return {
    ...defaults,
    name: name,
    title: title,
    category: 'vf/core',
    description: __('Visual Framework (core)'),
    keywords: [...defaults.keywords],
    attributes: attributes,
    supports: {
      ...defaults.supports
    },
    edit: props => {
      return (
        <Fragment>
          <VFBlock {...props} ver={1} hasFooter>
            <VFBlockFields {...props} fields={blockFields} />
          </VFBlock>
          {inspectorFields.length && (
            <InspectorControls>
              <PanelBody title={__('Settings')} initialOpen={false}>
                <VFBlockFields {...props} fields={inspectorFields} />
              </PanelBody>
            </InspectorControls>
          )}
        </Fragment>
      );
    }
  };
};

export default useVFCoreSettings;
