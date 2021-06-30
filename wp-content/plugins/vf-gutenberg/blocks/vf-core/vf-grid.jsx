/**
Block Name: Grid
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
import ColumnsControl from '../components/columns-control';
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
  const {clientId} = props;
  const {dirty, columns, placeholder} = props.attributes;
  console.log('vf-grid edit')

  // Turn on setup placeholder if no columns are defined
  useEffect(() => {
    if (columns === 0) {
      props.setAttributes({placeholder: 1});
    }
  }, [clientId]);

  /* setAttribute() method must be used inside a useEffect Hook or a method. */
  useEffect(() => {
    const className = `vf-grid | vf-grid__col-${columns}`;
    const styles = {
      ["--block-columns"]: columns
    };
    console.log("setting attrs");
    props.setAttributes({ className: className });
    props.setAttributes({ style: styles });
  }, [columns]);

  const {replaceInnerBlocks} = useDispatch('core/block-editor');

  const {setColumns, updateColumns} = useSelect(
    (select) => {
      const {getBlocks, getBlockAttributes} = select('core/block-editor');

      // Return total number of columns accounting for spans
      const countSpans = (blocks) => {
        // console.log('countSpans')
        let count = 0;
        blocks.forEach((block) => {
          const {span} = block.attributes;
          if (Number.isInteger(span) && span > 0) {
            count += span;
          } else {
            count++;
          }
        });
        return count;
      };

      // Append new columns
      const addColumns = (maxSpans) => {
        const innerColumns = getBlocks(clientId);
        while (countSpans(innerColumns) < maxSpans) {
          innerColumns.push(createBlock('vf/grid-column', {}, []));
        }
        replaceInnerBlocks(clientId, innerColumns, false);
      };

      // Remove columns by merging their inner blocks
      const removeColumns = (maxSpans) => {
        let innerColumns = getBlocks(clientId);
        let mergeBlocks = [];
        while (innerColumns.length > 1 && countSpans(innerColumns) > maxSpans) {
          mergeBlocks = mergeBlocks.concat(innerColumns.pop().innerBlocks);
        }
        replaceInnerBlocks(
          innerColumns[innerColumns.length - 1].clientId,
          mergeBlocks.concat(innerColumns[innerColumns.length - 1].innerBlocks),
          false
        );
        replaceInnerBlocks(
          clientId,
          getBlocks(clientId).slice(0, innerColumns.length),
          false
        );
      };

      const setColumns = (newColumns) => {
        console.log('setColumns')
        props.setAttributes({columns: newColumns, placeholder: 0});
        const innerColumns = getBlocks(clientId);
        const count = countSpans(innerColumns);
        if (newColumns < count) {
          removeColumns(newColumns);
        }
        if (newColumns > count) {
          addColumns(newColumns);
        }
      };

      const updateColumns = () => {
        console.log('updateColumns')
        const {columns} = getBlockAttributes(clientId);
        setColumns(columns);
        props.setAttributes({dirty: 0});
      };

      return {
        setColumns,
        updateColumns
      };
    },
    [clientId]
  );

  useEffect(() => {
    if (dirty > 0) {
      updateColumns();
    }
  }, [dirty]);

  const GridControl = (props) => {
    console.log('GridControl')
    return (
      <ColumnsControl
        value={columns}
        min={MIN_COLUMNS}
        max={MAX_COLUMNS}
        onChange={useCallback((value) => setColumns(value))}
        {...props}
      />
    );
  };

  const blockProps = useBlockProps();

  // Return setup placeholder
  // props.setAttributes({className: `vf-block vf-block--placeholder`})
  if (placeholder === 1) {
    return (
      <div { ...blockProps }>
        <div className='vf-block vf-block--placeholder'>
          <Placeholder label={__('VF Grid')} icon={'admin-generic'}>
            <GridControl />
          </Placeholder>
        </div>
      </div>
    );
  }

  // Return inner blocks and inspector controls
  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Advanced Settings')} initialOpen>
          <GridControl
            help={__('Content may be reorganised when columns are reduced.')}
          />
        </PanelBody>
      </InspectorControls>
      <div> {/* without this wrapping div the editor blows up when certain elements are selected */}
        <div { ...blockProps }>
          {/* <InnerBlocks allowedBlocks={['vf/grid-column']} orientation='horizontal' templateLock='all' /> */}
          <InnerBlocks  />
        </div>
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
