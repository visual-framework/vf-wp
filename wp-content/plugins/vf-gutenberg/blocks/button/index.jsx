/**
 * VF Button
 * https://visual-framework.github.io/vf-core/components/detail/vf-button.html
 */
import {useRef} from 'react';
import {useVF, useVFBlock, useIFrameResize} from '../hooks';

const {__} = wp.i18n;
const {compose, withInstanceId} = wp.compose;
const {
  InnerBlocks,
  URLInput,
  URLInputButton,
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

const EditButton = ({onClick}) => {
  return (
    <IconButton label={__('Edit', 'vfwp')} icon="edit" onClick={onClick} />
  );
};

const ViewButton = ({onClick}) => {
  return (
    <IconButton
      label="__('Preview', 'vfwp')"
      icon="visibility"
      onClick={onClick}
    />
  );
};

const ViewControl = ({data}) => {
  const iframeEl = useRef();
  const {onLoad} = useIFrameResize(iframeEl, data.html);
  return (
    <div className="vf-gutenberg-view">
      <iframe
        ref={iframeEl}
        onLoad={onLoad}
        className="vf-gutenberg-iframe"
        scrolling="no"
      />
    </div>
  );
};

const Edit = function(props) {
  const {
    isSelected,
    instanceId,
    setAttributes,
    attributes: {ver, id, mode, url}
  } = props;

  // Ensure version is encoded in post content
  if (!ver) {
    setAttributes({ver: 1});
  }

  const isEdit = mode === 'edit';

  const {data, isLoading} = useVFBlock({id, url/*, vf_plugin:'vf_latest_posts'*/});

  const LoadingControl = () => {
    return <div>{__('Loading', 'vfwp')}</div>;
  };

  const onToggle = () => {
    setAttributes({mode: !isEdit ? 'edit' : 'view'});
  };

  return [
    <BlockControls>
      <Toolbar>
        {isEdit ? (
          <ViewButton onClick={onToggle} />
        ) : (
          <EditButton onClick={onToggle} />
        )}
      </Toolbar>
    </BlockControls>,
    <div
      className="vf-gutenberg-block"
      data-ver={ver}
      data-id={id}
      data-edit={isEdit}
      data-selected={isSelected}
      data-loading={isLoading}>
      {isEdit ? (
        <div className="vf-gutenberg-edit">
          <BaseControl
            id={'vf-gutenberg-' + instanceId}
            label={__('URL', 'vfwp')}>
            <URLInput
              id={'vf-gutenberg-' + instanceId}
              value={url}
              autoFocus={false}
              onChange={value => setAttributes({url: value})}
              disableSuggestions={!isSelected}
              isFullWidth
              hasBorder
            />
          </BaseControl>
        </div>
      ) : isLoading ? (
        <LoadingControl />
      ) : (
        <ViewControl data={data} />
      )}
    </div>
  ];
};

export const settings = {
  title: __('VF Button', 'vfwp'),
  icon: 'format-aside',
  category: 'vf/core',
  keywords: [__('VF', 'vfwp'), __('Visual Framework', 'vfwp')],
  attributes: {
    ver: {
      type: 'integer'
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
  edit: withInstanceId(Edit),
  save: () => null
};
