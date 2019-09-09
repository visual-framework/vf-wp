const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {InnerBlocks, RichText, BlockControls, InspectorControls} = wp.editor;
const {
  Toolbar,
  IconButton,
  SelectControl,
  PanelBody,
  ToggleControl,
  Button
} = wp.components;
const {Component, createElement} = wp.element;

registerBlockType('vf/block', {
  title: __('VF Block'),
  icon: 'format-aside',
  category: 'vf/core',
  keywords: [__('VF'), __('Visual Framework')],
  attributes: {
    ver: {
      type: 'integer',
      default: 1
    },
    mode: {
      type: 'string'
    }
  },
  supports: {
    align: false,
    html: false,
    className: false,
    customClassName: false
  },
  edit: function(props) {
    // const {
    //   attributes: {mode}
    // } = props;
    const isEdit = props.attributes.mode === 'edit';
    const onToggleView = () => {
      props.setAttributes({mode: !isEdit ? 'edit' : 'view'});
    };
    const editButton = (
      <IconButton
        label={__('Edit', 'vfwp')}
        icon="edit"
        onClick={onToggleView}
      />
    );
    const viewButton = (
      <IconButton
        label="__('Preview', 'vfwp')"
        icon="visibility"
        onClick={onToggleView}
      />
    );
    const edit = (
      <div className="vf-gutenberg-edit">
        <p>Edit</p>
      </div>
    );
    const view = (
      <div
        className="vf-gutenberg-view"
        dangerouslySetInnerHTML={{__html: '<b>View</b>'}}
      />
    );
    return [
      <BlockControls>
        <Toolbar>{isEdit ? viewButton : editButton}</Toolbar>
      </BlockControls>,
      <div className="vf-gutenberg-block">{isEdit ? edit : view}</div>
    ];
  },
  save: function(props) {
    const data = {
      ver: props.attributes.ver,
      name: 'vf/block'
    };
    // return JSON.stringify(data);
    return '';
  }
});
