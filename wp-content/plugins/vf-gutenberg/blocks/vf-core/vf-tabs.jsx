/**
Block Name: Grid
*/
import React, {useEffect, Fragment} from 'react';
import {createBlock} from '@wordpress/blocks';
import {InnerBlocks, InspectorControls} from '@wordpress/block-editor';
import {Button, PanelBody} from '@wordpress/components';
import {withDispatch, useDispatch, useSelect} from '@wordpress/data';
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
      type: 'object',
      default: {}
    }
  }
};

// const withTabsProps = (Save) => {
//   return (props) => {
//     const clientId = props.clientId;
//     const {hasTabs, innerTabs} = useSelect(
//       (select) => {
//         const {getBlocks} = select('core/block-editor');
//         const innerTabs = getBlocks(clientId);
//         return {
//           hasTabs: innerTabs > 0,
//           innerTabs
//         };
//       },
//       [clientId]
//     );
//     return Save({...props, hasTabs, innerTabs});
//   };
// };

settings.save = (props) => {
  // if (props.attributes.placeholder === 1) {
  //   return null;
  // }
  // console.log(props.innerBlocks.length);
  // const {columns} = props.attributes;
  const className = `vf-tabs`; //  | vf-grid__col-${columns}
  return (
    <div className={className}>
      {/* <ul class='vf-tabs__list' data-vf-js-tabs>
        {props.innerBlocks.map((block, i) => {
          return (
            <li key={block.clientId} class='vf-tabs__item'>
              <a class='vf-tabs__link' href={`#vf-tabs__section--${i + 1}`}>
                {block.attributes.label}
              </a>
            </li>
          );
        })}
      </ul> */}
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

  const {updateBlockAttributes, replaceInnerBlocks} = useDispatch(
    'core/block-editor'
  );

  const {getTabs, setTabs, updateTabs} = useSelect(
    (select) => {
      const {getBlocks} = select('core/block-editor');
      const getTabs = () => {
        return getBlocks(clientId);
      };
      const updateTabs = () => {
        const innerTabs = getTabs();
        const newTabs = {};
        innerTabs.forEach((tab, i) => {
          const {id, label} = tab.attributes;
          newTabs[i] = {
            id,
            label
          };
        });
        props.setAttributes({dirty: 0, tabs: newTabs});
      };
      const setTabs = (newTabs) => {
        let innerTabs = getTabs();
        if (newTabs > innerTabs.length) {
          while (innerTabs.length < newTabs) {
            innerTabs.push(createBlock('vf/tabs-section', {}, []));
          }
        }
        replaceInnerBlocks(clientId, innerTabs, false);
      };
      return {
        getTabs,
        setTabs,
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

  // Require at least one tab
  // if (getTabs().length < 1) {
  //   setTabs(1);
  // }

  // props.maybeCleanup(updateTabs);

  // if (!props.isDirty()) {
  //   if (Object.keys(tabs).length !== getTabs().length) {
  //     // props.setAttributes({dirty: Date.now()});
  //     console.log('add/remove');
  //     props.setDirty(true);
  //   }
  // }

  // if (props.attributes.dirty > 0) {
  //   props.setAttributes({dirty: 0});
  //   console.log('trigger');
  //   // if (!props.isDirty()) {
  //   props.setDirty(() => {
  //     updateTabs();
  //   });
  //   // }
  //   // if (Date.now() - props.attributes.dirty < 100) {
  //   //   console.log(Date.now() - props.attributes.dirty);
  //   //   // updateTabs();
  //   // }
  // }

  // Setup placeholder fields
  const fields = [
    {
      control: 'button',
      label: __('Add Tab'),
      isSecondary: true,
      onClick: () => {
        setTabs(getTabs().length + 1);
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
