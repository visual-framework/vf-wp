/**
 * VF Framework Grid
 */
import React from 'react';
import {InnerBlocks} from '@wordpress/block-editor';
import {Button, ButtonGroup, Placeholder} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';

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

settings.edit = props => {
  // ensure version is encoded in post content
  props.setAttributes({ver});

  // turn on setup wizard if no columns are defined
  const {columns, wizard} = props.attributes;
  if (columns === 0) {
    props.setAttributes({wizard: 1 /*, columns: 2*/});
  }

  // callback to set number of columns
  const setColumns = count => {
    props.setAttributes({columns: count, wizard: 0});
  };

  if (wizard === 1) {
    return (
      <Placeholder
        label={__('Grid Setup')}
        icon={'admin-generic'}
        instructions={__('Select the number of columns for the grid')}>
        <ButtonGroup aria-label={__('Number of Columns')}>
          {Array(6)
            .fill()
            .map((x, i) => (
              <Button key={i} isLarge onClick={() => setColumns(i + 1)}>
                {i + 1}
              </Button>
            ))}
        </ButtonGroup>
      </Placeholder>
    );
  }

  const gridAttrs = {
    className: 'vf-block-grid',
    'data-ver': ver,
    'data-columns': columns
  };

  return (
    <div {...gridAttrs}>
      <InnerBlocks
        allowedBlocks={['vf/grid-column']}
        template={Array(columns).fill(['vf/grid-column'])}
        templateLock="all"
      />
    </div>
  );
};

export default settings;
