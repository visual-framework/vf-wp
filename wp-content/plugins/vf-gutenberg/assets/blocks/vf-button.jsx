/**
 * VF Button
 * https://visual-framework.github.io/vf-core/components/detail/vf-button.html
 */

const {__} = wp.i18n;

const {
  InnerBlocks,
  URLInput,
  RichText,
  BlockControls,
  InspectorControls
} = wp.editor;
const {
  Toolbar,
  IconButton,
  SelectControl,
  Panel,
  PanelBody,
  ToggleControl,
  Button,
  ButtonGroup,
  BaseControl,
  Dashicon
} = wp.components;
const {Component, createElement} = wp.element;

const {compose, withInstanceId} = wp.compose;

const Edit = function(props) {
  const {
    instanceId,
    isSelected,
    setAttributes,
    attributes: {id, mode, url}
  } = props;
  const isEdit = mode === 'edit';
  const onToggleView = () => {
    setAttributes({mode: !isEdit ? 'edit' : 'view'});
  };
  const editButton = (
    <IconButton label={__('Edit', 'vfwp')} icon="edit" onClick={onToggleView} />
  );
  const viewButton = (
    <IconButton
      label="__('Preview', 'vfwp')"
      icon="visibility"
      onClick={onToggleView}
    />
  );
  const ids = {
    url: 'vf-gutenberg-' + instanceId
  };

  const controls = [
    <BaseControl id={ids.url} label={__('URL', 'vfwp')}>
      <URLInput
        id={ids.url}
        value={url}
        autoFocus={false}
        onChange={value => setAttributes({url: value})}
        disableSuggestions={!isSelected}
        isFullWidth
        hasBorder
      />
    </BaseControl>
  ];
  const edit = (
    <div className="vf-gutenberg-edit" data-id={id}>
      {controls}
    </div>
  );
  const view = (
    <div
      className="vf-gutenberg-view"
      dangerouslySetInnerHTML={{__html: '<b>View</b>'}}
    />
  );
  /*
  <InspectorControls>
    <PanelBody title={__('VF Button', 'vfwp')}>{controls}</PanelBody>
  </InspectorControls>
  */
  return [
    <BlockControls>
      <Toolbar>{isEdit ? viewButton : editButton}</Toolbar>
    </BlockControls>,
    <div className="vf-gutenberg-block">{isEdit ? edit : view}</div>
  ];
};

export const settings = {
  title: __('VF Button', 'vfwp'),
  icon: 'format-aside',
  category: 'vf/core',
  keywords: [__('VF', 'vfwp'), __('Visual Framework', 'vfwp')],
  attributes: {
    ver: {
      type: 'integer',
      default: 1
    },
    id: {
      type: 'string',
      default: 'vf/button'
    },
    mode: {
      type: 'string'
    },
    url: {
      type: 'string'
    }
  },
  supports: {
    align: false,
    html: false,
    className: false,
    customClassName: false
  },
  edit: compose(withInstanceId)(Edit),
  save: function(props) {
    const data = {
      ver: props.attributes.ver
    };
    // return JSON.stringify(data);
    return '';
  }
};
