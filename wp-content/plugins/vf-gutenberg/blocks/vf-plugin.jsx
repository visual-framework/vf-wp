/**
 * VF Plugin (base component)
 */
import {useEffect, useRef} from 'react';
import {useVF, useVFPlugin, useIFrame} from './hooks';

const {__} = wp.i18n;

const {BlockControls} = wp.editor;
const {Toolbar, IconButton} = wp.components;

const EditButton = ({onClick}) => {
  return (
    <IconButton label={__('Edit', 'vfwp')} icon="edit" onClick={onClick} />
  );
};

const ViewButton = ({onClick}) => {
  return (
    <IconButton
      label={__('Preview', 'vfwp')}
      icon="visibility"
      onClick={onClick}
    />
  );
};

const ViewControl = ({data}) => {
  const iframeEl = useRef();
  const {onLoad} = useIFrame(iframeEl, data.html);

  // cleanup iframe resizer before unmount
  useEffect(() => {
    const iframe = iframeEl.current;
    return () => {
      if (iframe.iFrameResizer) {
        iframe.iFrameResizer.close();
      }
    };
  }, [data.hash]);

  return (
    <div className="vf-gutenberg-view">
      <iframe
        ref={iframeEl}
        onLoad={onLoad}
        data-hash={data.hash}
        className="vf-gutenberg-iframe"
        scrolling="no"
      />
    </div>
  );
};

const PluginEdit = function(props) {
  const {
    isSelected,
    attributes: {ver, mode}
  } = props;

  const isEditing = mode === 'edit';

  // Ensure version is encoded in post content
  if (!ver) {
    props.setAttributes({ver: 1});
  }

  const {data, isLoading} = useVFPlugin({
    ...props.attributes,
    name: props.name
  });

  const onToggle = () => {
    props.setAttributes({mode: !isEditing ? 'edit' : 'view'});
  };

  return [
    <BlockControls>
      <Toolbar>
        {isEditing ? (
          <ViewButton onClick={onToggle} />
        ) : (
          <EditButton onClick={onToggle} />
        )}
      </Toolbar>
    </BlockControls>,
    <div
      className="vf-gutenberg-block"
      data-ver={ver}
      data-name={props.name}
      data-editing={isEditing}
      data-selected={isSelected}
      data-loading={isLoading}>
      {isEditing ? (
        <div className="vf-gutenberg-edit">{props.children}</div>
      ) : !isLoading ? (
        <ViewControl data={data} />
      ) : (
        false
      )}
    </div>
  ];
};

export default PluginEdit;
