/**
Block Name: Grid Column
*/
import React, {Fragment} from 'react';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, RangeControl} from '@wordpress/components';
import {withDispatch, withSelect} from '@wordpress/data';
import {compose} from '@wordpress/compose';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';

const defaults = useVFDefaults();

const ver = '1.0.0';

const settings = {
  ...defaults,
  name: 'vf/grid-column',
  title: __('Grid Column'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  parent: ['vf/grid', 'vf/embl-grid'],
  supports: {
    ...defaults.supports,
    inserter: false
  },
  attributes: {
    span: {
      type: 'integer',
      default: 1
    }
  }
};

const withCompose = compose(
  withSelect((select, ownProps) => {
    const {
      getBlockAttributes,
      getBlockName,
      getBlockOrder,
      getBlockRootClientId
    } = select('core/block-editor');

    // Get parent grid properties
    const gridClientId = getBlockRootClientId(ownProps.clientId);
    const gridAttrs = getBlockAttributes(gridClientId);

    // Set column block properties
    const hasChildBlocks = getBlockOrder(ownProps.clientId).length > 0;
    const isVFGrid = getBlockName(gridClientId) === 'vf/grid';

    return {
      hasChildBlocks,
      isVFGrid,
      isEMBLGrid: !isVFGrid,
      gridColumns: gridAttrs.columns
    };
  }),
  withDispatch((dispatch, ownProps, {select}) => {
    const setSpan = (prevSpan, newSpan) => {
      ownProps.setAttributes({span: newSpan});
    };
    return {
      setSpan
    };
  })
);

settings.save = props => {
  const {span} = props.attributes;
  const attrs = {};
  if (span > 1) {
    attrs.className = `vf-grid__col--span-${span}`;
  }
  return (
    <div {...attrs}>
      <InnerBlocks.Content />
    </div>
  );
};

settings.edit = withCompose(props => {
  const {gridColumns, hasChildBlocks, isVFGrid} = props;
  const {span} = props.attributes;

  // Ensure version is encoded in post content
  props.setAttributes({ver: props.ver || ver});

  // Setup inspector tools
  let inspector = false;
  if (isVFGrid && gridColumns > 2) {
    inspector = (
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <RangeControl
            label={__('Column span')}
            help={__('Experimental feature.')}
            value={span}
            onChange={value => props.setSpan(span, value)}
            min={1}
            max={gridColumns - 1}
          />
        </PanelBody>
      </InspectorControls>
    );
  }

  return (
    <Fragment>
      {inspector}
      <div className="vf-block-column">
        <InnerBlocks
          templateLock={false}
          renderAppender={
            hasChildBlocks
              ? undefined
              : () => <InnerBlocks.ButtonBlockAppender />
          }
        />
      </div>
    </Fragment>
  );
});

export default settings;
