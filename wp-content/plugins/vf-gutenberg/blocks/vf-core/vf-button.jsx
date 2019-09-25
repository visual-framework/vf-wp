/**
 * VF Framework Button
 */
import React from 'react';
import {__} from '@wordpress/i18n';
import useVFCoreSettings from '../hooks/use-vf-core-settings';
import template from '../templates/vf-button';

const settings = useVFCoreSettings({
  name: 'vf/button',
  title: __('Button'),
  attributes: {
    href: {
      type: 'string',
      default: '/'
    },
    text: {
      type: 'string',
      default: __('Button')
    },
    theme: {
      type: 'string',
      default: 'primary'
    },
    size: {
      type: 'string',
      default: 'regular'
    }
  },
  fields: [
    {
      name: 'text',
      control: 'text',
      label: __('Label')
    },
    {
      name: 'href',
      control: 'url',
      label: __('URL')
    }
  ]
});

settings.supports.customClassName = true;

settings.supports.styles = [
  {
    name: 'primary',
    label: __('Primary'),
    isDefault: true
  },
  {
    name: 'secondary',
    label: __('Secondary')
  },
  {
    name: 'tertiary',
    label: __('Tertiary')
  }
];

export default settings;

// import React from 'react';
// import {InspectorControls} from '@wordpress/block-editor';
// import {PanelBody, SelectControl} from '@wordpress/components';
// import {__} from '@wordpress/i18n';
// import VFBlock from '../vf-block';
// import VFBlockFields from '../vf-block/block-fields';

// const vfButtonAttrs = {
//   url: {
//     type: 'string'
//   },
//   label: {
//     type: 'string'
//   },
//   size: {
//     type: 'string',
//     default: 'regular'
//   }
// };

// const vfButtonFields = [
// {
//   name: 'label',
//   control: 'text',
//   label: 'Label'
// },
// {
//   name: 'url',
//   control: 'url',
//   label: 'URL'
// }
// ];

// const vfButtonStyles = [
//   {
//     name: 'primary',
//     label: __('Primary'),
//     isDefault: true
//   },
//   {
//     name: 'secondary',
//     label: __('Secondary')
//   },
//   {
//     name: 'tertiary',
//     label: __('Tertiary')
//   }
// ];

// const vfButtonSizes = [
//   {label: __('Small'), value: 'small'},
//   {label: __('Regular'), value: 'regular'},
//   {label: __('Large'), value: 'large'}
// ];

// export default {
//   name: 'vf/button',
//   category: 'vf/core',
//   title: __('Button'),
//   description: __('Visual Framework'),
//   keywords: [__('VF'), __('Visual Framework')],
//   attributes: {
//     ver: {
//       type: 'integer'
//     },
//     mode: {
//       type: 'string',
//       default: 'edit'
//     },
//     ...vfButtonAttrs
//   },
//   styles: [...vfButtonStyles],
//   supports: {
//     align: false,
//     className: false,
//     customClassName: true,
//     html: false
//   },
//   save: () => null,
//   edit: props => {
//     const {attributes, setAttributes} = props;
//     return (
//       <>
//         <VFBlock {...props} ver={1} hasFooter>
//           <VFBlockFields {...props} fields={vfButtonFields} />
//         </VFBlock>
//         <InspectorControls>
//           <PanelBody title={__('Settings')} initialOpen={false}>
//             <SelectControl
//               label={__('Size')}
//               value={attributes['size']}
//               onChange={size => setAttributes({size})}
//               options={vfButtonSizes}
//             />
//           </PanelBody>
//         </InspectorControls>
//       </>
//     );
//   }
// };
