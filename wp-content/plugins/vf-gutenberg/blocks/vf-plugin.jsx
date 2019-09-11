/**
 * VF Plugin (base component)
 */
import {useEffect, useRef} from 'react';
import {useVF, useVFPlugin, useIFrame} from './hooks';
import Spinner from './spinner';

const {__} = wp.i18n;

const {BlockControls} = wp.editor;
const {IconButton, Toolbar} = wp.components;

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

/**
 * Cannot use `<iframe onLoad...>` load event in React does not
 * fire properly in Safari/Chrome for iframes
 */
const PluginViewMode = React.memo(({data, clientId}) => {
  // create iframe
  const iframeId = `iframe_${data.hash}_${clientId}`.replace(/[^\w]/g, '');
  const iframe = document.createElement('iframe');
  iframe.id = iframeId;
  iframe.className = 'vf-gutenberg-iframe';
  iframe.setAttribute('scrolling', 'no');

  const rootEl = useRef();
  const {onLoad} = useIFrame(iframe, data);

  useEffect(() => {
    iframe.addEventListener('load', ev => onLoad(ev));
    rootEl.current.appendChild(iframe);
    return () => {
      // cleanup iframe resizer before unmount
      if (iframe.iFrameResizer) {
        iframe.iFrameResizer.close();
      }
    };
  });
  return <div className="vf-gutenberg-view" ref={rootEl} />;
});

// const PluginEditMode = props => {
//   return <div className="vf-gutenberg-edit">{props.children}</div>;
// };

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
        <PluginViewMode data={data} clientId={props.clientId} />
      ) : (
        <Spinner />
      )}
    </div>
  ];
};

export default PluginEdit;

// <PluginEditMode {...props} />
