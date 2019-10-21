/**
Block Name: Grid
*/
import React, {Fragment} from 'react';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, Placeholder} from '@wordpress/components';
import {withDispatch} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';

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
      min: 1,
      max: 6,
      value: columns,
      onChange: props.setColumns
    }
  ];

  // Return setup placeholder
  if (placeholder === 1) {
    return (
      <div className={`vf-block vf-block--placeholder ${props.className}`}>
        <Placeholder label={__('Grid')} icon={'admin-generic'}>
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

export default settings;
