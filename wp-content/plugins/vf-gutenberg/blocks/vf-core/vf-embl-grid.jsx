/**
Block Name: EMBL Grid
Based on `vf-grid.jsx`
*/
import React, {useCallback, useEffect} from 'react';
import {createBlock} from '@wordpress/blocks';
import {
  InnerBlocks,
  InspectorControls,
  useBlockProps
} from '@wordpress/block-editor';
import {PanelBody, Placeholder} from '@wordpress/components';
import {useDispatch, useSelect} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';
import {fromColumns} from './transforms/grid';

const defaults = useVFDefaults();

const ver = '1.1.0';

const MIN_COLUMNS = 2;
const MAX_COLUMNS = 4;

const settings = {
  ...defaults,
  name: 'vf/embl-grid',
  title: __('EMBL Grid'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  supports: {
    ...defaults.supports,
    lightBlockWrapper: true
  },
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

settings.save = (props) => {
  const {placeholder, sidebar, centered} = props.attributes;
  if (placeholder === 1) {
    return null;
  }
  let className = 'embl-grid';
  if (!!sidebar) {
    className = `${className} embl-grid--has-sidebar`;
  }
  if (!!centered) {
    className = `${className} embl-grid--has-centered-content`;
  }
  const blockProps = useBlockProps.save({ className });
  return (
    <div {...blockProps}>
      <InnerBlocks.Content />
    </div>
  );
};

settings.edit = (props) => {
  if (ver !== props.attributes.ver) {
    props.setAttributes({ver});
  }

  const {clientId} = props;
  const {columns, centered, sidebar, placeholder} = props.attributes;

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
        if (newColumns !== 3) {
          props.setAttributes({sidebar: 0, centered: 0});
        }
      };
      return {
        setColumns
      };
    },
    [clientId]
  );

  // Toggle attribute `onChange` callback
  const setToggle = useCallback((name, value) => {
    value = value ? 1 : 0;
    props.setAttributes({
      sidebar: 0,
      centered: 0,
      [name]: value
    });
    if (value) {
      setColumns(3);
    }
  });

  // Setup placeholder fields
  const fields = [
    {
      control: 'columns',
      min: MIN_COLUMNS,
      max: MAX_COLUMNS,
      value: columns,
      onChange: setColumns
    },
    {
      label: __('With Sidebar'),
      control: 'toggle',
      name: 'sidebar',
      onChange: setToggle
    },
    {
      label: __('Centered Content'),
      control: 'toggle',
      name: 'centered',
      onChange: setToggle
    }
  ];

  // Return setup placeholder
  if (placeholder === 1) {
    const blockProps = useBlockProps({ className: 'vf-block vf-block--placeholder' });
    return (
      <div {...blockProps}>
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

  let className = 'embl-grid';
  if (!!sidebar) {
    className = `${className} embl-grid--has-sidebar`;
  }
  if (!!centered) {
    className = `${className} embl-grid--has-centered-content`;
  }
  const blockProps = useBlockProps({ className });

  // Return inner blocks and inspector controls
  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields {...props} fields={fields} />
        </PanelBody>
      </InspectorControls>
      <div
        {...blockProps}
        data-ver={ver}
        data-embl={true}
        data-sidebar={sidebar}
        data-centered={centered}
      >
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
    fromColumns('core/columns', 'vf/embl-grid', MIN_COLUMNS, MAX_COLUMNS),
    fromColumns('vf/grid', 'vf/embl-grid', MIN_COLUMNS, MAX_COLUMNS)
  ]
};

export default settings;
