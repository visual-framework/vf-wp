/**
Block Name: Grid
*/
import React, {Fragment} from 'react';
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
    // placeholder: {
    //   type: 'integer',
    //   default: 0
    // },
    tabs: {
      type: 'integer',
      default: 0
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
  // console.log(props);
  // const {columns} = props.attributes;
  const className = `vf-tabs`; //  | vf-grid__col-${columns}
  return (
    <div className={className}>
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
  const {tabs} = props.attributes;

  const {replaceInnerBlocks} = useDispatch('core/block-editor');

  const {innerTabs, setTabs} = useSelect((select) => {
    const {getBlocks} = select('core/block-editor');
    let innerTabs = getBlocks(clientId);
    const setTabs = (newTabs) => {
      // innerTabs = innerTabs.slice(0, newTabs);
      if (newTabs > tabs) {
        while (innerTabs.length < newTabs) {
          innerTabs.push(createBlock('vf/tabs-section', {}, []));
        }
        replaceInnerBlocks(clientId, innerTabs, false);
        props.setAttributes({tabs: innerTabs.length});
      }
    };
    return {
      setTabs,
      innerTabs
    };
  }, []);


  if (tabs === 0) {
    setTabs(1);
  }

  if (tabs !== innerTabs.length) {
    props.setAttributes({tabs: innerTabs.length});
  }

  // Setup placeholder fields
  const fields = [
    {
      control: 'button',
      label: __('Add Tab'),
      isSecondary: true,
      onClick: () => {
        setTabs(tabs + 1);
      }
    }
  ];

  // Return setup placeholder
  // if (placeholder === 1) {
  //   return (
  //     <div className={`vf-block vf-block--placeholder ${props.className}`}>
  //       <Placeholder label={__('VF Grid')} icon={'admin-generic'}>
  //         <VFBlockFields fields={fields} />
  //       </Placeholder>
  //     </div>
  //   );
  // }

  // // Amend fields for inspector
  // fields[0].help = __('Content may be reorganised when columns are reduced.');

  // Return inner blocks and inspector controls
  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Settings')} initialOpen>
          <VFBlockFields fields={fields} />
        </PanelBody>
      </InspectorControls>
      <div className={'vf-tabs'} data-ver={ver} data-tabs={tabs}>
        <InnerBlocks
          allowedBlocks={['vf/tabs-section']}
          // template={Array(tabs).fill(['vf/tabs-section'])}
          // templateLock='all'
        />
        <Button {...fields[0]}>
          <span>{fields[0].label}</span>
        </Button>
      </div>
    </Fragment>
  );
};

export default settings;
