/**
 * VF Framework Grid
 */
import React, {Fragment} from 'react';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, Placeholder} from '@wordpress/components';
import {withDispatch} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import ColumnsControl from '../components/columns-control';

const defaults = useVFDefaults();

const ver = '1.0.0';

const settings = {
  ...defaults,
  name: 'vf/grid',
  title: __('Grid'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  attributes: {
    ...defaults.attributes,
    wizard: {
      type: 'integer',
      default: 0
    },
    columns: {
      type: 'integer',
      default: 0
    }
  }
};

settings.save = props => {
  if (props.attributes.wizard === 1) {
    return null;
  }
  const {columns} = props.attributes;
  const className = `vf-grid | vf-grid__col-${columns}`;
  return (
    <div className={className}>
      <InnerBlocks.Content />
    </div>
  );
};

const withGridDispatch = Edit => {
  return withDispatch((dispatch, ownProps, {select}) => {
    const {getBlocks} = select('core/block-editor');
    const {replaceInnerBlocks} = dispatch('core/block-editor');

    // `columns` attribute `onChange` callback
    const setColumns = newColumns => {
      const prevColumns = ownProps.attributes.columns;
      // merge inner blocks when number of columns is reduced
      if (newColumns < prevColumns) {
        const columnBlocks = getBlocks(ownProps.clientId);
        const mergeBlocks = [];
        for (let i = newColumns - 1; i < prevColumns; i++) {
          mergeBlocks.push(...columnBlocks[i].innerBlocks);
        }
        replaceInnerBlocks(
          columnBlocks[newColumns - 1].clientId,
          mergeBlocks,
          false
        );
      }

      // update block attributes
      ownProps.setAttributes({columns: newColumns, wizard: 0});
    };
    return {
      setColumns
    };
  })(Edit);
};

settings.edit = withGridDispatch(props => {
  const {columns, wizard} = props.attributes;

  // ensure version is encoded in post content
  props.setAttributes({ver});

  // turn on setup wizard if no columns are defined
  if (columns === 0) {
    props.setAttributes({wizard: 1});
  }

  const controlProps = {
    columns,
    min: 1,
    max: 6,
    onChange: props.setColumns
  };

  // return setup wizard
  if (wizard === 1) {
    return (
      <Placeholder label={__('Grid Setup')} icon={'admin-generic'}>
        <ColumnsControl {...controlProps} />
      </Placeholder>
    );
  }

  // return inner blocks and inspector controls
  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <ColumnsControl {...controlProps} isInspector />
        </PanelBody>
      </InspectorControls>
      <div className={'vf-block-grid'} data-ver={ver} data-columns={columns}>
        <InnerBlocks
          allowedBlocks={['vf/grid-column']}
          template={Array(columns).fill(['vf/grid-column'])}
          templateLock="all"
        />
      </div>
    </Fragment>
  );
});

export default settings;
