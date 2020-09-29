/**
Block Name: Grid Column
*/
import React, {Fragment} from 'react';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {PanelBody} from '@wordpress/components';
import {useDispatch, useSelect} from '@wordpress/data';
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
    id: {
      type: 'string',
      default: ''
    },
    label: {
      type: 'string',
      default: ''
    },
    unlabelled: {
      type: 'integer',
      default: 0
    }
  }
};

settings.save = (props) => {
  const {id, order, label, unlabelled} = props.attributes;
  const attr = {
    className: `vf-tabs__section`
  };
  if (id !== '') {
    attr.id = id;
  }
  // if (order > 0) {
  // attr.id = `vf-tabs__section--${order}`;
  // }
  const h2 = {};
  if (unlabelled === 1) {
    h2.className = 'vf-u-sr-only';
  }
  return (
    <section {...attr}>
      <h2 {...h2}>{label}</h2>
      <InnerBlocks.Content />
    </section>
  );
};

settings.edit = (props) => {
  if (ver !== props.attributes.ver) {
    props.setAttributes({ver});
  }

  const {clientId} = props;
  let {id, order, label, unlabelled} = props.attributes;

  const {updateBlockAttributes} = useDispatch('core/block-editor');

  const {updateTabs, tabOrder, hasChildBlocks} = useSelect((select) => {
    const {
      getBlocks,
      // getBlockAttributes,
      getBlockOrder,
      getBlockRootClientId
    } = select('core/block-editor');
    const rootClientId = getBlockRootClientId(clientId);
    const parentBlockOrder = getBlockOrder(rootClientId);
    // console.log(getBlockAttributes(rootClientId));

    const updateTabs = () => {
      // const tabs = {};
      // const innerTabs = getBlocks(rootClientId);
      // innerTabs.forEach((tab, i) => {
      //   const {id, label} = tab.attributes;
      //   tabs[i] = {
      //     id,
      //     label
      //   };
      // });
      // console.log(tabs, rootClientId);
      updateBlockAttributes(rootClientId, {
        dirty: 1
      });
    };

    // updateTabs();

    return {
      updateTabs,
      hasChildBlocks: getBlockOrder(clientId).length > 0,
      tabOrder: parentBlockOrder.indexOf(clientId) + 1
    };
  }, []);

  if (id === '') {
    id = clientId;
    props.setAttributes({id});
    // updateTabs();
  }

  if (label === '' && order === 0) {
    label = __(`Tab ${tabOrder}`);
    props.setAttributes({label});
    // updateTabs();
  }

  if (order !== tabOrder) {
    props.setAttributes({order: tabOrder});
    // updateTabs();
  }

  const onChange = (name, value) => {
    if (name === 'id') {
      value = value
        .replace(/[\s\./]+/g, '-')
        .replace(/[^\w-]+/g, '')
        .toLowerCase()
        .trim();
    }
    props.setAttributes({[name]: value});
    updateTabs();
  };

  const fields = [
    {
      name: 'label',
      control: 'text',
      label: __('Tab Label'),
      onChange
    },
    {
      name: 'unlabelled',
      control: 'toggle',
      label: __('Hide Heading'),
      onChange
    },
    {
      name: 'id',
      control: 'text',
      label: __('Anchor ID'),
      onChange
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
