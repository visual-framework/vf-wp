/**
 * VF Plugin (base component)
 */
import {useEffect, useRef} from 'react';
import {useVF, useVFPlugin, useIFrame} from './hooks';
import Spinner from './spinner';

const {__} = wp.i18n;

const {BlockControls} = wp.editor;
const {
  Button,
  BaseControl,
  CheckboxControl,
  IconButton,
  RadioControl,
  RangeControl,
  SelectControl,
  TextControl,
  TextareaControl,
  ToggleControl,
  Toolbar
} = wp.components;

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
const PluginPreview = React.memo(({data, clientId}) => {
  // create iframe
  const iframeId = `iframe_${data.hash}_${clientId}`.replace(/[^\w]/g, '');
  const iframe = document.createElement('iframe');
  iframe.id = iframeId;
  iframe.className = 'vf-gutenberg-iframe';
  iframe.setAttribute('scrolling', 'no');

  const rootEl = useRef();
  const {onLoad, onUnload} = useIFrame(iframe, data.html);

  useEffect(() => {
    iframe.addEventListener('load', ev => onLoad(ev));
    rootEl.current.appendChild(iframe);
    return () => {
      onUnload();
    };
  });
  return <div className="vf-gutenberg-view" ref={rootEl} />;
});

/**
 * The default "edit" component for VF Gutenberg blocks
 */
const PluginEdit = function(props) {
  const {
    isSelected,
    attributes: {ver, mode, ...attrs}
  } = props;

  const hasMode = typeof mode === 'string';
  const isEditing = hasMode && mode === 'edit';

  // Ensure version is encoded in post content
  if (!ver) {
    props.setAttributes({ver: 1});
  }

  const {data, isLoading} = useVFPlugin({
    attrs: attrs,
    name: props.name
  });

  const onToggle = () => {
    props.setAttributes({mode: !isEditing ? 'edit' : 'view'});
  };

  return (
    <React.Fragment>
      {hasMode && (
        <BlockControls>
          <Toolbar>
            {isEditing ? (
              <ViewButton onClick={onToggle} />
            ) : (
              <EditButton onClick={onToggle} />
            )}
          </Toolbar>
        </BlockControls>
      )}
      <div
        className="vf-gutenberg-block"
        data-ver={ver}
        data-name={props.name}
        data-editing={isEditing}
        data-loading={isLoading}
        data-selected={isSelected}>
        {isEditing ? (
          props.children
        ) : !isLoading ? (
          <PluginPreview data={data} clientId={props.clientId} />
        ) : (
          <Spinner />
        )}
      </div>
    </React.Fragment>
  );
};

/**
 * Automatically map field controls to attributes
 */
const PluginEditFields = props => {
  const {attributes: attrs, setAttributes, fields} = props;
  const onUpdate = () => {
    props.setAttributes({
      mode: attrs.mode === 'edit' ? 'view' : 'edit'
    });
  };
  const onChange = (name, value) => {
    const attr = {};
    attr[name] = value;
    setAttributes({...attr});
  };
  return (
    <div className="vf-gutenberg-edit">
      {fields.map(field => {
        const {name, type, label} = field;
        if (type === 'text') {
          return (
            <TextControl
              label={label}
              value={attrs[name]}
              onChange={value => onChange(name, value)}
            />
          );
        }
        if (type === 'textarea') {
          return (
            <TextareaControl
              label={label}
              value={attrs[name]}
              onChange={value => onChange(name, value)}
            />
          );
        }
        if (type === 'select') {
          return (
            <SelectControl
              label={label}
              value={attrs[name]}
              onChange={value => onChange(name, value)}
              options={[...field.options]}
            />
          );
        }
        if (type === 'radio') {
          return (
            <RadioControl
              label={label}
              selected={attrs[name]}
              onChange={value => onChange(name, value)}
              options={[...field.options]}
            />
          );
        }
        if (type === 'range') {
          return (
            <RangeControl
              label={label}
              value={parseInt(attrs[name])}
              onChange={value => onChange(name, value)}
              min={parseInt(field['min'])}
              max={parseInt(field['max'])}
            />
          );
        }
      })}
      <Button isDefault isLarge onClick={onUpdate}>
        {__('Update', 'vfwp')}
      </Button>
    </div>
  );
};

/*
const onCheckboxChange = checked => {
  props.setAttributes({
    checkbox: checked
  });
};
<CheckboxControl
  heading="Checkbox"
  label="checkbox"
  checked={props.attributes.checkbox}
  onChange={onCheckboxChange}
/>
*/

export {PluginEdit, PluginEditFields};
