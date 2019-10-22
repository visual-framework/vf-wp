/**
Block Name: Grid
Based on `vf-grid.jsx`
*/
import React, {Fragment} from 'react';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, Placeholder} from '@wordpress/components';
import {withDispatch} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';
import {fromColumns, fromGrid} from './vf-grid';

const defaults = useVFDefaults();

const ver = '1.0.0';

const MIN_COLUMNS = 2;
const MAX_COLUMNS = 4;

const settings = {
  ...defaults,
  name: 'vf/embl-grid',
  title: __('EMBL Grid'),
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
    },
    sidebar: {
      type: 'integer',
      default: 0
    },
    centered: {
      type: 'integer',
      default: 0
    }
  }
};

settings.save = props => {
  const {placeholder, sidebar, centered} = props.attributes;
  if (placeholder === 1) {
    return null;
  }
  let className = 'embl-grid';
  if (!!sidebar) {
    className += ' embl-grid--has-sidebar';
  }
  if (!!centered) {
    className += ' embl-grid--has-centered-content';
  }
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
      ownProps.setAttributes({
        placeholder: 0,
        columns: newColumns
      });
      if (newColumns !== 3) {
        ownProps.setAttributes({sidebar: 0, centered: 0});
      }
    };

    // Toggle attribute `onChange` callback
    const setToggle = (name, value) => {
      value = value ? 1 : 0;
      ownProps.setAttributes({
        sidebar: 0,
        centered: 0,
        [name]: value
      });
      if (value) {
        setColumns(3);
      }
    };

    return {
      setColumns,
      setToggle
    };
  })(Edit);
};

settings.edit = withGridDispatch(props => {
  const {columns, sidebar, centered, placeholder} = props.attributes;

  // ensure version is encoded in post content
  props.setAttributes({ver});

  // turn on setup placeholder if no columns are defined
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
    },
    {
      label: __('With Sidebar'),
      control: 'toggle',
      name: 'sidebar',
      onChange: props.setToggle
    },
    {
      label: __('Centered Content'),
      control: 'toggle',
      name: 'centered',
      onChange: props.setToggle
    }
  ];

  // Return setup placeholder
  if (placeholder === 1) {
    return (
      <div className={`vf-block vf-block--placeholder ${props.className}`}>
        <Placeholder label={__('EMBL Grid')} icon={'admin-generic'}>
          <VFBlockFields {...props} fields={fields} />
        </Placeholder>
      </div>
    );
  }

  // Amend fields for inspector
  fields[0].help = __('Content may be reorganised when columns are reduced.');
  fields[1].help = __('3 column only.');
  fields[2].help = fields[1].help;

  // Return inner blocks and inspector controls
  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields {...props} fields={fields} />
        </PanelBody>
      </InspectorControls>
      <div
        className={'vf-block-grid'}
        data-ver={ver}
        data-embl={true}
        data-sidebar={sidebar}
        data-centered={centered}>
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
    fromColumns('vf/embl-grid', MIN_COLUMNS, MAX_COLUMNS),
    fromGrid('vf/grid', 'vf/embl-grid', MIN_COLUMNS, MAX_COLUMNS)
  ]
};

export default settings;
