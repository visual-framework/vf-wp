/**
Block Name: Grid
*/
import React, {useEffect} from 'react';
import {createBlock} from '@wordpress/blocks';
import {
  InnerBlocks,
  InspectorControls,
  // TODO: replace with `useBlockProps` hook in WP 5.6
  __experimentalBlock as ExperimentalBlock
} from '@wordpress/block-editor';
import {PanelBody, Placeholder} from '@wordpress/components';
import {useDispatch, useSelect} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';
import {fromColumns} from './transforms/grid';

const defaults = useVFDefaults();

const MIN_COLUMNS = 1;
const MAX_COLUMNS = 6;

const settings = {
  ...defaults,
  name: 'vf/grid',
  title: __('VF Grid'),
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
    dirty: {
      type: 'string',
      default: ''
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
  const {clientId} = props;
  const {dirty, columns, placeholder} = props.attributes;

  // Turn on setup placeholder if no columns are defined
  useEffect(() => {
    if (columns === 0) {
      props.setAttributes({placeholder: 1});
    }
  }, [clientId]);

  const {replaceInnerBlocks} = useDispatch('core/block-editor');

  const {setColumns, updateColumns} = useSelect(
    (select) => {
      const {getBlocks} = select('core/block-editor');

      // Return total number of columns accounting for spans
      const countSpans = () => {
        let spans = 0;
        getBlocks(clientId).forEach((block) => {
          const {span} = block.attributes;
          if (Number.isInteger(span) && span > 0) {
            spans += span;
          } else {
            spans++;
          }
        });
        return spans;
      };

      const updateColumns = () => {
        const innerColumns = getBlocks(clientId);
        console.log(innerColumns, innerColumns.length, countSpans());
        props.setAttributes({dirty: ''});
      };

      // Remove columns by merging their inner blocks
      const removeColumns = (maxSpans) => {
        let spans = countSpans();
        while (spans > maxSpans) {
          let innerColumns = getBlocks(clientId);
          let count = innerColumns.length;
          console.log(spans, count);
          if (count < 2) {
            break;
          }
          replaceInnerBlocks(
            innerColumns[count - 2].clientId,
            [
              ...innerColumns[count - 2].innerBlocks,
              ...innerColumns[count - 1].innerBlocks
            ],
            false
          );
          replaceInnerBlocks(clientId, innerColumns.slice(0, count - 1), false);
          spans = countSpans();
        }

        // for (let i = newColumns - 1; i < count; i++) {
        //   mergeBlocks.push(...innerColumns[i].innerBlocks);
        // }
        // replaceInnerBlocks(
        //   innerColumns[newColumns - 1].clientId,
        //   mergeBlocks,
        //   false
        // );
        // replaceInnerBlocks(
        //   clientId,
        //   getBlocks(clientId).slice(0, newColumns),
        //   false
        // );
      };

      // Append new columns
      const addColumns = (maxSpans) => {
        const innerColumns = getBlocks(clientId);
        let count = countSpans();
        while (count++ < maxSpans) {
          innerColumns.push(createBlock('vf/grid-column', {}, []));
        }
        replaceInnerBlocks(clientId, innerColumns, false);
      };

      const setColumns = (newColumns) => {
        // const innerColumns = getBlocks(clientId);
        const count = countSpans();
        if (newColumns < count) {
          removeColumns(newColumns);
        }
        if (newColumns > count) {
          addColumns(newColumns);
        }
        props.setAttributes({columns: newColumns, placeholder: 0});
      };
      return {
        setColumns,
        updateColumns
      };
    },
    [clientId]
  );

  useEffect(() => {
    if (dirty !== '') {
      updateColumns();
    }
  }, [dirty]);

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
      <ExperimentalBlock.div className='vf-block vf-block--placeholder'>
        <Placeholder label={__('VF Grid')} icon={'admin-generic'}>
          <VFBlockFields fields={fields} />
        </Placeholder>
      </ExperimentalBlock.div>
    );
  }

  // Amend fields for inspector
  fields[0].help = __('Content may be reorganised when columns are reduced.');

  const className = `vf-grid | vf-grid__col-${columns}`;

  // Return inner blocks and inspector controls
  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields fields={fields} />
        </PanelBody>
      </InspectorControls>
      <ExperimentalBlock.div className={className} data-columns={columns}>
        <InnerBlocks
          allowedBlocks={['vf/grid-column']}
          template={Array(columns).fill(['vf/grid-column'])}
          templateLock='all'
        />
      </ExperimentalBlock.div>
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
