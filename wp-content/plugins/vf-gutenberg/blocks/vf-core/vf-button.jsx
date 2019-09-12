/**
 * VF Framework Button
 */

import {PluginEdit} from '../vf-plugin';

const {__} = wp.i18n;
const {URLInput} = wp.editor;
const {BaseControl, Button, TextControl} = wp.components;

export default {
  name: 'vf/button',
  title: __('Button', 'vfwp'),
  category: 'vf/core',
  keywords: [__('VF', 'vfwp'), __('Visual Framework', 'vfwp')],
  attributes: {
    ver: {
      type: 'integer'
    },
    mode: {
      type: 'string',
      default: 'edit'
    },
    url: {
      type: 'string'
    },
    label: {
      type: 'string'
    }
  },
  supports: {
    align: false,
    className: false,
    customClassName: false,
    html: false
  },
  save: () => null,
  edit: props => {
    const {attributes: attrs, setAttributes} = props;
    const onUpdate = () => {
      props.setAttributes({
        mode: attrs.mode === 'edit' ? 'view' : 'edit'
      });
    };
    return (
      <PluginEdit {...props} ver={2}>
        <div className="vf-gutenberg-edit">
          <TextControl
            label={__('Label', 'vfwp')}
            value={attrs.label}
            onChange={label => setAttributes({label})}
          />
          <BaseControl label={__('URL', 'vfwp')}>
            <URLInput
              value={attrs.url}
              onChange={url => setAttributes({url})}
            />
          </BaseControl>
          <Button isDefault isLarge onClick={onUpdate}>
            {__('Update', 'vfwp')}
          </Button>
        </div>
      </PluginEdit>
    );
  }
};
