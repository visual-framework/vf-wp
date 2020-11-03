/**
Block Name: Cluster
*/
import React, {useCallback} from 'react';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {Button, PanelBody} from '@wordpress/components';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';

const defaults = useVFDefaults();

const ver = '1.0.0';

const settings = {
  ...defaults,
  name: 'vf/cluster',
  title: __('VF Cluster'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  attributes: {
    ...defaults.attributes,
    alignment: {
      type: 'string',
      default: 'center'
    },
    spacing: {
      type: 'string',
      default: 'small'
    },
    customSpacing: {
      type: 'number',
      default: 0
    }
  }
};

const Cluster = (props) => {
  const {alignment, spacing, customSpacing} = props.attributes;

  const classes = ['vf-cluster'];

  if (spacing === 'medium') {
    classes.push('vf-cluster--600');
  } else if (spacing === 'large') {
    classes.push('vf-cluster--800');
  } else if (spacing !== 'custom') {
    classes.push('vf-cluster--400');
  }

  const styles = {};

  styles['--vf-cluster__item--flex'] = '25% 1 0';

  if (spacing === 'custom') {
    styles['--vf-cluster-margin'] = `${customSpacing}px`;
  }

  if (alignment === 'start') {
    styles['--vf-cluster-alignment'] = 'flex-start';
  } else if (alignment === 'end') {
    styles['--vf-cluster-alignment'] = 'flex-end';
  } else if (alignment === 'stretch') {
    styles['--vf-cluster-alignment'] = 'stretch';
  } else {
    styles['--vf-cluster-alignment'] = 'center';
  }

  return (
    <div
      data-ver={props.isEdit ? ver : null}
      className={classes.join(' ')}
      style={styles}
    >
      <div className='vf-cluster__inner'>{props.children}</div>
    </div>
  );
};

settings.save = (props) => {
  return (
    <Cluster {...props}>
      <InnerBlocks.Content />
    </Cluster>
  );
};

settings.edit = (props) => {
  if (ver !== props.attributes.ver) {
    props.setAttributes({ver});
  }

  const {clientId} = props;
  const {alignment, spacing} = props.attributes;

  const onSpacing = useCallback(
    (name, value) => {
      props.setAttributes({[name]: value});
      if (value !== 'custom') {
        props.setAttributes({customSpacing: 0});
      }
    },
    [clientId]
  );

  // Inspector controls
  const fields = [
    {
      name: 'alignment',
      label: __('Alignment'),
      control: 'select',
      options: [
        {label: __('Stretch'), value: 'stretch'},
        {label: __('Start'), value: 'start'},
        {label: __('Center'), value: 'center'},
        {label: __('End'), value: 'end'}
      ]
    },
    {
      name: 'spacing',
      label: __('Spacing'),
      control: 'select',
      options: [
        {label: __('Small'), value: 'small'},
        {label: __('Medium'), value: 'medium'},
        {label: __('Large'), value: 'large'},
        {label: __('Custom'), value: 'custom'}
      ],
      onChange: onSpacing
    }
  ];

  if (spacing === 'custom') {
    fields.push({
      name: 'customSpacing',
      label: __('Custom spacing'),
      control: 'range',
      allowReset: true,
      min: 0,
      max: 100
    });
  }

  // Return inner blocks and inspector controls
  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields {...props} fields={fields} />
        </PanelBody>
      </InspectorControls>

      <Cluster {...props} isEdit>
        <InnerBlocks
          renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
        />
      </Cluster>
    </>
  );
};

export default settings;
