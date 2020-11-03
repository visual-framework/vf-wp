/**
Block Name: Grid
*/
import React, {useEffect} from 'react';
import {createBlock} from '@wordpress/blocks';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, Placeholder} from '@wordpress/components';
import {useDispatch, useSelect} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';
import {fromColumns} from './transforms/grid';

const defaults = useVFDefaults();

const ver = '1.1.0';

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

settings.save = (props) => {
  const {columns, placeholder} = props.attributes;
  if (placeholder === 1) {
    return null;
  }
  const className = `vf-grid | vf-grid__col-${columns}`;
  return (
    <div className={className}>
      <InnerBlocks.Content />
    </div>
  );
};

settings.edit = (props) => {
  if (ver !== props.attributes.ver) {
    props.setAttributes({ver});
  }

  const {clientId} = props;
  const {columns, placeholder} = props.attributes;

  // Turn on setup placeholder if no columns are defined
  useEffect(() => {
    if (columns === 0) {
      props.setAttributes({placeholder: 1});
    }
  }, [clientId]);

  const {replaceInnerBlocks} = useDispatch('core/block-editor');

  const {setColumns} = useSelect(
    (select) => {
      const {getBlocks} = select('core/block-editor');

      // Remove columns by merging their inner blocks
      const removeColumns = (newColumns) => {
        const innerColumns = getBlocks(clientId);
        const mergeBlocks = [];
        for (let i = newColumns - 1; i < innerColumns.length; i++) {
          mergeBlocks.push(...innerColumns[i].innerBlocks);
        }
        replaceInnerBlocks(
          innerColumns[newColumns - 1].clientId,
          mergeBlocks,
          false
        );
        replaceInnerBlocks(
          clientId,
          getBlocks(clientId).slice(0, newColumns),
          false
        );
      };

      // Append new columns
      const addColumns = (newColumns) => {
        const innerColumns = getBlocks(clientId);
        while (innerColumns.length < newColumns) {
          innerColumns.push(createBlock('vf/grid-column', {}, []));
        }
        replaceInnerBlocks(clientId, innerColumns, false);
      };

      const setColumns = (newColumns) => {
        const innerColumns = getBlocks(clientId);
        if (newColumns < innerColumns.length) {
          removeColumns(newColumns);
        }
        if (newColumns > innerColumns.length) {
          addColumns(newColumns);
        }
        props.setAttributes({columns: newColumns, placeholder: 0});
      };
      return {
        setColumns
      };
    },
    [clientId]
  );

  // Setup placeholder fields
  const fields = [
    {
      control: 'columns',
      min: MIN_COLUMNS,
      max: MAX_COLUMNS,
      value: columns,
      onChange: setColumns
    }
  ];

  // Return setup placeholder
  if (placeholder === 1) {
    return (
      <div className='vf-block vf-block--placeholder'>
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
    <>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields fields={fields} />
        </PanelBody>
      </InspectorControls>
      <div className='vf-block-grid' data-ver={ver} data-columns={columns}>
        <InnerBlocks
          allowedBlocks={['vf/grid-column']}
          template={Array(columns).fill(['vf/grid-column'])}
          templateLock='all'
        />
      </div>
    </>
  );
};

// Block transforms
settings.transforms = {
  from: [
    fromColumns('core/columns', 'vf/grid', MIN_COLUMNS, MAX_COLUMNS),
    fromColumns('vf/embl-grid', 'vf/grid', MIN_COLUMNS, MAX_COLUMNS)
  ]
};

export default settings;
