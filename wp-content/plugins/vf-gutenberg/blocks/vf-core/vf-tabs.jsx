/**
Block Name: Grid
*/
import React, {useCallback, useEffect} from 'react';
import {createBlock} from '@wordpress/blocks';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {Button, PanelBody} from '@wordpress/components';
import {useDispatch, useSelect} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import useVFDefaults from '../hooks/use-vf-defaults';
import VFBlockFields from '../vf-block/block-fields';

const defaults = useVFDefaults();

const ver = '1.0.0';

const settings = {
  ...defaults,
  name: 'vf/tabs',
  title: __('VF Tabs'),
  category: 'vf/core',
  description: __('Visual Framework (core)'),
  attributes: {
    ...defaults.attributes,
    dirty: {
      type: 'integer',
      default: 0
    },
    tabs: {
      type: 'array',
      default: []
    }
  }
};

settings.save = (props) => {
  return (
    <div className='vf-tabs'>
      <ul className='vf-tabs__list' data-vf-js-tabs>
        {props.attributes.tabs.map((tab, i) => {
          return (
            <li key={i + tab.id} className='vf-tabs__item'>
              <a className='vf-tabs__link' href={`#vf-tabs__section-${tab.id}`}>
                {tab.label}
              </a>
            </li>
          );
        })}
      </ul>
      <div className='vf-tabs-content' data-vf-js-tabs-content>
        <InnerBlocks.Content />
      </div>
    </div>
  );
};

settings.edit = (props) => {
  if (ver !== props.attributes.ver) {
    props.setAttributes({ver});
  }

  const {clientId} = props;
  const {dirty, tabs} = props.attributes;

  const {replaceInnerBlocks, selectBlock} = useDispatch('core/block-editor');

  const {appendTab, getTabs, getTabsOrder, updateTabs} = useSelect(
    (select) => {
      const {getBlockOrder, getBlocks} = select('core/block-editor');
      const getTabs = () => {
        return getBlocks(clientId);
      };
      const getTabsOrder = () => {
        return getBlockOrder(clientId);
      };
      const appendTab = () => {
        const innerTabs = getTabs();
        innerTabs.push(createBlock('vf/tabs-section', {}, []));
        replaceInnerBlocks(clientId, innerTabs, false);
        selectBlock(innerTabs.slice(-1)[0].clientId);
      };
      const updateTabs = () => {
        const innerTabs = getTabs();
        const newTabs = [];
        innerTabs.forEach((block) => {
          const {id, label} = block.attributes;
          newTabs.push({
            id,
            label
          });
        });
        props.setAttributes({dirty: 0, tabs: newTabs});
      };
      return {
        appendTab,
        getTabs,
        getTabsOrder,
        updateTabs
      };
    },
    [clientId]
  );

  const tabsOrder = getTabsOrder();
  // Callback to switch tabs using the tab list interface
  const selectTab = useCallback(
    (index) => {
      if (index < tabsOrder.length) {
        selectBlock(tabsOrder[index]);
      }
    },
    [tabsOrder]
  );

  // Flag as "dirty" if the tabs and inner blocks do not match
  useEffect(() => {
    if (dirty === 0) {
      if (Object.keys(tabs).length !== getTabs().length) {
        props.setAttributes({dirty: Date.now()});
      }
    }
  }, [getTabs().length]);

  // Update attributes if the block is flagged as "dirty"
  useEffect(() => {
    if (dirty > 0) {
      updateTabs();
    }
  }, [dirty]);

  // Inspector controls
  const fields = [
    {
      control: 'button',
      label: __('Add Tab'),
      isSecondary: true,
      icon: 'insert',
      onClick: () => {
        appendTab();
      }
    }
  ];

  // Return inner blocks and inspector controls
  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields fields={fields} />
        </PanelBody>
      </InspectorControls>
      <div className='vf-tabs' data-ver={ver}>
        <ul className='vf-tabs__list'>
          {tabs.map((tab, i) => {
            return (
              <li key={i + tab.id} className='vf-tabs__item'>
                <a className='vf-tabs__link' onClick={() => selectTab(i)}>
                  {tab.label}
                </a>
              </li>
            );
          })}
          <li className='vf-tabs__item'>
            <Button {...fields[0]} isTertiary isSecondary={false}>
              <span>{fields[0].label}</span>
            </Button>
          </li>
        </ul>
        <InnerBlocks
          allowedBlocks={['vf/tabs-section']}
          template={Array(1).fill(['vf/tabs-section'])}
        />
      </div>
    </>
  );
};

export default settings;
