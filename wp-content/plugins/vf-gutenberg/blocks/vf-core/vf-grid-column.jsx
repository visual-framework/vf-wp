/**
Block Name: Grid Column
*/
import React from 'react';
import {
  InnerBlocks,
  InspectorControls,
  // TODO: replace with `useBlockProps` hook in WP 5.6
  __experimentalBlock as ExperimentalBlock
} from '@wordpress/block-editor';
import {PanelBody} from '@wordpress/components';
import {useSelect} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';

const defaults = useVFDefaults();

const ver = '1.1.0';

const settings = {
  ...defaults,
  name: 'vf/grid-column',
  title: __('Grid Column'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  parent: ['vf/grid', 'vf/embl-grid'],
  supports: {
    ...defaults.supports,
    inserter: false,
    lightBlockWrapper: true
  },
  attributes: {
    ...defaults.attributes,
    span: {
      type: 'integer',
      default: 1
    }
  }
};

settings.save = (props) => {
  const {span} = props.attributes;
  const classes = [];
  if (Number.isInteger(span) && span > 1) {
    classes.push(`vf-grid__col--span-${span}`);
  }
  const rootAttr = {};
  if (classes.length) {
    rootAttr.className = classes.join(' ');
  }
  return (
    <div {...rootAttr}>
      <InnerBlocks.Content />
    </div>
  );
};

settings.edit = (props) => {
  if (ver !== props.attributes.ver) {
    props.setAttributes({ver});
  }

  const {clientId} = props;
  const {span} = props.attributes;

  const {hasSpanSupport, hasChildBlocks} = useSelect((select) => {
    const {getBlockName, getBlockOrder, getBlockRootClientId} = select(
      'core/block-editor'
    );
    const rootClientId = getBlockRootClientId(clientId);
    const hasChildBlocks = getBlockOrder(clientId).length > 0;
    const hasSpanSupport = getBlockName(rootClientId) === 'vf/grid';
    return {
      hasChildBlocks,
      hasSpanSupport
    };
  });

  const classes = [];

  const fields = [];

  if (hasSpanSupport) {
    fields.push({
      name: 'span',
      label: __('Column span'),
      control: 'range',
      allowReset: true,
      min: 1,
      max: 6
    });
    if (Number.isInteger(span) && span > 1) {
      classes.push(`vf-grid__col--span-${span}`);
    }
  } else {
    if (span !== 1) {
      props.setAttributes({span: 1});
    }
  }

  const hasFields = fields.length > 0;

  const rootAttr = {};
  if (classes.length) {
    rootAttr.className = classes.join(' ');
  }

  return (
    <>
      {hasFields && (
        <InspectorControls>
          <PanelBody title={__('Settings')} initialOpen>
            <VFBlockFields {...props} fields={fields} />
          </PanelBody>
        </InspectorControls>
      )}
      <ExperimentalBlock.div {...rootAttr}>
        <InnerBlocks
          templateLock={false}
          renderAppender={
            hasChildBlocks
              ? undefined
              : () => <InnerBlocks.ButtonBlockAppender />
          }
        />
      </ExperimentalBlock.div>
    </>
  );
};

export default settings;
