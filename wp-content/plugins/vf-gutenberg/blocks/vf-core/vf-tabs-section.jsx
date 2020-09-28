/**
Block Name: Grid Column
*/
import React, {Fragment} from 'react';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody} from '@wordpress/components';
import {useSelect} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';

const defaults = useVFDefaults();

const ver = '1.0.0';

const settings = {
  ...defaults,
  name: 'vf/tabs-section',
  title: __('VF Tab Section'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  parent: ['vf/tabs'],
  supports: {
    ...defaults.supports,
    inserter: false
  },
  attributes: {
    ...defaults.attributes,
    order: {
      type: 'integer',
      default: 0
    },
    label: {
      type: 'string',
      default: ''
    },
    unlabelled: {
      type: 'boolean',
      default: 0
    }
  }
};

settings.save = (props) => {
  const {order, label, unlabelled} = props.attributes;
  const attr = {
    className: `vf-tabs__section`
  };
  if (order > 0) {
    attr.id = `vf-tabs__section--${order}`;
  }
  return (
    <section {...attr}>
      <h2 className={unlabelled ? 'vf-u-sr-only' : ''}>{label}</h2>
      <InnerBlocks.Content />
    </section>
  );
};

// const withTabsProps = (Edit) => {
//   return (props) => {

//     // const {getBlockOrder} = select('core/block-editor');
//     // const hasChildBlocks = getBlockOrder(props.clientId).length > 0;
//     return Edit({...props, hasChildBlocks, rootClientId});
//   };
// };

settings.edit = (props) => {
  if (ver !== props.attributes.ver) {
    props.setAttributes({ver});
  }

  const {clientId} = props;
  const {order, label, unlabelled} = props.attributes;

  const {tabOrder, hasChildBlocks} = useSelect((select) => {
    const {getBlockOrder, getBlockRootClientId} = select('core/block-editor');
    const rootClientId = getBlockRootClientId(clientId);
    const parentBlockOrder = getBlockOrder(rootClientId);
    return {
      hasChildBlocks: getBlockOrder(clientId).length > 0,
      tabOrder: parentBlockOrder.indexOf(clientId) + 1
    };
  }, []);

  if (label === '' && order === 0) {
    props.setAttributes({label: __(`Tab ${tabOrder}`)});
  }

  if (order !== tabOrder) {
    props.setAttributes({order: tabOrder});
  }

  const fields = [
    {
      name: 'label',
      control: 'text',
      label: __('Tab Label')
    },
    {
      name: 'unlabelled',
      control: 'toggle',
      label: __('Hide Heading')
    }
  ];

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields {...props} fields={fields} />
        </PanelBody>
      </InspectorControls>
      <div className='vf-tabs__section'>
        {unlabelled ? false : <h2>{label}</h2>}
        <InnerBlocks
          renderAppender={
            hasChildBlocks
              ? undefined
              : () => <InnerBlocks.ButtonBlockAppender />
          }
        />
      </div>
    </Fragment>
  );
};

export default settings;
