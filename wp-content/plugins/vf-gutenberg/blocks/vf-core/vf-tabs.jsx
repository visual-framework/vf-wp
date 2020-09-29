/**
Block Name: Grid
*/
import React, {useEffect, Fragment} from 'react';
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
      <ul class='vf-tabs__list' data-vf-js-tabs>
        {props.attributes.tabs.map((tab) => {
          return (
            <li key={tab.id} class='vf-tabs__item'>
              <a class='vf-tabs__link' href={`#vf-tabs__section-${tab.id}`}>
                {tab.label}
              </a>
            </li>
          );
        })}
      </ul>
      <div class='vf-tabs-content' data-vf-js-tabs-content>
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

  const {replaceInnerBlocks} = useDispatch('core/block-editor');

  const {getTabs, appendTab, updateTabs} = useSelect(
    (select) => {
      const {getBlocks} = select('core/block-editor');
      const getTabs = () => {
        return getBlocks(clientId);
      };
      const appendTab = () => {
        const innerTabs = getTabs();
        innerTabs.push(createBlock('vf/tabs-section', {}, []));
        replaceInnerBlocks(clientId, innerTabs, false);
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
        getTabs,
        appendTab,
        updateTabs
      };
    },
    [clientId]
  );

  useEffect(() => {
    if (dirty === 1) {
      console.log(`tabs cleanup ${clientId}`);
      updateTabs();
    }
  }, [dirty]);

  useEffect(() => {
    if (dirty === 0) {
      if (Object.keys(tabs).length !== getTabs().length) {
        console.log(`tabs recount ${clientId}`);
        props.setAttributes({dirty: 1});
      }
    }
  }, [getTabs().length]);

  // Setup placeholder fields
  const fields = [
    {
      control: 'button',
      label: __('Add Tab'),
      isSecondary: true,
      onClick: () => {
        appendTab();
      }
    }
  ];

  // Return inner blocks and inspector controls
  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields fields={fields} />
        </PanelBody>
      </InspectorControls>
      <div className={'vf-tabs'} data-ver={ver}>
        <InnerBlocks
          allowedBlocks={['vf/tabs-section']}
          template={Array(1).fill(['vf/tabs-section'])}
        />
        <Button {...fields[0]}>
          <span>{fields[0].label}</span>
        </Button>
      </div>
    </Fragment>
  );
};

export default settings;
