/**
 * VF Plugin (base component)
 */
import {useEffect, useRef} from 'react';
import {useVFPluginData, useIFrame, useUniqueId} from './hooks';

const {__} = wp.i18n;

const {withInstanceId} = wp.compose;

const {BlockControls} = wp.editor;
const {
  Button,
  BaseControl,
  CheckboxControl,
  IconButton,
  RadioControl,
  RangeControl,
  SelectControl,
  Spinner,
  TextControl,
  TextareaControl,
  ToggleControl,
  Toolbar
} = wp.components;

/**
 * The togglable "Edit" button added to `BlockControls`
 */
const EditButton = ({onClick}) => (
  <IconButton label={__('Edit')} icon="edit" onClick={onClick} />
);

/**
 * The togglable "View" button added to `BlockControls`
 */
const ViewButton = ({onClick}) => (
  <IconButton label={__('Preview')} icon="visibility" onClick={onClick} />
);

/**
 * The "Update" button appended to `VFBlock` (optional)
 */
const UpdateButton = ({onClick}) => (
  <Button isDefault isLarge onClick={onClick}>
    {__('Update')}
  </Button>
);

const VFBlockControls = ({isEditing, onToggle}) => {
  return (
    <BlockControls>
      <Toolbar>
        {isEditing ? (
          <ViewButton onClick={onToggle} />
        ) : (
          <EditButton onClick={onToggle} />
        )}
      </Toolbar>
    </BlockControls>
  );
};

const VFBlockEdit = ({hasFooter, onToggle, children}) => {
  return (
    <div className="vf-gutenberg-edit">
      {children}
      {hasFooter && <UpdateButton onClick={onToggle} />}
    </div>
  );
};

/**
 * Cannot use `<iframe onLoad...>` load event in React does not
 * fire properly in Safari/Chrome for iframes
 */
const VFBlockView = React.memo(({html, uniqueId}) => {
  // create iframe
  const iframeId = `vfwp_${uniqueId}`;
  let iframe = document.getElementById(iframeId);
  if (!iframe) {
    iframe = document.createElement('iframe');
    iframe.id = iframeId;
    iframe.className = 'vf-gutenberg-iframe';
    iframe.setAttribute('scrolling', 'no');
  }
  const rootEl = useRef();
  const {onLoad, onUnload} = useIFrame(iframe, html);
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
export const VFBlock = withInstanceId(function(props) {
  const {
    clientId,
    instanceId,
    isSelected,
    hasFooter,
    attributes: {ver, mode, ...attrs}
  } = props;

  const uniqueId = useUniqueId([clientId, instanceId]);

  // Ensure version is encoded in post content
  if (!ver) {
    props.setAttributes({ver: props.ver || 1});
  }

  // No `clientId` if rendered in the sidebar style preview
  const hasMode = clientId && typeof mode === 'string';
  const isEditing = hasMode && mode === 'edit';

  // Hook in conditional against the rules?
  const [data, isLoading] = isEditing
    ? [null, false]
    : useVFPluginData({
        attrs: attrs,
        name: props.name
      });

  const isPreview = !isLoading && data;

  const onToggle = () => {
    props.setAttributes({mode: !isEditing ? 'edit' : 'view'});
  };

  const rootAttr = {
    className: `vf-gutenberg-block ${props.className}`,
    'data-ver': ver,
    'data-name': props.name,
    'data-editing': isEditing,
    'data-loading': isLoading,
    'data-selected': isSelected
  };

  return (
    <React.Fragment>
      {hasMode && <VFBlockControls {...{isEditing, onToggle}} />}
      <div {...rootAttr}>
        {isEditing ? (
          <VFBlockEdit {...{hasFooter, onToggle}} children={props.children} />
        ) : isPreview ? (
          <VFBlockView html={data.html} uniqueId={uniqueId} />
        ) : (
          <Spinner />
        )}
      </div>
    </React.Fragment>
  );
});

/**
 * Automatically map field controls to attributes
 */
export const VFBlockFields = props => {
  const {attributes: attrs, setAttributes, fields} = props;
  const onChange = (name, value) => {
    const attr = {};
    attr[name] = value;
    setAttributes({...attr});
  };
  return fields.map(field => {
    const {name, type, label} = field;
    if (type === 'checkbox') {
      return (
        <BaseControl label={label} className="components-radio-control">
          {field.options.map(option => (
            <div className="components-radio-control__option">
              <CheckboxControl
                label={option.label}
                checked={(attrs[name] || []).includes(option.value)}
                onChange={checked => {
                  const attr = (attrs[name] || []).filter(
                    v => v !== option.value
                  );
                  if (checked) {
                    attr.push(option.value);
                  }
                  onChange(name, attr);
                }}
              />
            </div>
          ))}
        </BaseControl>
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
    if (type === 'toggle') {
      return (
        <ToggleControl
          label={label}
          checked={attrs[name]}
          onChange={value => onChange(name, value ? 1 : 0)}
        />
      );
    }
  });
};
