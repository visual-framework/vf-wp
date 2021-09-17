/**
Block Name: Grid Column
*/
import React, {useEffect, useCallback} from 'react';
import {
  InnerBlocks,
  InspectorControls,
  useBlockProps
} from '@wordpress/block-editor';
import {PanelBody, RangeControl} from '@wordpress/components';
import {useDispatch, useSelect} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';

const defaults = useVFDefaults();

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
  const blockProps = useBlockProps.save({ className: classes.join(' ') });
  return (
    <div {...blockProps}>
      <InnerBlocks.Content />
    </div>
  );
};

settings.edit = (props) => {
  const {clientId} = props;
  const {span} = props.attributes;

  const {updateBlockAttributes} = useDispatch('core/block-editor');

  const {hasChildBlocks, hasSpanSupport, rootClientId} = useSelect(
    (select) => {
      const {getBlockName, getBlockOrder, getBlockRootClientId} = select(
        'core/block-editor'
      );
      const rootClientId = getBlockRootClientId(clientId);
      const hasChildBlocks = getBlockOrder(clientId).length > 0;
      const hasSpanSupport = getBlockName(rootClientId) === 'vf/grid';

      return {
        rootClientId,
        hasChildBlocks,
        hasSpanSupport
      };
    },
    [clientId]
  );

  useEffect(() => {
    if (!hasSpanSupport && span !== 1) {
      props.setAttributes({span: 1});
    }
  }, [clientId]);

  const onSpanChange = useCallback(
    (value) => {
      if (span !== value) {
        props.setAttributes({span: value});
        updateBlockAttributes(rootClientId, {
          dirty: Date.now()
        });
      }
    },
    [span, clientId, rootClientId]
  );

  const classes = [];

  if (hasSpanSupport) {
    if (Number.isInteger(span) && span > 1) {
      classes.push(`vf-grid__col--span-${span}`);
    }
  }

  const blockProps = useBlockProps.save({ className: classes.join(' ') });

  return (
    <>
      {hasSpanSupport && (
        <InspectorControls>
          <PanelBody title={__('Advanced Settings')} initialOpen>
            <RangeControl
              label={__('Column span')}
              help={__('Columns may be merged to fit.')}
              value={Number.isInteger(span) ? span : 1}
              onChange={onSpanChange}
              allowReset={true}
              step={1}
              min={1}
              max={6}
            />
          </PanelBody>
        </InspectorControls>
      )}
      <div {...blockProps}>
        <InnerBlocks
          templateLock={false}
          renderAppender={
            hasChildBlocks
              ? undefined
              : () => <InnerBlocks.ButtonBlockAppender />
          }
        />
      </div>
    </>
  );
};

export default settings;
