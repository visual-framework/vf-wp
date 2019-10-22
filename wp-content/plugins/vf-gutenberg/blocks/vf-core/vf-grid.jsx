/**
Block Name: Grid
*/
import React, {Fragment} from 'react';
import {createBlock} from '@wordpress/blocks';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, Placeholder} from '@wordpress/components';
import {withDispatch} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';

// Return a transform object for grids using `vf/grid-column`
export const fromColumns = (fromBlock, toBlock, min, max) => {
  return {
    type: 'block',
    blocks: [fromBlock],
    transform: (attributes, innerBlocks) => {
      // Map column props
      let innerProps = innerBlocks.map(block => ({
        attributes: {...block.attributes},
        innerBlocks: [...block.innerBlocks]
      }));
      // Fill empty props to match min number of columns
      while (innerProps.length < min) {
        innerProps.push({});
      }
      // Merge end props to match max number of columns
      while (innerProps.length > max) {
        const mergeProps = innerProps.pop();
        innerProps[innerProps.length - 1].innerBlocks.push(
          ...mergeProps.innerBlocks
        );
      }
      // Return new grid block with inner columns
      return createBlock(
        toBlock,
        {columns: innerProps.length},
        innerProps.map(props =>
          createBlock(
            'vf/grid-column',
            props.attributes || {},
            props.innerBlocks || []
          )
        )
      );
    }
  };
};

const defaults = useVFDefaults();

const ver = '1.0.0';

const MIN_COLUMNS = 1;
const MAX_COLUMNS = 6;

const settings = {
  ...defaults,
  name: 'vf/grid',
  title: __('VF Grid'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  attributes: {
    ...defaults.attributes,
    placeholder: {
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
  if (props.attributes.placeholder === 1) {
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
      // Merge inner blocks when number of columns is reduced
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

      // Update block attributes
      ownProps.setAttributes({columns: newColumns, placeholder: 0});
    };
    return {
      setColumns
    };
  })(Edit);
};

settings.edit = withGridDispatch(props => {
  const {columns, placeholder} = props.attributes;

  // Ensure version is encoded in post content
  props.setAttributes({ver});

  // Turn on setup placeholder if no columns are defined
  if (columns === 0) {
    props.setAttributes({placeholder: 1});
  }

  // Setup placeholder fields
  const fields = [
    {
      control: 'columns',
      min: MIN_COLUMNS,
      max: MAX_COLUMNS,
      value: columns,
      onChange: props.setColumns
    }
  ];

  // Return setup placeholder
  if (placeholder === 1) {
    return (
      <div className={`vf-block vf-block--placeholder ${props.className}`}>
        <Placeholder label={__('VF Grid')} icon={'admin-generic'}>
          <VFBlockFields fields={fields} />
        </Placeholder>
      </div>
    );
  }

  // Amend fields for inspector
  fields[0].help = __('Content may be reorganised when columns are reduced.');

  // Return inner blocks and inspector controls
  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields fields={fields} />
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

// Block transforms
settings.transforms = {
  from: [
    fromColumns('core/columns', 'vf/grid', MIN_COLUMNS, MAX_COLUMNS),
    fromColumns('vf/embl-grid', 'vf/grid', MIN_COLUMNS, MAX_COLUMNS)
  ]
};

export default settings;
