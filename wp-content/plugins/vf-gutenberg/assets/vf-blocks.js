(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(require('@wordpress/blocks'), require('react'), require('@wordpress/i18n'), require('@wordpress/block-editor'), require('@wordpress/components'), require('@wordpress/data'), require('@wordpress/hooks')) :
  typeof define === 'function' && define.amd ? define(['@wordpress/blocks', 'react', '@wordpress/i18n', '@wordpress/block-editor', '@wordpress/components', '@wordpress/data', '@wordpress/hooks'], factory) :
  (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.wp.blocks, global.React, global.wp.i18n, global.wp.blockEditor, global.wp.components, global.wp.data, global.wp.hooks));
}(this, (function (blocks, React, i18n, blockEditor, components, data$1, hooks) { 'use strict';

  function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

  var React__default = /*#__PURE__*/_interopDefaultLegacy(React);

  /**
   * Return VF Gutenberg settings provided by the `vfGutenberg` global object
   * `wp_localize_script` defines this when enqueueing "vf-blocks.js"
   */
  // Default properties
  const vfGutenberg = {
    renderPrefix: '',
    renderSuffix: '',
    postId: 0,
    nonce: ''
  };

  const useVFGutenberg = () => {
    const vf = window.vfGutenberg || {};

    for (let [key, value] of Object.entries(vfGutenberg)) {
      if (!vf.hasOwnProperty(key)) {
        vf[key] = value;
      }
    }

    return vf;
  };

  function _extends() {
    _extends = Object.assign || function (target) {
      for (var i = 1; i < arguments.length; i++) {
        var source = arguments[i];

        for (var key in source) {
          if (Object.prototype.hasOwnProperty.call(source, key)) {
            target[key] = source[key];
          }
        }
      }

      return target;
    };

    return _extends.apply(this, arguments);
  }

  /**
   * Icon (component)
   * VF Gutenberg block icon
   */
  var Icon = wp.element.createElement(components.SVG, {
    viewBox: "0 0 24 24",
    xmlns: "http://www.w3.org/2000/svg"
  }, wp.element.createElement(components.Path, {
    d: "M19 7h-1V5h-4v2h-4V5H6v2H5c-1.1 0-2 .9-2 2v10h18V9c0-1.1-.9-2-2-2zm0 10H5V9h14v8z"
  }), wp.element.createElement(components.Path, {
    d: "M10.943 16c.522-1.854.972-3.726 1.386-5.58h-1.323a126.482 126.482 0 01-.918 4.383h-.081c-.342-1.44-.657-2.988-.936-4.428l-1.35.09c.414 1.854.873 3.681 1.395 5.535h1.827zm5.161-2.169H14.16V16h-1.332v-5.58h3.636v1.098H14.16v1.296h1.944v1.017z"
  }));

  /**
   * Return default Gutenberg block settings for a VF block
   */

  const useVFDefaults = () => ({
    icon: Icon,
    keywords: [i18n.__('VF'), i18n.__('Visual Framework'), i18n.__('EMBL')],
    attributes: {
      ver: {
        type: 'string'
      }
    },
    supports: {
      align: false,
      className: false,
      customClassName: false,
      html: false
    },
    edit: () => null,
    save: () => null
  });

  /**
   * Object Hashsum
   * https://github.com/bevacqua/hash-sum
   */
  function pad(hash, len) {
    while (hash.length < len) {
      hash = '0' + hash;
    }

    return hash;
  }

  function fold(hash, text) {
    var i;
    var chr;
    var len;

    if (text.length === 0) {
      return hash;
    }

    for (i = 0, len = text.length; i < len; i++) {
      chr = text.charCodeAt(i);
      hash = (hash << 5) - hash + chr;
      hash |= 0;
    }

    return hash < 0 ? hash * -2 : hash;
  }

  function foldObject(hash, o, seen) {
    return Object.keys(o).sort().reduce(foldKey, hash);

    function foldKey(hash, key) {
      return foldValue(hash, o[key], key, seen);
    }
  }

  function foldValue(input, value, key, seen) {
    var hash = fold(fold(fold(input, key), toString$1(value)), typeof value);

    if (value === null) {
      return fold(hash, 'null');
    }

    if (value === undefined) {
      return fold(hash, 'undefined');
    }

    if (typeof value === 'object' || typeof value === 'function') {
      if (seen.indexOf(value) !== -1) {
        return fold(hash, '[Circular]' + key);
      }

      seen.push(value);
      var objHash = foldObject(hash, value, seen);

      if (!('valueOf' in value) || typeof value.valueOf !== 'function') {
        return objHash;
      }

      try {
        return fold(objHash, String(value.valueOf()));
      } catch (err) {
        return fold(objHash, '[valueOf exception]' + (err.stack || err.message));
      }
    }

    return fold(hash, value.toString());
  }

  function toString$1(o) {
    return Object.prototype.toString.call(o);
  }

  function sum(o) {
    return pad(foldValue(0, o, '', []).toString(16), 8);
  }

  /**
   * Misc hooks
   */
  /**
   * Return a unique hash of any object
   */

  const useHashsum = obj => sum(obj);
  /**
   * Return a unique ID for Gutenberg block instance
   */

  const idStore = {};
  const useUniqueId = ({
    clientId,
    name
  }) => {
    const [uniqueId, setUniqueId] = React.useState(null);

    if (!uniqueId) {
      if (!idStore.hasOwnProperty(name)) {
        idStore[name] = 0;
      }

      idStore[name]++;
      setUniqueId(useHashsum([clientId, idStore[name]]));
    }

    return uniqueId;
  };
  /**
   * Return the block style name from the class attribute
   */

  const useStyleName = className => {
    const match = (className || '').match(/is-style-([^\s"]+)/);
    return match ? match[1] : '';
  };

  /**
   * ButtonControl (component)
   * Wrapper for `Button`
   */

  const ButtonControl = props => {
    const {
      help,
      label,
      ...buttonProps
    } = props.field;
    return wp.element.createElement(components.BaseControl, {
      help: help
    }, wp.element.createElement("div", {
      className: "components-base-control__button"
    }, wp.element.createElement(components.Button, buttonProps, label)));
  };

  /**
   * Checkboxes (component)
   * Wrapper for multiple `CheckboxControl`
   */

  const CheckboxesControl = props => {
    const {
      attrs,
      field,
      label,
      name,
      onChange
    } = props;
    return (// Markup similar to `RadioControl` with multiple options
      wp.element.createElement(components.BaseControl, {
        label: label,
        className: "components-radio-control"
      }, field.options.map(option => wp.element.createElement("div", {
        key: useHashsum(option),
        className: "components-radio-control__option"
      }, wp.element.createElement(components.CheckboxControl, {
        label: option.label,
        checked: (attrs[name] || []).includes(option.value),
        onChange: checked => {
          // Remove checkbox value from attribute array
          const attr = (attrs[name] || []).filter(v => v !== option.value); // Re-append value if checked

          if (checked) {
            attr.push(option.value);
          }

          onChange(name, attr);
        }
      }))))
    );
  };

  /**
   * Columns (component)
   * Wrapper for `ButtonGroup` to select number of columns
   */

  const ColumnsControl = props => {
    const {
      value,
      min,
      max,
      onChange
    } = props;
    const control = {
      label: i18n.__('Number of Columns'),
      className: 'components-vf-control'
    };

    if (props.help) {
      control.help = props.help;
    }

    const isPressed = i => i + min === value;

    return wp.element.createElement(components.BaseControl, control, wp.element.createElement(components.ButtonGroup, {
      "aria-label": control.label
    }, Array(max - min + 1).fill().map((x, i) => wp.element.createElement(components.Button, {
      key: i,
      isPrimary: isPressed(i),
      "aria-pressed": isPressed(i),
      onClick: () => onChange(i + min)
    }, i + min))));
  };

  /**
   * DateControl (component)
   * Wrapper for `DateControl`
   */

  const DateControl = props => {
    return wp.element.createElement(components.BaseControl, {
      label: props.label
    }, wp.element.createElement(components.DatePicker, {
      currentDate: props.currentDate,
      onChange: props.onChange
    }));
  };

  /**
   * Fetch an array of terms for a WP taxonomy asynchronously.
   * Cache in store to only fetch once per page load.
   */
  const taxonomyStore = {};

  const useVFTerms = taxonomy => {
    const [terms, setTerms] = React.useState([]);

    const fetchData = async () => {
      if (taxonomyStore.hasOwnProperty(taxonomy)) {
        setTerms(taxonomyStore[taxonomy]);
        return;
      }

      const {
        postId,
        nonce
      } = useVFGutenberg();

      try {
        const data = await wp.ajax.post('vf/gutenberg/fetch_terms', {
          taxonomy,
          postId,
          nonce
        });

        if (data && data.hasOwnProperty('terms')) {
          taxonomyStore[taxonomy] = data.terms;
          setTerms(data.terms);
        }
      } catch (err) {}
    };

    React.useEffect(() => {
      fetchData();
    }, [taxonomy]);
    return terms;
  };

  const TaxonomyControl = props => {
    let options = [{
      label: i18n.__('Loading…'),
      value: props.value
    }]; // Set terms once loaded

    const terms = useVFTerms(props.taxonomy);

    if (terms.length) {
      options = terms.map(term => ({
        label: term.name,
        value: parseInt(term.term_id)
      }));
      options.unshift({
        label: i18n.__('Select…'),
        value: ''
      });
    } // Reset to default value if no term ID is selected


    const onChange = value => {
      const intValue = parseInt(value);
      value = isNaN(intValue) ? '' : intValue;
      props.onChange(value);
    };

    return wp.element.createElement(components.SelectControl, _extends({}, props, {
      onChange: onChange,
      options: options
    }));
  };

  /**
   * URLControl (component)
   * Wrapper for `URLInput`
   */

  const URLControl = props => {
    let className = '';

    if (!props.disableSuggestions) {
      className += 'has-suggestions';
    }

    return wp.element.createElement(components.BaseControl, {
      label: props.label
    }, wp.element.createElement(blockEditor.URLInput, {
      autoFocus: false,
      className: className,
      disableSuggestions: props.disableSuggestions,
      onChange: props.onChange,
      value: props.value
    }));
  };

  /**
   * RichControl (component)
   * Wrapper for `RichText`
   */

  const RichControl = props => {
    return wp.element.createElement(components.BaseControl, {
      label: props.label
    }, wp.element.createElement("div", {
      className: "components-base-control__rich-text"
    }, wp.element.createElement(blockEditor.RichText, {
      tagName: props.tag,
      value: props.value,
      placeholder: props.placeholder,
      onChange: props.onChange
    })));
  };

  /**
   * VFBlockFields (component)
   * Iterate the `fields` property and return an array of WordPress field
   * controls that update their corresponding attributes. The `fields` array
   * is mapped from ACF configuration.
   */

  const DATE_CONTROLS = ['date', 'date_picker'];
  const RICH_CONTROLS = ['rich', 'wysiwyg'];
  const TEXT_CONTROLS = ['text', 'email'];
  const BOOL_CONTROLS = ['bool', 'boolean', 'toggle', 'true_false']; // Fields component

  const VFBlockFields = props => {
    const {
      attributes: attrs,
      setAttributes,
      fields
    } = props; // Generic event handler to update an attribute

    const handleChange = (name, value) => {
      const attr = {};
      attr[name] = value;
      setAttributes({ ...attr
      });
    }; // Add any initial controls from children


    const controls = [];

    if (props.children) {
      controls.push(props.children);
    } // Map fields and add array of controls


    controls.push(fields.map(field => {
      let {
        control,
        help,
        label,
        name,
        onChange
      } = field;
      const key = useHashsum(field); // Fallback to default handler

      onChange = typeof onChange === 'function' ? onChange : handleChange; // The ACF "checkbox" field returns an array of one or more checked
      // values whereas "true_false" (here "toggle") uses a boolean value

      if (control === 'button') {
        return wp.element.createElement(ButtonControl, {
          key: key,
          field: field,
          label: label
        });
      } // The ACF "checkbox" field returns an array of one or more checked
      // values whereas "true_false" (here "toggle") uses a boolean value


      if (control === 'checkbox') {
        return wp.element.createElement(CheckboxesControl, {
          key: key,
          name: name,
          attrs: attrs,
          field: field,
          label: label,
          onChange: onChange
        });
      } // Custom control to manage number of grid columns


      if (control === 'columns') {
        // const min = parseInt(field.min) || 1;
        // const max = parseInt(field.max) || 6;
        // const value = parseInt(field.value) || 0;
        const min = isNaN(field.min) ? 1 : parseInt(field.min);
        const max = isNaN(field.max) ? 6 : parseInt(field.max);
        const value = isNaN(field.value) ? 0 : parseInt(field.value);
        return wp.element.createElement(ColumnsControl, {
          key: key,
          min: min,
          max: max,
          help: help,
          value: value,
          onChange: field.onChange
        });
      }

      if (DATE_CONTROLS.includes(control)) {
        let date = new Date(attrs[name]);

        if (isNaN(date.getTime())) {
          date = Date.now();
        }

        return wp.element.createElement(DateControl, {
          key: key,
          label: label,
          currentDate: date,
          onChange: value => onChange(name, value)
        });
      }

      if (control === 'number') {
        const min = parseInt(field['min']) || undefined;
        const max = parseInt(field['max']) || undefined;
        return wp.element.createElement(components.TextControl, {
          key: key,
          help: help,
          label: label,
          type: "number",
          value: parseInt(attrs[name]) || min,
          onChange: value => onChange(name, parseInt(value)),
          min: min,
          max: max
        });
      }

      if (control === 'radio') {
        return wp.element.createElement(components.RadioControl, {
          key: key,
          label: label,
          selected: attrs[name],
          onChange: value => onChange(name, value),
          options: [...field.options]
        });
      }

      if (control === 'range') {
        const allowReset = !!field.allowReset;
        const min = isNaN(field.min) ? 1 : parseInt(field.min);
        const max = isNaN(field.max) ? 10 : parseInt(field.max);
        const step = isNaN(field.step) ? 1 : parseInt(field.step);
        return wp.element.createElement(components.RangeControl, {
          key: key,
          help: help,
          label: label,
          value: parseInt(attrs[name]) || min,
          onChange: value => onChange(name, value),
          allowReset: allowReset,
          step: step,
          min: min,
          max: max
        });
      }

      if (RICH_CONTROLS.includes(control)) {
        const tag = field.tag || 'p';

        const placeholder = field.placeholder || i18n.__('Type content…');

        return wp.element.createElement(RichControl, {
          key: key,
          label: label,
          value: attrs[name],
          tag: tag,
          placeholder: placeholder,
          onChange: value => onChange(name, value)
        });
      }

      if (control === 'select') {
        return wp.element.createElement(components.SelectControl, {
          key: key,
          label: label,
          value: attrs[name],
          onChange: value => onChange(name, value),
          options: [{
            label: i18n.__('Select…'),
            value: ''
          }, ...field.options]
        });
      }

      if (control === 'taxonomy') {
        return wp.element.createElement(TaxonomyControl, {
          key: key,
          taxonomy: field.taxonomy,
          label: label,
          value: attrs[name],
          onChange: value => onChange(name, value)
        });
      }

      if (TEXT_CONTROLS.includes(control)) {
        return wp.element.createElement(components.TextControl, {
          key: key,
          type: "text",
          label: label,
          value: attrs[name],
          onChange: value => onChange(name, value)
        });
      }

      if (control === 'textarea') {
        return wp.element.createElement(components.TextareaControl, {
          key: key,
          label: label,
          value: attrs[name],
          onChange: value => onChange(name, value)
        });
      } // Return integer value to match ACF field instead of boolean


      if (BOOL_CONTROLS.includes(control)) {
        return wp.element.createElement(components.ToggleControl, {
          key: key,
          help: help,
          label: label,
          checked: attrs[name],
          onChange: value => onChange(name, value ? 1 : 0)
        });
      }

      if (control === 'url') {
        return wp.element.createElement(URLControl, {
          key: key,
          label: label,
          value: attrs[name],
          disableSuggestions: field.disableSuggestions === true,
          onChange: value => onChange(name, value)
        });
      }
    }));
    return controls;
  };

  var commonjsGlobal = typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

  function getDefaultExportFromCjs (x) {
  	return x && x.__esModule && Object.prototype.hasOwnProperty.call(x, 'default') ? x['default'] : x;
  }

  var check = function (it) {
    return it && it.Math == Math && it;
  };

  // https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
  var global$e =
    // eslint-disable-next-line es/no-global-this -- safe
    check(typeof globalThis == 'object' && globalThis) ||
    check(typeof window == 'object' && window) ||
    // eslint-disable-next-line no-restricted-globals -- safe
    check(typeof self == 'object' && self) ||
    check(typeof commonjsGlobal == 'object' && commonjsGlobal) ||
    // eslint-disable-next-line no-new-func -- fallback
    (function () { return this; })() || Function('return this')();

  var objectGetOwnPropertyDescriptor = {};

  var fails$6 = function (exec) {
    try {
      return !!exec();
    } catch (error) {
      return true;
    }
  };

  var fails$5 = fails$6;

  // Detect IE8's incomplete defineProperty implementation
  var descriptors = !fails$5(function () {
    // eslint-disable-next-line es/no-object-defineproperty -- required for testing
    return Object.defineProperty({}, 1, { get: function () { return 7; } })[1] != 7;
  });

  var objectPropertyIsEnumerable = {};

  var $propertyIsEnumerable = {}.propertyIsEnumerable;
  // eslint-disable-next-line es/no-object-getownpropertydescriptor -- safe
  var getOwnPropertyDescriptor$1 = Object.getOwnPropertyDescriptor;

  // Nashorn ~ JDK8 bug
  var NASHORN_BUG = getOwnPropertyDescriptor$1 && !$propertyIsEnumerable.call({ 1: 2 }, 1);

  // `Object.prototype.propertyIsEnumerable` method implementation
  // https://tc39.es/ecma262/#sec-object.prototype.propertyisenumerable
  objectPropertyIsEnumerable.f = NASHORN_BUG ? function propertyIsEnumerable(V) {
    var descriptor = getOwnPropertyDescriptor$1(this, V);
    return !!descriptor && descriptor.enumerable;
  } : $propertyIsEnumerable;

  var createPropertyDescriptor$2 = function (bitmap, value) {
    return {
      enumerable: !(bitmap & 1),
      configurable: !(bitmap & 2),
      writable: !(bitmap & 4),
      value: value
    };
  };

  var toString = {}.toString;

  var classofRaw = function (it) {
    return toString.call(it).slice(8, -1);
  };

  var fails$4 = fails$6;
  var classof$1 = classofRaw;

  var split = ''.split;

  // fallback for non-array-like ES3 and non-enumerable old V8 strings
  var indexedObject = fails$4(function () {
    // throws an error in rhino, see https://github.com/mozilla/rhino/issues/346
    // eslint-disable-next-line no-prototype-builtins -- safe
    return !Object('z').propertyIsEnumerable(0);
  }) ? function (it) {
    return classof$1(it) == 'String' ? split.call(it, '') : Object(it);
  } : Object;

  // `RequireObjectCoercible` abstract operation
  // https://tc39.es/ecma262/#sec-requireobjectcoercible
  var requireObjectCoercible$2 = function (it) {
    if (it == undefined) throw TypeError("Can't call method on " + it);
    return it;
  };

  // toObject with fallback for non-array-like ES3 strings
  var IndexedObject = indexedObject;
  var requireObjectCoercible$1 = requireObjectCoercible$2;

  var toIndexedObject$3 = function (it) {
    return IndexedObject(requireObjectCoercible$1(it));
  };

  var isObject$5 = function (it) {
    return typeof it === 'object' ? it !== null : typeof it === 'function';
  };

  var global$d = global$e;

  var aFunction$2 = function (variable) {
    return typeof variable == 'function' ? variable : undefined;
  };

  var getBuiltIn$4 = function (namespace, method) {
    return arguments.length < 2 ? aFunction$2(global$d[namespace]) : global$d[namespace] && global$d[namespace][method];
  };

  var getBuiltIn$3 = getBuiltIn$4;

  var engineUserAgent = getBuiltIn$3('navigator', 'userAgent') || '';

  var global$c = global$e;
  var userAgent$1 = engineUserAgent;

  var process$2 = global$c.process;
  var Deno = global$c.Deno;
  var versions = process$2 && process$2.versions || Deno && Deno.version;
  var v8 = versions && versions.v8;
  var match, version;

  if (v8) {
    match = v8.split('.');
    version = match[0] < 4 ? 1 : match[0] + match[1];
  } else if (userAgent$1) {
    match = userAgent$1.match(/Edge\/(\d+)/);
    if (!match || match[1] >= 74) {
      match = userAgent$1.match(/Chrome\/(\d+)/);
      if (match) version = match[1];
    }
  }

  var engineV8Version = version && +version;

  /* eslint-disable es/no-symbol -- required for testing */

  var V8_VERSION = engineV8Version;
  var fails$3 = fails$6;

  // eslint-disable-next-line es/no-object-getownpropertysymbols -- required for testing
  var nativeSymbol = !!Object.getOwnPropertySymbols && !fails$3(function () {
    var symbol = Symbol();
    // Chrome 38 Symbol has incorrect toString conversion
    // `get-own-property-symbols` polyfill symbols converted to object are not Symbol instances
    return !String(symbol) || !(Object(symbol) instanceof Symbol) ||
      // Chrome 38-40 symbols are not inherited from DOM collections prototypes to instances
      !Symbol.sham && V8_VERSION && V8_VERSION < 41;
  });

  /* eslint-disable es/no-symbol -- required for testing */

  var NATIVE_SYMBOL$1 = nativeSymbol;

  var useSymbolAsUid = NATIVE_SYMBOL$1
    && !Symbol.sham
    && typeof Symbol.iterator == 'symbol';

  var getBuiltIn$2 = getBuiltIn$4;
  var USE_SYMBOL_AS_UID$1 = useSymbolAsUid;

  var isSymbol$2 = USE_SYMBOL_AS_UID$1 ? function (it) {
    return typeof it == 'symbol';
  } : function (it) {
    var $Symbol = getBuiltIn$2('Symbol');
    return typeof $Symbol == 'function' && Object(it) instanceof $Symbol;
  };

  var isObject$4 = isObject$5;

  // `OrdinaryToPrimitive` abstract operation
  // https://tc39.es/ecma262/#sec-ordinarytoprimitive
  var ordinaryToPrimitive$1 = function (input, pref) {
    var fn, val;
    if (pref === 'string' && typeof (fn = input.toString) == 'function' && !isObject$4(val = fn.call(input))) return val;
    if (typeof (fn = input.valueOf) == 'function' && !isObject$4(val = fn.call(input))) return val;
    if (pref !== 'string' && typeof (fn = input.toString) == 'function' && !isObject$4(val = fn.call(input))) return val;
    throw TypeError("Can't convert object to primitive value");
  };

  var shared$3 = {exports: {}};

  var global$b = global$e;

  var setGlobal$3 = function (key, value) {
    try {
      // eslint-disable-next-line es/no-object-defineproperty -- safe
      Object.defineProperty(global$b, key, { value: value, configurable: true, writable: true });
    } catch (error) {
      global$b[key] = value;
    } return value;
  };

  var global$a = global$e;
  var setGlobal$2 = setGlobal$3;

  var SHARED = '__core-js_shared__';
  var store$3 = global$a[SHARED] || setGlobal$2(SHARED, {});

  var sharedStore = store$3;

  var store$2 = sharedStore;

  (shared$3.exports = function (key, value) {
    return store$2[key] || (store$2[key] = value !== undefined ? value : {});
  })('versions', []).push({
    version: '3.17.3',
    mode: 'global',
    copyright: '© 2021 Denis Pushkarev (zloirock.ru)'
  });

  var requireObjectCoercible = requireObjectCoercible$2;

  // `ToObject` abstract operation
  // https://tc39.es/ecma262/#sec-toobject
  var toObject$1 = function (argument) {
    return Object(requireObjectCoercible(argument));
  };

  var toObject = toObject$1;

  var hasOwnProperty = {}.hasOwnProperty;

  var has$6 = Object.hasOwn || function hasOwn(it, key) {
    return hasOwnProperty.call(toObject(it), key);
  };

  var id = 0;
  var postfix = Math.random();

  var uid$2 = function (key) {
    return 'Symbol(' + String(key === undefined ? '' : key) + ')_' + (++id + postfix).toString(36);
  };

  var global$9 = global$e;
  var shared$2 = shared$3.exports;
  var has$5 = has$6;
  var uid$1 = uid$2;
  var NATIVE_SYMBOL = nativeSymbol;
  var USE_SYMBOL_AS_UID = useSymbolAsUid;

  var WellKnownSymbolsStore = shared$2('wks');
  var Symbol$1 = global$9.Symbol;
  var createWellKnownSymbol = USE_SYMBOL_AS_UID ? Symbol$1 : Symbol$1 && Symbol$1.withoutSetter || uid$1;

  var wellKnownSymbol$1 = function (name) {
    if (!has$5(WellKnownSymbolsStore, name) || !(NATIVE_SYMBOL || typeof WellKnownSymbolsStore[name] == 'string')) {
      if (NATIVE_SYMBOL && has$5(Symbol$1, name)) {
        WellKnownSymbolsStore[name] = Symbol$1[name];
      } else {
        WellKnownSymbolsStore[name] = createWellKnownSymbol('Symbol.' + name);
      }
    } return WellKnownSymbolsStore[name];
  };

  var isObject$3 = isObject$5;
  var isSymbol$1 = isSymbol$2;
  var ordinaryToPrimitive = ordinaryToPrimitive$1;
  var wellKnownSymbol = wellKnownSymbol$1;

  var TO_PRIMITIVE = wellKnownSymbol('toPrimitive');

  // `ToPrimitive` abstract operation
  // https://tc39.es/ecma262/#sec-toprimitive
  var toPrimitive$1 = function (input, pref) {
    if (!isObject$3(input) || isSymbol$1(input)) return input;
    var exoticToPrim = input[TO_PRIMITIVE];
    var result;
    if (exoticToPrim !== undefined) {
      if (pref === undefined) pref = 'default';
      result = exoticToPrim.call(input, pref);
      if (!isObject$3(result) || isSymbol$1(result)) return result;
      throw TypeError("Can't convert object to primitive value");
    }
    if (pref === undefined) pref = 'number';
    return ordinaryToPrimitive(input, pref);
  };

  var toPrimitive = toPrimitive$1;
  var isSymbol = isSymbol$2;

  // `ToPropertyKey` abstract operation
  // https://tc39.es/ecma262/#sec-topropertykey
  var toPropertyKey$2 = function (argument) {
    var key = toPrimitive(argument, 'string');
    return isSymbol(key) ? key : String(key);
  };

  var global$8 = global$e;
  var isObject$2 = isObject$5;

  var document$1 = global$8.document;
  // typeof document.createElement is 'object' in old IE
  var EXISTS = isObject$2(document$1) && isObject$2(document$1.createElement);

  var documentCreateElement = function (it) {
    return EXISTS ? document$1.createElement(it) : {};
  };

  var DESCRIPTORS$3 = descriptors;
  var fails$2 = fails$6;
  var createElement$1 = documentCreateElement;

  // Thank's IE8 for his funny defineProperty
  var ie8DomDefine = !DESCRIPTORS$3 && !fails$2(function () {
    // eslint-disable-next-line es/no-object-defineproperty -- requied for testing
    return Object.defineProperty(createElement$1('div'), 'a', {
      get: function () { return 7; }
    }).a != 7;
  });

  var DESCRIPTORS$2 = descriptors;
  var propertyIsEnumerableModule = objectPropertyIsEnumerable;
  var createPropertyDescriptor$1 = createPropertyDescriptor$2;
  var toIndexedObject$2 = toIndexedObject$3;
  var toPropertyKey$1 = toPropertyKey$2;
  var has$4 = has$6;
  var IE8_DOM_DEFINE$1 = ie8DomDefine;

  // eslint-disable-next-line es/no-object-getownpropertydescriptor -- safe
  var $getOwnPropertyDescriptor = Object.getOwnPropertyDescriptor;

  // `Object.getOwnPropertyDescriptor` method
  // https://tc39.es/ecma262/#sec-object.getownpropertydescriptor
  objectGetOwnPropertyDescriptor.f = DESCRIPTORS$2 ? $getOwnPropertyDescriptor : function getOwnPropertyDescriptor(O, P) {
    O = toIndexedObject$2(O);
    P = toPropertyKey$1(P);
    if (IE8_DOM_DEFINE$1) try {
      return $getOwnPropertyDescriptor(O, P);
    } catch (error) { /* empty */ }
    if (has$4(O, P)) return createPropertyDescriptor$1(!propertyIsEnumerableModule.f.call(O, P), O[P]);
  };

  var objectDefineProperty = {};

  var isObject$1 = isObject$5;

  var anObject$2 = function (it) {
    if (!isObject$1(it)) {
      throw TypeError(String(it) + ' is not an object');
    } return it;
  };

  var DESCRIPTORS$1 = descriptors;
  var IE8_DOM_DEFINE = ie8DomDefine;
  var anObject$1 = anObject$2;
  var toPropertyKey = toPropertyKey$2;

  // eslint-disable-next-line es/no-object-defineproperty -- safe
  var $defineProperty = Object.defineProperty;

  // `Object.defineProperty` method
  // https://tc39.es/ecma262/#sec-object.defineproperty
  objectDefineProperty.f = DESCRIPTORS$1 ? $defineProperty : function defineProperty(O, P, Attributes) {
    anObject$1(O);
    P = toPropertyKey(P);
    anObject$1(Attributes);
    if (IE8_DOM_DEFINE) try {
      return $defineProperty(O, P, Attributes);
    } catch (error) { /* empty */ }
    if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported');
    if ('value' in Attributes) O[P] = Attributes.value;
    return O;
  };

  var DESCRIPTORS = descriptors;
  var definePropertyModule$1 = objectDefineProperty;
  var createPropertyDescriptor = createPropertyDescriptor$2;

  var createNonEnumerableProperty$3 = DESCRIPTORS ? function (object, key, value) {
    return definePropertyModule$1.f(object, key, createPropertyDescriptor(1, value));
  } : function (object, key, value) {
    object[key] = value;
    return object;
  };

  var redefine$1 = {exports: {}};

  var store$1 = sharedStore;

  var functionToString = Function.toString;

  // this helper broken in `core-js@3.4.1-3.4.4`, so we can't use `shared` helper
  if (typeof store$1.inspectSource != 'function') {
    store$1.inspectSource = function (it) {
      return functionToString.call(it);
    };
  }

  var inspectSource$2 = store$1.inspectSource;

  var global$7 = global$e;
  var inspectSource$1 = inspectSource$2;

  var WeakMap$1 = global$7.WeakMap;

  var nativeWeakMap = typeof WeakMap$1 === 'function' && /native code/.test(inspectSource$1(WeakMap$1));

  var shared$1 = shared$3.exports;
  var uid = uid$2;

  var keys = shared$1('keys');

  var sharedKey$1 = function (key) {
    return keys[key] || (keys[key] = uid(key));
  };

  var hiddenKeys$3 = {};

  var NATIVE_WEAK_MAP = nativeWeakMap;
  var global$6 = global$e;
  var isObject = isObject$5;
  var createNonEnumerableProperty$2 = createNonEnumerableProperty$3;
  var objectHas = has$6;
  var shared = sharedStore;
  var sharedKey = sharedKey$1;
  var hiddenKeys$2 = hiddenKeys$3;

  var OBJECT_ALREADY_INITIALIZED = 'Object already initialized';
  var WeakMap = global$6.WeakMap;
  var set$1, get, has$3;

  var enforce = function (it) {
    return has$3(it) ? get(it) : set$1(it, {});
  };

  var getterFor = function (TYPE) {
    return function (it) {
      var state;
      if (!isObject(it) || (state = get(it)).type !== TYPE) {
        throw TypeError('Incompatible receiver, ' + TYPE + ' required');
      } return state;
    };
  };

  if (NATIVE_WEAK_MAP || shared.state) {
    var store = shared.state || (shared.state = new WeakMap());
    var wmget = store.get;
    var wmhas = store.has;
    var wmset = store.set;
    set$1 = function (it, metadata) {
      if (wmhas.call(store, it)) throw new TypeError(OBJECT_ALREADY_INITIALIZED);
      metadata.facade = it;
      wmset.call(store, it, metadata);
      return metadata;
    };
    get = function (it) {
      return wmget.call(store, it) || {};
    };
    has$3 = function (it) {
      return wmhas.call(store, it);
    };
  } else {
    var STATE = sharedKey('state');
    hiddenKeys$2[STATE] = true;
    set$1 = function (it, metadata) {
      if (objectHas(it, STATE)) throw new TypeError(OBJECT_ALREADY_INITIALIZED);
      metadata.facade = it;
      createNonEnumerableProperty$2(it, STATE, metadata);
      return metadata;
    };
    get = function (it) {
      return objectHas(it, STATE) ? it[STATE] : {};
    };
    has$3 = function (it) {
      return objectHas(it, STATE);
    };
  }

  var internalState = {
    set: set$1,
    get: get,
    has: has$3,
    enforce: enforce,
    getterFor: getterFor
  };

  var global$5 = global$e;
  var createNonEnumerableProperty$1 = createNonEnumerableProperty$3;
  var has$2 = has$6;
  var setGlobal$1 = setGlobal$3;
  var inspectSource = inspectSource$2;
  var InternalStateModule = internalState;

  var getInternalState = InternalStateModule.get;
  var enforceInternalState = InternalStateModule.enforce;
  var TEMPLATE = String(String).split('String');

  (redefine$1.exports = function (O, key, value, options) {
    var unsafe = options ? !!options.unsafe : false;
    var simple = options ? !!options.enumerable : false;
    var noTargetGet = options ? !!options.noTargetGet : false;
    var state;
    if (typeof value == 'function') {
      if (typeof key == 'string' && !has$2(value, 'name')) {
        createNonEnumerableProperty$1(value, 'name', key);
      }
      state = enforceInternalState(value);
      if (!state.source) {
        state.source = TEMPLATE.join(typeof key == 'string' ? key : '');
      }
    }
    if (O === global$5) {
      if (simple) O[key] = value;
      else setGlobal$1(key, value);
      return;
    } else if (!unsafe) {
      delete O[key];
    } else if (!noTargetGet && O[key]) {
      simple = true;
    }
    if (simple) O[key] = value;
    else createNonEnumerableProperty$1(O, key, value);
  // add fake Function#toString for correct work wrapped methods / constructors with methods like LoDash isNative
  })(Function.prototype, 'toString', function toString() {
    return typeof this == 'function' && getInternalState(this).source || inspectSource(this);
  });

  var objectGetOwnPropertyNames = {};

  var ceil = Math.ceil;
  var floor = Math.floor;

  // `ToInteger` abstract operation
  // https://tc39.es/ecma262/#sec-tointeger
  var toInteger$2 = function (argument) {
    return isNaN(argument = +argument) ? 0 : (argument > 0 ? floor : ceil)(argument);
  };

  var toInteger$1 = toInteger$2;

  var min$1 = Math.min;

  // `ToLength` abstract operation
  // https://tc39.es/ecma262/#sec-tolength
  var toLength$1 = function (argument) {
    return argument > 0 ? min$1(toInteger$1(argument), 0x1FFFFFFFFFFFFF) : 0; // 2 ** 53 - 1 == 9007199254740991
  };

  var toInteger = toInteger$2;

  var max = Math.max;
  var min = Math.min;

  // Helper for a popular repeating case of the spec:
  // Let integer be ? ToInteger(index).
  // If integer < 0, let result be max((length + integer), 0); else let result be min(integer, length).
  var toAbsoluteIndex$1 = function (index, length) {
    var integer = toInteger(index);
    return integer < 0 ? max(integer + length, 0) : min(integer, length);
  };

  var toIndexedObject$1 = toIndexedObject$3;
  var toLength = toLength$1;
  var toAbsoluteIndex = toAbsoluteIndex$1;

  // `Array.prototype.{ indexOf, includes }` methods implementation
  var createMethod = function (IS_INCLUDES) {
    return function ($this, el, fromIndex) {
      var O = toIndexedObject$1($this);
      var length = toLength(O.length);
      var index = toAbsoluteIndex(fromIndex, length);
      var value;
      // Array#includes uses SameValueZero equality algorithm
      // eslint-disable-next-line no-self-compare -- NaN check
      if (IS_INCLUDES && el != el) while (length > index) {
        value = O[index++];
        // eslint-disable-next-line no-self-compare -- NaN check
        if (value != value) return true;
      // Array#indexOf ignores holes, Array#includes - not
      } else for (;length > index; index++) {
        if ((IS_INCLUDES || index in O) && O[index] === el) return IS_INCLUDES || index || 0;
      } return !IS_INCLUDES && -1;
    };
  };

  var arrayIncludes = {
    // `Array.prototype.includes` method
    // https://tc39.es/ecma262/#sec-array.prototype.includes
    includes: createMethod(true),
    // `Array.prototype.indexOf` method
    // https://tc39.es/ecma262/#sec-array.prototype.indexof
    indexOf: createMethod(false)
  };

  var has$1 = has$6;
  var toIndexedObject = toIndexedObject$3;
  var indexOf = arrayIncludes.indexOf;
  var hiddenKeys$1 = hiddenKeys$3;

  var objectKeysInternal = function (object, names) {
    var O = toIndexedObject(object);
    var i = 0;
    var result = [];
    var key;
    for (key in O) !has$1(hiddenKeys$1, key) && has$1(O, key) && result.push(key);
    // Don't enum bug & hidden keys
    while (names.length > i) if (has$1(O, key = names[i++])) {
      ~indexOf(result, key) || result.push(key);
    }
    return result;
  };

  // IE8- don't enum bug keys
  var enumBugKeys$1 = [
    'constructor',
    'hasOwnProperty',
    'isPrototypeOf',
    'propertyIsEnumerable',
    'toLocaleString',
    'toString',
    'valueOf'
  ];

  var internalObjectKeys = objectKeysInternal;
  var enumBugKeys = enumBugKeys$1;

  var hiddenKeys = enumBugKeys.concat('length', 'prototype');

  // `Object.getOwnPropertyNames` method
  // https://tc39.es/ecma262/#sec-object.getownpropertynames
  // eslint-disable-next-line es/no-object-getownpropertynames -- safe
  objectGetOwnPropertyNames.f = Object.getOwnPropertyNames || function getOwnPropertyNames(O) {
    return internalObjectKeys(O, hiddenKeys);
  };

  var objectGetOwnPropertySymbols = {};

  // eslint-disable-next-line es/no-object-getownpropertysymbols -- safe
  objectGetOwnPropertySymbols.f = Object.getOwnPropertySymbols;

  var getBuiltIn$1 = getBuiltIn$4;
  var getOwnPropertyNamesModule = objectGetOwnPropertyNames;
  var getOwnPropertySymbolsModule = objectGetOwnPropertySymbols;
  var anObject = anObject$2;

  // all object keys, includes non-enumerable and symbols
  var ownKeys$1 = getBuiltIn$1('Reflect', 'ownKeys') || function ownKeys(it) {
    var keys = getOwnPropertyNamesModule.f(anObject(it));
    var getOwnPropertySymbols = getOwnPropertySymbolsModule.f;
    return getOwnPropertySymbols ? keys.concat(getOwnPropertySymbols(it)) : keys;
  };

  var has = has$6;
  var ownKeys = ownKeys$1;
  var getOwnPropertyDescriptorModule = objectGetOwnPropertyDescriptor;
  var definePropertyModule = objectDefineProperty;

  var copyConstructorProperties$1 = function (target, source) {
    var keys = ownKeys(source);
    var defineProperty = definePropertyModule.f;
    var getOwnPropertyDescriptor = getOwnPropertyDescriptorModule.f;
    for (var i = 0; i < keys.length; i++) {
      var key = keys[i];
      if (!has(target, key)) defineProperty(target, key, getOwnPropertyDescriptor(source, key));
    }
  };

  var fails$1 = fails$6;

  var replacement = /#|\.prototype\./;

  var isForced$1 = function (feature, detection) {
    var value = data[normalize(feature)];
    return value == POLYFILL ? true
      : value == NATIVE ? false
      : typeof detection == 'function' ? fails$1(detection)
      : !!detection;
  };

  var normalize = isForced$1.normalize = function (string) {
    return String(string).replace(replacement, '.').toLowerCase();
  };

  var data = isForced$1.data = {};
  var NATIVE = isForced$1.NATIVE = 'N';
  var POLYFILL = isForced$1.POLYFILL = 'P';

  var isForced_1 = isForced$1;

  var global$4 = global$e;
  var getOwnPropertyDescriptor = objectGetOwnPropertyDescriptor.f;
  var createNonEnumerableProperty = createNonEnumerableProperty$3;
  var redefine = redefine$1.exports;
  var setGlobal = setGlobal$3;
  var copyConstructorProperties = copyConstructorProperties$1;
  var isForced = isForced_1;

  /*
    options.target      - name of the target object
    options.global      - target is the global object
    options.stat        - export as static methods of target
    options.proto       - export as prototype methods of target
    options.real        - real prototype method for the `pure` version
    options.forced      - export even if the native feature is available
    options.bind        - bind methods to the target, required for the `pure` version
    options.wrap        - wrap constructors to preventing global pollution, required for the `pure` version
    options.unsafe      - use the simple assignment of property instead of delete + defineProperty
    options.sham        - add a flag to not completely full polyfills
    options.enumerable  - export as enumerable property
    options.noTargetGet - prevent calling a getter on target
  */
  var _export = function (options, source) {
    var TARGET = options.target;
    var GLOBAL = options.global;
    var STATIC = options.stat;
    var FORCED, target, key, targetProperty, sourceProperty, descriptor;
    if (GLOBAL) {
      target = global$4;
    } else if (STATIC) {
      target = global$4[TARGET] || setGlobal(TARGET, {});
    } else {
      target = (global$4[TARGET] || {}).prototype;
    }
    if (target) for (key in source) {
      sourceProperty = source[key];
      if (options.noTargetGet) {
        descriptor = getOwnPropertyDescriptor(target, key);
        targetProperty = descriptor && descriptor.value;
      } else targetProperty = target[key];
      FORCED = isForced(GLOBAL ? key : TARGET + (STATIC ? '.' : '#') + key, options.forced);
      // contained in target
      if (!FORCED && targetProperty !== undefined) {
        if (typeof sourceProperty === typeof targetProperty) continue;
        copyConstructorProperties(sourceProperty, targetProperty);
      }
      // add a flag to not completely full polyfills
      if (options.sham || (targetProperty && targetProperty.sham)) {
        createNonEnumerableProperty(sourceProperty, 'sham', true);
      }
      // extend global
      redefine(target, key, sourceProperty, options);
    }
  };

  var aFunction$1 = function (it) {
    if (typeof it != 'function') {
      throw TypeError(String(it) + ' is not a function');
    } return it;
  };

  var aFunction = aFunction$1;

  // optional / simple context binding
  var functionBindContext = function (fn, that, length) {
    aFunction(fn);
    if (that === undefined) return fn;
    switch (length) {
      case 0: return function () {
        return fn.call(that);
      };
      case 1: return function (a) {
        return fn.call(that, a);
      };
      case 2: return function (a, b) {
        return fn.call(that, a, b);
      };
      case 3: return function (a, b, c) {
        return fn.call(that, a, b, c);
      };
    }
    return function (/* ...args */) {
      return fn.apply(that, arguments);
    };
  };

  var getBuiltIn = getBuiltIn$4;

  var html$1 = getBuiltIn('document', 'documentElement');

  var userAgent = engineUserAgent;

  var engineIsIos = /(?:ipad|iphone|ipod).*applewebkit/i.test(userAgent);

  var classof = classofRaw;
  var global$3 = global$e;

  var engineIsNode = classof(global$3.process) == 'process';

  var global$2 = global$e;
  var fails = fails$6;
  var bind = functionBindContext;
  var html = html$1;
  var createElement = documentCreateElement;
  var IS_IOS = engineIsIos;
  var IS_NODE = engineIsNode;

  var set = global$2.setImmediate;
  var clear = global$2.clearImmediate;
  var process$1 = global$2.process;
  var MessageChannel = global$2.MessageChannel;
  var Dispatch = global$2.Dispatch;
  var counter = 0;
  var queue = {};
  var ONREADYSTATECHANGE = 'onreadystatechange';
  var location, defer, channel, port;

  try {
    // Deno throws a ReferenceError on `location` access without `--location` flag
    location = global$2.location;
  } catch (error) { /* empty */ }

  var run = function (id) {
    // eslint-disable-next-line no-prototype-builtins -- safe
    if (queue.hasOwnProperty(id)) {
      var fn = queue[id];
      delete queue[id];
      fn();
    }
  };

  var runner = function (id) {
    return function () {
      run(id);
    };
  };

  var listener = function (event) {
    run(event.data);
  };

  var post = function (id) {
    // old engines have not location.origin
    global$2.postMessage(String(id), location.protocol + '//' + location.host);
  };

  // Node.js 0.9+ & IE10+ has setImmediate, otherwise:
  if (!set || !clear) {
    set = function setImmediate(fn) {
      var args = [];
      var argumentsLength = arguments.length;
      var i = 1;
      while (argumentsLength > i) args.push(arguments[i++]);
      queue[++counter] = function () {
        // eslint-disable-next-line no-new-func -- spec requirement
        (typeof fn == 'function' ? fn : Function(fn)).apply(undefined, args);
      };
      defer(counter);
      return counter;
    };
    clear = function clearImmediate(id) {
      delete queue[id];
    };
    // Node.js 0.8-
    if (IS_NODE) {
      defer = function (id) {
        process$1.nextTick(runner(id));
      };
    // Sphere (JS game engine) Dispatch API
    } else if (Dispatch && Dispatch.now) {
      defer = function (id) {
        Dispatch.now(runner(id));
      };
    // Browsers with MessageChannel, includes WebWorkers
    // except iOS - https://github.com/zloirock/core-js/issues/624
    } else if (MessageChannel && !IS_IOS) {
      channel = new MessageChannel();
      port = channel.port2;
      channel.port1.onmessage = listener;
      defer = bind(port.postMessage, port, 1);
    // Browsers with postMessage, skip WebWorkers
    // IE8 has postMessage, but it's sync & typeof its postMessage is 'object'
    } else if (
      global$2.addEventListener &&
      typeof postMessage == 'function' &&
      !global$2.importScripts &&
      location && location.protocol !== 'file:' &&
      !fails(post)
    ) {
      defer = post;
      global$2.addEventListener('message', listener, false);
    // IE8-
    } else if (ONREADYSTATECHANGE in createElement('script')) {
      defer = function (id) {
        html.appendChild(createElement('script'))[ONREADYSTATECHANGE] = function () {
          html.removeChild(this);
          run(id);
        };
      };
    // Rest old browsers
    } else {
      defer = function (id) {
        setTimeout(runner(id), 0);
      };
    }
  }

  var task$1 = {
    set: set,
    clear: clear
  };

  var $ = _export;
  var global$1 = global$e;
  var task = task$1;

  var FORCED = !global$1.setImmediate || !global$1.clearImmediate;

  // http://w3c.github.io/setImmediate/
  $({ global: true, bind: true, enumerable: true, forced: FORCED }, {
    // `setImmediate` method
    // http://w3c.github.io/setImmediate/#si-setImmediate
    setImmediate: task.set,
    // `clearImmediate` method
    // http://w3c.github.io/setImmediate/#si-clearImmediate
    clearImmediate: task.clear
  });

  var nunjucksSlim = {exports: {}};

  (function (module, exports) {
    (function webpackUniversalModuleDefinition(root, factory) {
      module.exports = factory();
    })(typeof self !== 'undefined' ? self : commonjsGlobal, function () {
      return (
        /******/
        function (modules) {
          // webpackBootstrap

          /******/
          // The module cache

          /******/
          var installedModules = {};
          /******/

          /******/
          // The require function

          /******/

          function __webpack_require__(moduleId) {
            /******/

            /******/
            // Check if module is in cache

            /******/
            if (installedModules[moduleId]) {
              /******/
              return installedModules[moduleId].exports;
              /******/
            }
            /******/
            // Create a new module (and put it into the cache)

            /******/


            var module = installedModules[moduleId] = {
              /******/
              i: moduleId,

              /******/
              l: false,

              /******/
              exports: {}
              /******/

            };
            /******/

            /******/
            // Execute the module function

            /******/

            modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
            /******/

            /******/
            // Flag the module as loaded

            /******/

            module.l = true;
            /******/

            /******/
            // Return the exports of the module

            /******/

            return module.exports;
            /******/
          }
          /******/

          /******/

          /******/
          // expose the modules object (__webpack_modules__)

          /******/


          __webpack_require__.m = modules;
          /******/

          /******/
          // expose the module cache

          /******/

          __webpack_require__.c = installedModules;
          /******/

          /******/
          // define getter function for harmony exports

          /******/

          __webpack_require__.d = function (exports, name, getter) {
            /******/
            if (!__webpack_require__.o(exports, name)) {
              /******/
              Object.defineProperty(exports, name, {
                /******/
                configurable: false,

                /******/
                enumerable: true,

                /******/
                get: getter
                /******/

              });
              /******/
            }
            /******/

          };
          /******/

          /******/
          // getDefaultExport function for compatibility with non-harmony modules

          /******/


          __webpack_require__.n = function (module) {
            /******/
            var getter = module && module.__esModule ?
            /******/
            function getDefault() {
              return module['default'];
            } :
            /******/
            function getModuleExports() {
              return module;
            };
            /******/

            __webpack_require__.d(getter, 'a', getter);
            /******/


            return getter;
            /******/
          };
          /******/

          /******/
          // Object.prototype.hasOwnProperty.call

          /******/


          __webpack_require__.o = function (object, property) {
            return Object.prototype.hasOwnProperty.call(object, property);
          };
          /******/

          /******/
          // __webpack_public_path__

          /******/


          __webpack_require__.p = "";
          /******/

          /******/
          // Load entry module and return exports

          /******/

          return __webpack_require__(__webpack_require__.s = 6);
          /******/
        }([
          /* 0 */

          /***/

          /* 1 */

          /***/

          /* 2 */

          /***/

          /* 3 */

          /***/

          /* 4 */

          /***/

          /* 5 */

          /***/

          /* 6 */

          /***/

          /* 7 */

          /***/

          /* 8 */

          /***/

          /* 9 */

          /***/

          /* 10 */

          /***/

          /* 11 */

          /***/

          /* 12 */

          /***/

          /* 13 */

          /***/

          /* 14 */

          /***/

          /* 15 */

          /***/

          /* 16 */

          /***/

          /* 17 */

          /***/

          /******/
        function (module, exports) {
          /***/
        }, function (module, exports, __webpack_require__) {

          var ArrayProto = Array.prototype;
          var ObjProto = Object.prototype;
          var escapeMap = {
            '&': '&amp;',
            '"': '&quot;',
            '\'': '&#39;',
            '<': '&lt;',
            '>': '&gt;'
          };
          var escapeRegex = /[&"'<>]/g;
          var exports = module.exports = {};

          function hasOwnProp(obj, k) {
            return ObjProto.hasOwnProperty.call(obj, k);
          }

          exports.hasOwnProp = hasOwnProp;

          function lookupEscape(ch) {
            return escapeMap[ch];
          }

          function _prettifyError(path, withInternals, err) {
            if (!err.Update) {
              // not one of ours, cast it
              err = new exports.TemplateError(err);
            }

            err.Update(path); // Unless they marked the dev flag, show them a trace from here

            if (!withInternals) {
              var old = err;
              err = new Error(old.message);
              err.name = old.name;
            }

            return err;
          }

          exports._prettifyError = _prettifyError;

          function TemplateError(message, lineno, colno) {
            var err;
            var cause;

            if (message instanceof Error) {
              cause = message;
              message = cause.name + ": " + cause.message;
            }

            if (Object.setPrototypeOf) {
              err = new Error(message);
              Object.setPrototypeOf(err, TemplateError.prototype);
            } else {
              err = this;
              Object.defineProperty(err, 'message', {
                enumerable: false,
                writable: true,
                value: message
              });
            }

            Object.defineProperty(err, 'name', {
              value: 'Template render error'
            });

            if (Error.captureStackTrace) {
              Error.captureStackTrace(err, this.constructor);
            }

            var getStack;

            if (cause) {
              var stackDescriptor = Object.getOwnPropertyDescriptor(cause, 'stack');

              getStack = stackDescriptor && (stackDescriptor.get || function () {
                return stackDescriptor.value;
              });

              if (!getStack) {
                getStack = function getStack() {
                  return cause.stack;
                };
              }
            } else {
              var stack = new Error(message).stack;

              getStack = function getStack() {
                return stack;
              };
            }

            Object.defineProperty(err, 'stack', {
              get: function get() {
                return getStack.call(err);
              }
            });
            Object.defineProperty(err, 'cause', {
              value: cause
            });
            err.lineno = lineno;
            err.colno = colno;
            err.firstUpdate = true;

            err.Update = function Update(path) {
              var msg = '(' + (path || 'unknown path') + ')'; // only show lineno + colno next to path of template
              // where error occurred

              if (this.firstUpdate) {
                if (this.lineno && this.colno) {
                  msg += " [Line " + this.lineno + ", Column " + this.colno + "]";
                } else if (this.lineno) {
                  msg += " [Line " + this.lineno + "]";
                }
              }

              msg += '\n ';

              if (this.firstUpdate) {
                msg += ' ';
              }

              this.message = msg + (this.message || '');
              this.firstUpdate = false;
              return this;
            };

            return err;
          }

          if (Object.setPrototypeOf) {
            Object.setPrototypeOf(TemplateError.prototype, Error.prototype);
          } else {
            TemplateError.prototype = Object.create(Error.prototype, {
              constructor: {
                value: TemplateError
              }
            });
          }

          exports.TemplateError = TemplateError;

          function escape(val) {
            return val.replace(escapeRegex, lookupEscape);
          }

          exports.escape = escape;

          function isFunction(obj) {
            return ObjProto.toString.call(obj) === '[object Function]';
          }

          exports.isFunction = isFunction;

          function isArray(obj) {
            return ObjProto.toString.call(obj) === '[object Array]';
          }

          exports.isArray = isArray;

          function isString(obj) {
            return ObjProto.toString.call(obj) === '[object String]';
          }

          exports.isString = isString;

          function isObject(obj) {
            return ObjProto.toString.call(obj) === '[object Object]';
          }

          exports.isObject = isObject;

          function groupBy(obj, val) {
            var result = {};
            var iterator = isFunction(val) ? val : function (o) {
              return o[val];
            };

            for (var i = 0; i < obj.length; i++) {
              var value = obj[i];
              var key = iterator(value, i);
              (result[key] || (result[key] = [])).push(value);
            }

            return result;
          }

          exports.groupBy = groupBy;

          function toArray(obj) {
            return Array.prototype.slice.call(obj);
          }

          exports.toArray = toArray;

          function without(array) {
            var result = [];

            if (!array) {
              return result;
            }

            var length = array.length;
            var contains = toArray(arguments).slice(1);
            var index = -1;

            while (++index < length) {
              if (indexOf(contains, array[index]) === -1) {
                result.push(array[index]);
              }
            }

            return result;
          }

          exports.without = without;

          function repeat(char_, n) {
            var str = '';

            for (var i = 0; i < n; i++) {
              str += char_;
            }

            return str;
          }

          exports.repeat = repeat;

          function each(obj, func, context) {
            if (obj == null) {
              return;
            }

            if (ArrayProto.forEach && obj.forEach === ArrayProto.forEach) {
              obj.forEach(func, context);
            } else if (obj.length === +obj.length) {
              for (var i = 0, l = obj.length; i < l; i++) {
                func.call(context, obj[i], i, obj);
              }
            }
          }

          exports.each = each;

          function map(obj, func) {
            var results = [];

            if (obj == null) {
              return results;
            }

            if (ArrayProto.map && obj.map === ArrayProto.map) {
              return obj.map(func);
            }

            for (var i = 0; i < obj.length; i++) {
              results[results.length] = func(obj[i], i);
            }

            if (obj.length === +obj.length) {
              results.length = obj.length;
            }

            return results;
          }

          exports.map = map;

          function asyncIter(arr, iter, cb) {
            var i = -1;

            function next() {
              i++;

              if (i < arr.length) {
                iter(arr[i], i, next, cb);
              } else {
                cb();
              }
            }

            next();
          }

          exports.asyncIter = asyncIter;

          function asyncFor(obj, iter, cb) {
            var keys = keys_(obj || {});
            var len = keys.length;
            var i = -1;

            function next() {
              i++;
              var k = keys[i];

              if (i < len) {
                iter(k, obj[k], i, len, next);
              } else {
                cb();
              }
            }

            next();
          }

          exports.asyncFor = asyncFor;

          function indexOf(arr, searchElement, fromIndex) {
            return Array.prototype.indexOf.call(arr || [], searchElement, fromIndex);
          }

          exports.indexOf = indexOf;

          function keys_(obj) {
            /* eslint-disable no-restricted-syntax */
            var arr = [];

            for (var k in obj) {
              if (hasOwnProp(obj, k)) {
                arr.push(k);
              }
            }

            return arr;
          }

          exports.keys = keys_;

          function _entries(obj) {
            return keys_(obj).map(function (k) {
              return [k, obj[k]];
            });
          }

          exports._entries = _entries;

          function _values(obj) {
            return keys_(obj).map(function (k) {
              return obj[k];
            });
          }

          exports._values = _values;

          function extend(obj1, obj2) {
            obj1 = obj1 || {};
            keys_(obj2).forEach(function (k) {
              obj1[k] = obj2[k];
            });
            return obj1;
          }

          exports._assign = exports.extend = extend;

          function inOperator(key, val) {
            if (isArray(val) || isString(val)) {
              return val.indexOf(key) !== -1;
            } else if (isObject(val)) {
              return key in val;
            }

            throw new Error('Cannot use "in" operator to search for "' + key + '" in unexpected types.');
          }

          exports.inOperator = inOperator;
          /***/
        }, function (module, exports, __webpack_require__) {

          var lib = __webpack_require__(1);

          var arrayFrom = Array.from;
          var supportsIterators = typeof Symbol === 'function' && Symbol.iterator && typeof arrayFrom === 'function'; // Frames keep track of scoping both at compile-time and run-time so
          // we know how to access variables. Block tags can introduce special
          // variables, for example.

          var Frame = /*#__PURE__*/function () {
            function Frame(parent, isolateWrites) {
              this.variables = {};
              this.parent = parent;
              this.topLevel = false; // if this is true, writes (set) should never propagate upwards past
              // this frame to its parent (though reads may).

              this.isolateWrites = isolateWrites;
            }

            var _proto = Frame.prototype;

            _proto.set = function set(name, val, resolveUp) {
              // Allow variables with dots by automatically creating the
              // nested structure
              var parts = name.split('.');
              var obj = this.variables;
              var frame = this;

              if (resolveUp) {
                if (frame = this.resolve(parts[0], true)) {
                  frame.set(name, val);
                  return;
                }
              }

              for (var i = 0; i < parts.length - 1; i++) {
                var id = parts[i];

                if (!obj[id]) {
                  obj[id] = {};
                }

                obj = obj[id];
              }

              obj[parts[parts.length - 1]] = val;
            };

            _proto.get = function get(name) {
              var val = this.variables[name];

              if (val !== undefined) {
                return val;
              }

              return null;
            };

            _proto.lookup = function lookup(name) {
              var p = this.parent;
              var val = this.variables[name];

              if (val !== undefined) {
                return val;
              }

              return p && p.lookup(name);
            };

            _proto.resolve = function resolve(name, forWrite) {
              var p = forWrite && this.isolateWrites ? undefined : this.parent;
              var val = this.variables[name];

              if (val !== undefined) {
                return this;
              }

              return p && p.resolve(name);
            };

            _proto.push = function push(isolateWrites) {
              return new Frame(this, isolateWrites);
            };

            _proto.pop = function pop() {
              return this.parent;
            };

            return Frame;
          }();

          function makeMacro(argNames, kwargNames, func) {
            var _this = this;

            return function () {
              for (var _len = arguments.length, macroArgs = new Array(_len), _key = 0; _key < _len; _key++) {
                macroArgs[_key] = arguments[_key];
              }

              var argCount = numArgs(macroArgs);
              var args;
              var kwargs = getKeywordArgs(macroArgs);

              if (argCount > argNames.length) {
                args = macroArgs.slice(0, argNames.length); // Positional arguments that should be passed in as
                // keyword arguments (essentially default values)

                macroArgs.slice(args.length, argCount).forEach(function (val, i) {
                  if (i < kwargNames.length) {
                    kwargs[kwargNames[i]] = val;
                  }
                });
                args.push(kwargs);
              } else if (argCount < argNames.length) {
                args = macroArgs.slice(0, argCount);

                for (var i = argCount; i < argNames.length; i++) {
                  var arg = argNames[i]; // Keyword arguments that should be passed as
                  // positional arguments, i.e. the caller explicitly
                  // used the name of a positional arg

                  args.push(kwargs[arg]);
                  delete kwargs[arg];
                }

                args.push(kwargs);
              } else {
                args = macroArgs;
              }

              return func.apply(_this, args);
            };
          }

          function makeKeywordArgs(obj) {
            obj.__keywords = true;
            return obj;
          }

          function isKeywordArgs(obj) {
            return obj && Object.prototype.hasOwnProperty.call(obj, '__keywords');
          }

          function getKeywordArgs(args) {
            var len = args.length;

            if (len) {
              var lastArg = args[len - 1];

              if (isKeywordArgs(lastArg)) {
                return lastArg;
              }
            }

            return {};
          }

          function numArgs(args) {
            var len = args.length;

            if (len === 0) {
              return 0;
            }

            var lastArg = args[len - 1];

            if (isKeywordArgs(lastArg)) {
              return len - 1;
            } else {
              return len;
            }
          } // A SafeString object indicates that the string should not be
          // autoescaped. This happens magically because autoescaping only
          // occurs on primitive string objects.


          function SafeString(val) {
            if (typeof val !== 'string') {
              return val;
            }

            this.val = val;
            this.length = val.length;
          }

          SafeString.prototype = Object.create(String.prototype, {
            length: {
              writable: true,
              configurable: true,
              value: 0
            }
          });

          SafeString.prototype.valueOf = function valueOf() {
            return this.val;
          };

          SafeString.prototype.toString = function toString() {
            return this.val;
          };

          function copySafeness(dest, target) {
            if (dest instanceof SafeString) {
              return new SafeString(target);
            }

            return target.toString();
          }

          function markSafe(val) {
            var type = typeof val;

            if (type === 'string') {
              return new SafeString(val);
            } else if (type !== 'function') {
              return val;
            } else {
              return function wrapSafe(args) {
                var ret = val.apply(this, arguments);

                if (typeof ret === 'string') {
                  return new SafeString(ret);
                }

                return ret;
              };
            }
          }

          function suppressValue(val, autoescape) {
            val = val !== undefined && val !== null ? val : '';

            if (autoescape && !(val instanceof SafeString)) {
              val = lib.escape(val.toString());
            }

            return val;
          }

          function ensureDefined(val, lineno, colno) {
            if (val === null || val === undefined) {
              throw new lib.TemplateError('attempted to output null or undefined value', lineno + 1, colno + 1);
            }

            return val;
          }

          function memberLookup(obj, val) {
            if (obj === undefined || obj === null) {
              return undefined;
            }

            if (typeof obj[val] === 'function') {
              return function () {
                for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
                  args[_key2] = arguments[_key2];
                }

                return obj[val].apply(obj, args);
              };
            }

            return obj[val];
          }

          function callWrap(obj, name, context, args) {
            if (!obj) {
              throw new Error('Unable to call `' + name + '`, which is undefined or falsey');
            } else if (typeof obj !== 'function') {
              throw new Error('Unable to call `' + name + '`, which is not a function');
            }

            return obj.apply(context, args);
          }

          function contextOrFrameLookup(context, frame, name) {
            var val = frame.lookup(name);
            return val !== undefined ? val : context.lookup(name);
          }

          function handleError(error, lineno, colno) {
            if (error.lineno) {
              return error;
            } else {
              return new lib.TemplateError(error, lineno, colno);
            }
          }

          function asyncEach(arr, dimen, iter, cb) {
            if (lib.isArray(arr)) {
              var len = arr.length;
              lib.asyncIter(arr, function iterCallback(item, i, next) {
                switch (dimen) {
                  case 1:
                    iter(item, i, len, next);
                    break;

                  case 2:
                    iter(item[0], item[1], i, len, next);
                    break;

                  case 3:
                    iter(item[0], item[1], item[2], i, len, next);
                    break;

                  default:
                    item.push(i, len, next);
                    iter.apply(this, item);
                }
              }, cb);
            } else {
              lib.asyncFor(arr, function iterCallback(key, val, i, len, next) {
                iter(key, val, i, len, next);
              }, cb);
            }
          }

          function asyncAll(arr, dimen, func, cb) {
            var finished = 0;
            var len;
            var outputArr;

            function done(i, output) {
              finished++;
              outputArr[i] = output;

              if (finished === len) {
                cb(null, outputArr.join(''));
              }
            }

            if (lib.isArray(arr)) {
              len = arr.length;
              outputArr = new Array(len);

              if (len === 0) {
                cb(null, '');
              } else {
                for (var i = 0; i < arr.length; i++) {
                  var item = arr[i];

                  switch (dimen) {
                    case 1:
                      func(item, i, len, done);
                      break;

                    case 2:
                      func(item[0], item[1], i, len, done);
                      break;

                    case 3:
                      func(item[0], item[1], item[2], i, len, done);
                      break;

                    default:
                      item.push(i, len, done);
                      func.apply(this, item);
                  }
                }
              }
            } else {
              var keys = lib.keys(arr || {});
              len = keys.length;
              outputArr = new Array(len);

              if (len === 0) {
                cb(null, '');
              } else {
                for (var _i = 0; _i < keys.length; _i++) {
                  var k = keys[_i];
                  func(k, arr[k], _i, len, done);
                }
              }
            }
          }

          function fromIterator(arr) {
            if (typeof arr !== 'object' || arr === null || lib.isArray(arr)) {
              return arr;
            } else if (supportsIterators && Symbol.iterator in arr) {
              return arrayFrom(arr);
            } else {
              return arr;
            }
          }

          module.exports = {
            Frame: Frame,
            makeMacro: makeMacro,
            makeKeywordArgs: makeKeywordArgs,
            numArgs: numArgs,
            suppressValue: suppressValue,
            ensureDefined: ensureDefined,
            memberLookup: memberLookup,
            contextOrFrameLookup: contextOrFrameLookup,
            callWrap: callWrap,
            handleError: handleError,
            isArray: lib.isArray,
            keys: lib.keys,
            SafeString: SafeString,
            copySafeness: copySafeness,
            markSafe: markSafe,
            asyncEach: asyncEach,
            asyncAll: asyncAll,
            inOperator: lib.inOperator,
            fromIterator: fromIterator
          };
          /***/
        }, function (module, exports, __webpack_require__) {

          function _inheritsLoose(subClass, superClass) {
            subClass.prototype = Object.create(superClass.prototype);
            subClass.prototype.constructor = subClass;
            subClass.__proto__ = superClass;
          }

          var Loader = __webpack_require__(4);

          var PrecompiledLoader = /*#__PURE__*/function (_Loader) {
            _inheritsLoose(PrecompiledLoader, _Loader);

            function PrecompiledLoader(compiledTemplates) {
              var _this;

              _this = _Loader.call(this) || this;
              _this.precompiled = compiledTemplates || {};
              return _this;
            }

            var _proto = PrecompiledLoader.prototype;

            _proto.getSource = function getSource(name) {
              if (this.precompiled[name]) {
                return {
                  src: {
                    type: 'code',
                    obj: this.precompiled[name]
                  },
                  path: name
                };
              }

              return null;
            };

            return PrecompiledLoader;
          }(Loader);

          module.exports = {
            PrecompiledLoader: PrecompiledLoader
          };
          /***/
        }, function (module, exports, __webpack_require__) {

          function _inheritsLoose(subClass, superClass) {
            subClass.prototype = Object.create(superClass.prototype);
            subClass.prototype.constructor = subClass;
            subClass.__proto__ = superClass;
          }

          var path = __webpack_require__(0);

          var _require = __webpack_require__(5),
              EmitterObj = _require.EmitterObj;

          module.exports = /*#__PURE__*/function (_EmitterObj) {
            _inheritsLoose(Loader, _EmitterObj);

            function Loader() {
              return _EmitterObj.apply(this, arguments) || this;
            }

            var _proto = Loader.prototype;

            _proto.resolve = function resolve(from, to) {
              return path.resolve(path.dirname(from), to);
            };

            _proto.isRelative = function isRelative(filename) {
              return filename.indexOf('./') === 0 || filename.indexOf('../') === 0;
            };

            return Loader;
          }(EmitterObj);
          /***/

        }, function (module, exports, __webpack_require__) {

          function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
              var descriptor = props[i];
              descriptor.enumerable = descriptor.enumerable || false;
              descriptor.configurable = true;
              if ("value" in descriptor) descriptor.writable = true;
              Object.defineProperty(target, descriptor.key, descriptor);
            }
          }

          function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            return Constructor;
          }

          function _inheritsLoose(subClass, superClass) {
            subClass.prototype = Object.create(superClass.prototype);
            subClass.prototype.constructor = subClass;
            subClass.__proto__ = superClass;
          }

          var EventEmitter = __webpack_require__(13);

          var lib = __webpack_require__(1);

          function parentWrap(parent, prop) {
            if (typeof parent !== 'function' || typeof prop !== 'function') {
              return prop;
            }

            return function wrap() {
              // Save the current parent method
              var tmp = this.parent; // Set parent to the previous method, call, and restore

              this.parent = parent;
              var res = prop.apply(this, arguments);
              this.parent = tmp;
              return res;
            };
          }

          function extendClass(cls, name, props) {
            props = props || {};
            lib.keys(props).forEach(function (k) {
              props[k] = parentWrap(cls.prototype[k], props[k]);
            });

            var subclass = /*#__PURE__*/function (_cls) {
              _inheritsLoose(subclass, _cls);

              function subclass() {
                return _cls.apply(this, arguments) || this;
              }

              _createClass(subclass, [{
                key: "typename",
                get: function get() {
                  return name;
                }
              }]);

              return subclass;
            }(cls);

            lib._assign(subclass.prototype, props);

            return subclass;
          }

          var Obj = /*#__PURE__*/function () {
            function Obj() {
              // Unfortunately necessary for backwards compatibility
              this.init.apply(this, arguments);
            }

            var _proto = Obj.prototype;

            _proto.init = function init() {};

            Obj.extend = function extend(name, props) {
              if (typeof name === 'object') {
                props = name;
                name = 'anonymous';
              }

              return extendClass(this, name, props);
            };

            _createClass(Obj, [{
              key: "typename",
              get: function get() {
                return this.constructor.name;
              }
            }]);

            return Obj;
          }();

          var EmitterObj = /*#__PURE__*/function (_EventEmitter) {
            _inheritsLoose(EmitterObj, _EventEmitter);

            function EmitterObj() {
              var _this2;

              var _this;

              _this = _EventEmitter.call(this) || this; // Unfortunately necessary for backwards compatibility

              (_this2 = _this).init.apply(_this2, arguments);

              return _this;
            }

            var _proto2 = EmitterObj.prototype;

            _proto2.init = function init() {};

            EmitterObj.extend = function extend(name, props) {
              if (typeof name === 'object') {
                props = name;
                name = 'anonymous';
              }

              return extendClass(this, name, props);
            };

            _createClass(EmitterObj, [{
              key: "typename",
              get: function get() {
                return this.constructor.name;
              }
            }]);

            return EmitterObj;
          }(EventEmitter);

          module.exports = {
            Obj: Obj,
            EmitterObj: EmitterObj
          };
          /***/
        }, function (module, exports, __webpack_require__) {

          var lib = __webpack_require__(1);

          var _require = __webpack_require__(7),
              Environment = _require.Environment,
              Template = _require.Template;

          var Loader = __webpack_require__(4);

          var loaders = __webpack_require__(3);

          var precompile = __webpack_require__(0);

          var compiler = __webpack_require__(0);

          var parser = __webpack_require__(0);

          var lexer = __webpack_require__(0);

          var runtime = __webpack_require__(2);

          var nodes = __webpack_require__(0);

          var installJinjaCompat = __webpack_require__(17); // A single instance of an environment, since this is so commonly used


          var e;

          function configure(templatesPath, opts) {
            opts = opts || {};

            if (lib.isObject(templatesPath)) {
              opts = templatesPath;
              templatesPath = null;
            }

            var TemplateLoader;

            if (loaders.FileSystemLoader) {
              TemplateLoader = new loaders.FileSystemLoader(templatesPath, {
                watch: opts.watch,
                noCache: opts.noCache
              });
            } else if (loaders.WebLoader) {
              TemplateLoader = new loaders.WebLoader(templatesPath, {
                useCache: opts.web && opts.web.useCache,
                async: opts.web && opts.web.async
              });
            }

            e = new Environment(TemplateLoader, opts);

            if (opts && opts.express) {
              e.express(opts.express);
            }

            return e;
          }

          module.exports = {
            Environment: Environment,
            Template: Template,
            Loader: Loader,
            FileSystemLoader: loaders.FileSystemLoader,
            NodeResolveLoader: loaders.NodeResolveLoader,
            PrecompiledLoader: loaders.PrecompiledLoader,
            WebLoader: loaders.WebLoader,
            compiler: compiler,
            parser: parser,
            lexer: lexer,
            runtime: runtime,
            lib: lib,
            nodes: nodes,
            installJinjaCompat: installJinjaCompat,
            configure: configure,
            reset: function reset() {
              e = undefined;
            },
            compile: function compile(src, env, path, eagerCompile) {
              if (!e) {
                configure();
              }

              return new Template(src, env, path, eagerCompile);
            },
            render: function render(name, ctx, cb) {
              if (!e) {
                configure();
              }

              return e.render(name, ctx, cb);
            },
            renderString: function renderString(src, ctx, cb) {
              if (!e) {
                configure();
              }

              return e.renderString(src, ctx, cb);
            },
            precompile: precompile ? precompile.precompile : undefined,
            precompileString: precompile ? precompile.precompileString : undefined
          };
          /***/
        }, function (module, exports, __webpack_require__) {

          function _inheritsLoose(subClass, superClass) {
            subClass.prototype = Object.create(superClass.prototype);
            subClass.prototype.constructor = subClass;
            subClass.__proto__ = superClass;
          }

          var asap = __webpack_require__(8);

          var _waterfall = __webpack_require__(11);

          var lib = __webpack_require__(1);

          var compiler = __webpack_require__(0);

          var filters = __webpack_require__(12);

          var _require = __webpack_require__(3),
              FileSystemLoader = _require.FileSystemLoader,
              WebLoader = _require.WebLoader,
              PrecompiledLoader = _require.PrecompiledLoader;

          var tests = __webpack_require__(14);

          var globals = __webpack_require__(15);

          var _require2 = __webpack_require__(5),
              Obj = _require2.Obj,
              EmitterObj = _require2.EmitterObj;

          var globalRuntime = __webpack_require__(2);

          var handleError = globalRuntime.handleError,
              Frame = globalRuntime.Frame;

          var expressApp = __webpack_require__(16); // If the user is using the async API, *always* call it
          // asynchronously even if the template was synchronous.


          function callbackAsap(cb, err, res) {
            asap(function () {
              cb(err, res);
            });
          }
          /**
           * A no-op template, for use with {% include ignore missing %}
           */


          var noopTmplSrc = {
            type: 'code',
            obj: {
              root: function root(env, context, frame, runtime, cb) {
                try {
                  cb(null, '');
                } catch (e) {
                  cb(handleError(e, null, null));
                }
              }
            }
          };

          var Environment = /*#__PURE__*/function (_EmitterObj) {
            _inheritsLoose(Environment, _EmitterObj);

            function Environment() {
              return _EmitterObj.apply(this, arguments) || this;
            }

            var _proto = Environment.prototype;

            _proto.init = function init(loaders, opts) {
              var _this = this; // The dev flag determines the trace that'll be shown on errors.
              // If set to true, returns the full trace from the error point,
              // otherwise will return trace starting from Template.render
              // (the full trace from within nunjucks may confuse developers using
              //  the library)
              // defaults to false


              opts = this.opts = opts || {};
              this.opts.dev = !!opts.dev; // The autoescape flag sets global autoescaping. If true,
              // every string variable will be escaped by default.
              // If false, strings can be manually escaped using the `escape` filter.
              // defaults to true

              this.opts.autoescape = opts.autoescape != null ? opts.autoescape : true; // If true, this will make the system throw errors if trying
              // to output a null or undefined value

              this.opts.throwOnUndefined = !!opts.throwOnUndefined;
              this.opts.trimBlocks = !!opts.trimBlocks;
              this.opts.lstripBlocks = !!opts.lstripBlocks;
              this.loaders = [];

              if (!loaders) {
                // The filesystem loader is only available server-side
                if (FileSystemLoader) {
                  this.loaders = [new FileSystemLoader('views')];
                } else if (WebLoader) {
                  this.loaders = [new WebLoader('/views')];
                }
              } else {
                this.loaders = lib.isArray(loaders) ? loaders : [loaders];
              } // It's easy to use precompiled templates: just include them
              // before you configure nunjucks and this will automatically
              // pick it up and use it


              if (typeof window !== 'undefined' && window.nunjucksPrecompiled) {
                this.loaders.unshift(new PrecompiledLoader(window.nunjucksPrecompiled));
              }

              this._initLoaders();

              this.globals = globals();
              this.filters = {};
              this.tests = {};
              this.asyncFilters = [];
              this.extensions = {};
              this.extensionsList = [];

              lib._entries(filters).forEach(function (_ref) {
                var name = _ref[0],
                    filter = _ref[1];
                return _this.addFilter(name, filter);
              });

              lib._entries(tests).forEach(function (_ref2) {
                var name = _ref2[0],
                    test = _ref2[1];
                return _this.addTest(name, test);
              });
            };

            _proto._initLoaders = function _initLoaders() {
              var _this2 = this;

              this.loaders.forEach(function (loader) {
                // Caching and cache busting
                loader.cache = {};

                if (typeof loader.on === 'function') {
                  loader.on('update', function (name, fullname) {
                    loader.cache[name] = null;

                    _this2.emit('update', name, fullname, loader);
                  });
                  loader.on('load', function (name, source) {
                    _this2.emit('load', name, source, loader);
                  });
                }
              });
            };

            _proto.invalidateCache = function invalidateCache() {
              this.loaders.forEach(function (loader) {
                loader.cache = {};
              });
            };

            _proto.addExtension = function addExtension(name, extension) {
              extension.__name = name;
              this.extensions[name] = extension;
              this.extensionsList.push(extension);
              return this;
            };

            _proto.removeExtension = function removeExtension(name) {
              var extension = this.getExtension(name);

              if (!extension) {
                return;
              }

              this.extensionsList = lib.without(this.extensionsList, extension);
              delete this.extensions[name];
            };

            _proto.getExtension = function getExtension(name) {
              return this.extensions[name];
            };

            _proto.hasExtension = function hasExtension(name) {
              return !!this.extensions[name];
            };

            _proto.addGlobal = function addGlobal(name, value) {
              this.globals[name] = value;
              return this;
            };

            _proto.getGlobal = function getGlobal(name) {
              if (typeof this.globals[name] === 'undefined') {
                throw new Error('global not found: ' + name);
              }

              return this.globals[name];
            };

            _proto.addFilter = function addFilter(name, func, async) {
              var wrapped = func;

              if (async) {
                this.asyncFilters.push(name);
              }

              this.filters[name] = wrapped;
              return this;
            };

            _proto.getFilter = function getFilter(name) {
              if (!this.filters[name]) {
                throw new Error('filter not found: ' + name);
              }

              return this.filters[name];
            };

            _proto.addTest = function addTest(name, func) {
              this.tests[name] = func;
              return this;
            };

            _proto.getTest = function getTest(name) {
              if (!this.tests[name]) {
                throw new Error('test not found: ' + name);
              }

              return this.tests[name];
            };

            _proto.resolveTemplate = function resolveTemplate(loader, parentName, filename) {
              var isRelative = loader.isRelative && parentName ? loader.isRelative(filename) : false;
              return isRelative && loader.resolve ? loader.resolve(parentName, filename) : filename;
            };

            _proto.getTemplate = function getTemplate(name, eagerCompile, parentName, ignoreMissing, cb) {
              var _this3 = this;

              var that = this;
              var tmpl = null;

              if (name && name.raw) {
                // this fixes autoescape for templates referenced in symbols
                name = name.raw;
              }

              if (lib.isFunction(parentName)) {
                cb = parentName;
                parentName = null;
                eagerCompile = eagerCompile || false;
              }

              if (lib.isFunction(eagerCompile)) {
                cb = eagerCompile;
                eagerCompile = false;
              }

              if (name instanceof Template) {
                tmpl = name;
              } else if (typeof name !== 'string') {
                throw new Error('template names must be a string: ' + name);
              } else {
                for (var i = 0; i < this.loaders.length; i++) {
                  var loader = this.loaders[i];
                  tmpl = loader.cache[this.resolveTemplate(loader, parentName, name)];

                  if (tmpl) {
                    break;
                  }
                }
              }

              if (tmpl) {
                if (eagerCompile) {
                  tmpl.compile();
                }

                if (cb) {
                  cb(null, tmpl);
                  return undefined;
                } else {
                  return tmpl;
                }
              }

              var syncResult;

              var createTemplate = function createTemplate(err, info) {
                if (!info && !err && !ignoreMissing) {
                  err = new Error('template not found: ' + name);
                }

                if (err) {
                  if (cb) {
                    cb(err);
                    return;
                  } else {
                    throw err;
                  }
                }

                var newTmpl;

                if (!info) {
                  newTmpl = new Template(noopTmplSrc, _this3, '', eagerCompile);
                } else {
                  newTmpl = new Template(info.src, _this3, info.path, eagerCompile);

                  if (!info.noCache) {
                    info.loader.cache[name] = newTmpl;
                  }
                }

                if (cb) {
                  cb(null, newTmpl);
                } else {
                  syncResult = newTmpl;
                }
              };

              lib.asyncIter(this.loaders, function (loader, i, next, done) {
                function handle(err, src) {
                  if (err) {
                    done(err);
                  } else if (src) {
                    src.loader = loader;
                    done(null, src);
                  } else {
                    next();
                  }
                } // Resolve name relative to parentName


                name = that.resolveTemplate(loader, parentName, name);

                if (loader.async) {
                  loader.getSource(name, handle);
                } else {
                  handle(null, loader.getSource(name));
                }
              }, createTemplate);
              return syncResult;
            };

            _proto.express = function express(app) {
              return expressApp(this, app);
            };

            _proto.render = function render(name, ctx, cb) {
              if (lib.isFunction(ctx)) {
                cb = ctx;
                ctx = null;
              } // We support a synchronous API to make it easier to migrate
              // existing code to async. This works because if you don't do
              // anything async work, the whole thing is actually run
              // synchronously.


              var syncResult = null;
              this.getTemplate(name, function (err, tmpl) {
                if (err && cb) {
                  callbackAsap(cb, err);
                } else if (err) {
                  throw err;
                } else {
                  syncResult = tmpl.render(ctx, cb);
                }
              });
              return syncResult;
            };

            _proto.renderString = function renderString(src, ctx, opts, cb) {
              if (lib.isFunction(opts)) {
                cb = opts;
                opts = {};
              }

              opts = opts || {};
              var tmpl = new Template(src, this, opts.path);
              return tmpl.render(ctx, cb);
            };

            _proto.waterfall = function waterfall(tasks, callback, forceAsync) {
              return _waterfall(tasks, callback, forceAsync);
            };

            return Environment;
          }(EmitterObj);

          var Context = /*#__PURE__*/function (_Obj) {
            _inheritsLoose(Context, _Obj);

            function Context() {
              return _Obj.apply(this, arguments) || this;
            }

            var _proto2 = Context.prototype;

            _proto2.init = function init(ctx, blocks, env) {
              var _this4 = this; // Has to be tied to an environment so we can tap into its globals.


              this.env = env || new Environment(); // Make a duplicate of ctx

              this.ctx = lib.extend({}, ctx);
              this.blocks = {};
              this.exported = [];
              lib.keys(blocks).forEach(function (name) {
                _this4.addBlock(name, blocks[name]);
              });
            };

            _proto2.lookup = function lookup(name) {
              // This is one of the most called functions, so optimize for
              // the typical case where the name isn't in the globals
              if (name in this.env.globals && !(name in this.ctx)) {
                return this.env.globals[name];
              } else {
                return this.ctx[name];
              }
            };

            _proto2.setVariable = function setVariable(name, val) {
              this.ctx[name] = val;
            };

            _proto2.getVariables = function getVariables() {
              return this.ctx;
            };

            _proto2.addBlock = function addBlock(name, block) {
              this.blocks[name] = this.blocks[name] || [];
              this.blocks[name].push(block);
              return this;
            };

            _proto2.getBlock = function getBlock(name) {
              if (!this.blocks[name]) {
                throw new Error('unknown block "' + name + '"');
              }

              return this.blocks[name][0];
            };

            _proto2.getSuper = function getSuper(env, name, block, frame, runtime, cb) {
              var idx = lib.indexOf(this.blocks[name] || [], block);
              var blk = this.blocks[name][idx + 1];
              var context = this;

              if (idx === -1 || !blk) {
                throw new Error('no super block available for "' + name + '"');
              }

              blk(env, context, frame, runtime, cb);
            };

            _proto2.addExport = function addExport(name) {
              this.exported.push(name);
            };

            _proto2.getExported = function getExported() {
              var _this5 = this;

              var exported = {};
              this.exported.forEach(function (name) {
                exported[name] = _this5.ctx[name];
              });
              return exported;
            };

            return Context;
          }(Obj);

          var Template = /*#__PURE__*/function (_Obj2) {
            _inheritsLoose(Template, _Obj2);

            function Template() {
              return _Obj2.apply(this, arguments) || this;
            }

            var _proto3 = Template.prototype;

            _proto3.init = function init(src, env, path, eagerCompile) {
              this.env = env || new Environment();

              if (lib.isObject(src)) {
                switch (src.type) {
                  case 'code':
                    this.tmplProps = src.obj;
                    break;

                  case 'string':
                    this.tmplStr = src.obj;
                    break;

                  default:
                    throw new Error("Unexpected template object type " + src.type + "; expected 'code', or 'string'");
                }
              } else if (lib.isString(src)) {
                this.tmplStr = src;
              } else {
                throw new Error('src must be a string or an object describing the source');
              }

              this.path = path;

              if (eagerCompile) {
                try {
                  this._compile();
                } catch (err) {
                  throw lib._prettifyError(this.path, this.env.opts.dev, err);
                }
              } else {
                this.compiled = false;
              }
            };

            _proto3.render = function render(ctx, parentFrame, cb) {
              var _this6 = this;

              if (typeof ctx === 'function') {
                cb = ctx;
                ctx = {};
              } else if (typeof parentFrame === 'function') {
                cb = parentFrame;
                parentFrame = null;
              } // If there is a parent frame, we are being called from internal
              // code of another template, and the internal system
              // depends on the sync/async nature of the parent template
              // to be inherited, so force an async callback


              var forceAsync = !parentFrame; // Catch compile errors for async rendering

              try {
                this.compile();
              } catch (e) {
                var err = lib._prettifyError(this.path, this.env.opts.dev, e);

                if (cb) {
                  return callbackAsap(cb, err);
                } else {
                  throw err;
                }
              }

              var context = new Context(ctx || {}, this.blocks, this.env);
              var frame = parentFrame ? parentFrame.push(true) : new Frame();
              frame.topLevel = true;
              var syncResult = null;
              var didError = false;
              this.rootRenderFunc(this.env, context, frame, globalRuntime, function (err, res) {
                if (didError) {
                  // prevent multiple calls to cb
                  if (cb) {
                    return;
                  } else {
                    throw err;
                  }
                }

                if (err) {
                  err = lib._prettifyError(_this6.path, _this6.env.opts.dev, err);
                  didError = true;
                }

                if (cb) {
                  if (forceAsync) {
                    callbackAsap(cb, err, res);
                  } else {
                    cb(err, res);
                  }
                } else {
                  if (err) {
                    throw err;
                  }

                  syncResult = res;
                }
              });
              return syncResult;
            };

            _proto3.getExported = function getExported(ctx, parentFrame, cb) {
              // eslint-disable-line consistent-return
              if (typeof ctx === 'function') {
                cb = ctx;
                ctx = {};
              }

              if (typeof parentFrame === 'function') {
                cb = parentFrame;
                parentFrame = null;
              } // Catch compile errors for async rendering


              try {
                this.compile();
              } catch (e) {
                if (cb) {
                  return cb(e);
                } else {
                  throw e;
                }
              }

              var frame = parentFrame ? parentFrame.push() : new Frame();
              frame.topLevel = true; // Run the rootRenderFunc to populate the context with exported vars

              var context = new Context(ctx || {}, this.blocks, this.env);
              this.rootRenderFunc(this.env, context, frame, globalRuntime, function (err) {
                if (err) {
                  cb(err, null);
                } else {
                  cb(null, context.getExported());
                }
              });
            };

            _proto3.compile = function compile() {
              if (!this.compiled) {
                this._compile();
              }
            };

            _proto3._compile = function _compile() {
              var props;

              if (this.tmplProps) {
                props = this.tmplProps;
              } else {
                var source = compiler.compile(this.tmplStr, this.env.asyncFilters, this.env.extensionsList, this.path, this.env.opts);
                var func = new Function(source); // eslint-disable-line no-new-func

                props = func();
              }

              this.blocks = this._getBlocks(props);
              this.rootRenderFunc = props.root;
              this.compiled = true;
            };

            _proto3._getBlocks = function _getBlocks(props) {
              var blocks = {};
              lib.keys(props).forEach(function (k) {
                if (k.slice(0, 2) === 'b_') {
                  blocks[k.slice(2)] = props[k];
                }
              });
              return blocks;
            };

            return Template;
          }(Obj);

          module.exports = {
            Environment: Environment,
            Template: Template
          };
          /***/
        }, function (module, exports, __webpack_require__) {

          var rawAsap = __webpack_require__(9); // RawTasks are recycled to reduce GC churn.


          var freeTasks = []; // We queue errors to ensure they are thrown in right order (FIFO).
          // Array-as-queue is good enough here, since we are just dealing with exceptions.

          var pendingErrors = [];
          var requestErrorThrow = rawAsap.makeRequestCallFromTimer(throwFirstError);

          function throwFirstError() {
            if (pendingErrors.length) {
              throw pendingErrors.shift();
            }
          }
          /**
           * Calls a task as soon as possible after returning, in its own event, with priority
           * over other events like animation, reflow, and repaint. An error thrown from an
           * event will not interrupt, nor even substantially slow down the processing of
           * other events, but will be rather postponed to a lower priority event.
           * @param {{call}} task A callable object, typically a function that takes no
           * arguments.
           */


          module.exports = asap;

          function asap(task) {
            var rawTask;

            if (freeTasks.length) {
              rawTask = freeTasks.pop();
            } else {
              rawTask = new RawTask();
            }

            rawTask.task = task;
            rawAsap(rawTask);
          } // We wrap tasks with recyclable task objects.  A task object implements
          // `call`, just like a function.


          function RawTask() {
            this.task = null;
          } // The sole purpose of wrapping the task is to catch the exception and recycle
          // the task object after its single use.


          RawTask.prototype.call = function () {
            try {
              this.task.call();
            } catch (error) {
              if (asap.onerror) {
                // This hook exists purely for testing purposes.
                // Its name will be periodically randomized to break any code that
                // depends on its existence.
                asap.onerror(error);
              } else {
                // In a web browser, exceptions are not fatal. However, to avoid
                // slowing down the queue of pending tasks, we rethrow the error in a
                // lower priority turn.
                pendingErrors.push(error);
                requestErrorThrow();
              }
            } finally {
              this.task = null;
              freeTasks[freeTasks.length] = this;
            }
          };
          /***/

        }, function (module, exports, __webpack_require__) {
          /* WEBPACK VAR INJECTION */

          (function (global) {
            // Use the fastest means possible to execute a task in its own turn, with
            // priority over other events including IO, animation, reflow, and redraw
            // events in browsers.
            //
            // An exception thrown by a task will permanently interrupt the processing of
            // subsequent tasks. The higher level `asap` function ensures that if an
            // exception is thrown by a task, that the task queue will continue flushing as
            // soon as possible, but if you use `rawAsap` directly, you are responsible to
            // either ensure that no exceptions are thrown from your task, or to manually
            // call `rawAsap.requestFlush` if an exception is thrown.
            module.exports = rawAsap;

            function rawAsap(task) {
              if (!queue.length) {
                requestFlush();
              } // Equivalent to push, but avoids a function call.


              queue[queue.length] = task;
            }

            var queue = []; // Once a flush has been requested, no further calls to `requestFlush` are
            // off a `flush` event as quickly as possible. `flush` will attempt to exhaust
            // the event queue before yielding to the browser's own event loop.

            var requestFlush; // The position of the next task to execute in the task queue. This is
            // preserved between calls to `flush` so that it can be resumed if
            // a task throws an exception.

            var index = 0; // If a task schedules additional tasks recursively, the task queue can grow
            // unbounded. To prevent memory exhaustion, the task queue will periodically
            // truncate already-completed tasks.

            var capacity = 1024; // The flush function processes all tasks that have been scheduled with
            // `rawAsap` unless and until one of those tasks throws an exception.
            // If a task throws an exception, `flush` ensures that its state will remain
            // consistent and will resume where it left off when called again.
            // However, `flush` does not make any arrangements to be called again if an
            // exception is thrown.

            function flush() {
              while (index < queue.length) {
                var currentIndex = index; // Advance the index before calling the task. This ensures that we will
                // begin flushing on the next task the task throws an error.

                index = index + 1;
                queue[currentIndex].call(); // Prevent leaking memory for long chains of recursive calls to `asap`.
                // If we call `asap` within tasks scheduled by `asap`, the queue will
                // grow, but to avoid an O(n) walk for every task we execute, we don't
                // shift tasks off the queue after they have been executed.
                // Instead, we periodically shift 1024 tasks off the queue.

                if (index > capacity) {
                  // Manually shift all values starting at the index back to the
                  // beginning of the queue.
                  for (var scan = 0, newLength = queue.length - index; scan < newLength; scan++) {
                    queue[scan] = queue[scan + index];
                  }

                  queue.length -= index;
                  index = 0;
                }
              }

              queue.length = 0;
              index = 0;
            } // `requestFlush` is implemented using a strategy based on data collected from
            // every available SauceLabs Selenium web driver worker at time of writing.
            // https://docs.google.com/spreadsheets/d/1mG-5UYGup5qxGdEMWkhP6BWCz053NUb2E1QoUTU16uA/edit#gid=783724593
            // Safari 6 and 6.1 for desktop, iPad, and iPhone are the only browsers that
            // have WebKitMutationObserver but not un-prefixed MutationObserver.
            // Must use `global` or `self` instead of `window` to work in both frames and web
            // workers. `global` is a provision of Browserify, Mr, Mrs, or Mop.

            /* globals self */


            var scope = typeof global !== "undefined" ? global : self;
            var BrowserMutationObserver = scope.MutationObserver || scope.WebKitMutationObserver; // MutationObservers are desirable because they have high priority and work
            // reliably everywhere they are implemented.
            // They are implemented in all modern browsers.
            //
            // - Android 4-4.3
            // - Chrome 26-34
            // - Firefox 14-29
            // - Internet Explorer 11
            // - iPad Safari 6-7.1
            // - iPhone Safari 7-7.1
            // - Safari 6-7

            if (typeof BrowserMutationObserver === "function") {
              requestFlush = makeRequestCallFromMutationObserver(flush); // MessageChannels are desirable because they give direct access to the HTML
              // task queue, are implemented in Internet Explorer 10, Safari 5.0-1, and Opera
              // 11-12, and in web workers in many engines.
              // Although message channels yield to any queued rendering and IO tasks, they
              // would be better than imposing the 4ms delay of timers.
              // However, they do not work reliably in Internet Explorer or Safari.
              // Internet Explorer 10 is the only browser that has setImmediate but does
              // not have MutationObservers.
              // Although setImmediate yields to the browser's renderer, it would be
              // preferrable to falling back to setTimeout since it does not have
              // the minimum 4ms penalty.
              // Unfortunately there appears to be a bug in Internet Explorer 10 Mobile (and
              // Desktop to a lesser extent) that renders both setImmediate and
              // MessageChannel useless for the purposes of ASAP.
              // https://github.com/kriskowal/q/issues/396
              // Timers are implemented universally.
              // We fall back to timers in workers in most engines, and in foreground
              // contexts in the following browsers.
              // However, note that even this simple case requires nuances to operate in a
              // broad spectrum of browsers.
              //
              // - Firefox 3-13
              // - Internet Explorer 6-9
              // - iPad Safari 4.3
              // - Lynx 2.8.7
            } else {
              requestFlush = makeRequestCallFromTimer(flush);
            } // `requestFlush` requests that the high priority event queue be flushed as
            // soon as possible.
            // This is useful to prevent an error thrown in a task from stalling the event
            // queue if the exception handled by Node.js’s
            // `process.on("uncaughtException")` or by a domain.


            rawAsap.requestFlush = requestFlush; // To request a high priority event, we induce a mutation observer by toggling
            // the text of a text node between "1" and "-1".

            function makeRequestCallFromMutationObserver(callback) {
              var toggle = 1;
              var observer = new BrowserMutationObserver(callback);
              var node = document.createTextNode("");
              observer.observe(node, {
                characterData: true
              });
              return function requestCall() {
                toggle = -toggle;
                node.data = toggle;
              };
            } // The message channel technique was discovered by Malte Ubl and was the
            // original foundation for this library.
            // http://www.nonblocking.io/2011/06/windownexttick.html
            // Safari 6.0.5 (at least) intermittently fails to create message ports on a
            // page's first load. Thankfully, this version of Safari supports
            // MutationObservers, so we don't need to fall back in that case.
            // function makeRequestCallFromMessageChannel(callback) {
            //     var channel = new MessageChannel();
            //     channel.port1.onmessage = callback;
            //     return function requestCall() {
            //         channel.port2.postMessage(0);
            //     };
            // }
            // For reasons explained above, we are also unable to use `setImmediate`
            // under any circumstances.
            // Even if we were, there is another bug in Internet Explorer 10.
            // It is not sufficient to assign `setImmediate` to `requestFlush` because
            // `setImmediate` must be called *by name* and therefore must be wrapped in a
            // closure.
            // Never forget.
            // function makeRequestCallFromSetImmediate(callback) {
            //     return function requestCall() {
            //         setImmediate(callback);
            //     };
            // }
            // Safari 6.0 has a problem where timers will get lost while the user is
            // scrolling. This problem does not impact ASAP because Safari 6.0 supports
            // mutation observers, so that implementation is used instead.
            // However, if we ever elect to use timers in Safari, the prevalent work-around
            // is to add a scroll event listener that calls for a flush.
            // `setTimeout` does not call the passed callback if the delay is less than
            // approximately 7 in web workers in Firefox 8 through 18, and sometimes not
            // even then.


            function makeRequestCallFromTimer(callback) {
              return function requestCall() {
                // We dispatch a timeout with a specified delay of 0 for engines that
                // can reliably accommodate that request. This will usually be snapped
                // to a 4 milisecond delay, but once we're flushing, there's no delay
                // between events.
                var timeoutHandle = setTimeout(handleTimer, 0); // However, since this timer gets frequently dropped in Firefox
                // workers, we enlist an interval handle that will try to fire
                // an event 20 times per second until it succeeds.

                var intervalHandle = setInterval(handleTimer, 50);

                function handleTimer() {
                  // Whichever timer succeeds will cancel both timers and
                  // execute the callback.
                  clearTimeout(timeoutHandle);
                  clearInterval(intervalHandle);
                  callback();
                }
              };
            } // This is for `asap.js` only.
            // Its name will be periodically randomized to break any code that depends on
            // its existence.


            rawAsap.makeRequestCallFromTimer = makeRequestCallFromTimer; // ASAP was originally a nextTick shim included in Q. This was factored out
            // into this ASAP package. It was later adapted to RSVP which made further
            // amendments. These decisions, particularly to marginalize MessageChannel and
            // to capture the MutationObserver implementation in a closure, were integrated
            // back into ASAP proper.
            // https://github.com/tildeio/rsvp.js/blob/cddf7232546a9cf858524b75cde6f9edf72620a7/lib/rsvp/asap.js

            /* WEBPACK VAR INJECTION */
          }).call(exports, __webpack_require__(10));
          /***/
        }, function (module, exports) {
          var g; // This works in non-strict mode

          g = function () {
            return this;
          }();

          try {
            // This works if eval is allowed (see CSP)
            g = g || Function("return this")() || (1, eval)("this");
          } catch (e) {
            // This works if the window reference is available
            if (typeof window === "object") g = window;
          } // g can still be undefined, but nothing to do about it...
          // We return undefined, instead of nothing here, so it's
          // easier to handle this case. if(!global) { ...}


          module.exports = g;
          /***/
        }, function (module, exports, __webpack_require__) {
          var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__; // MIT license (by Elan Shanker).


          (function (globals) {

            var executeSync = function () {
              var args = Array.prototype.slice.call(arguments);

              if (typeof args[0] === 'function') {
                args[0].apply(null, args.splice(1));
              }
            };

            var executeAsync = function (fn) {
              if (typeof setImmediate === 'function') {
                setImmediate(fn);
              } else if (typeof process !== 'undefined' && process.nextTick) {
                process.nextTick(fn);
              } else {
                setTimeout(fn, 0);
              }
            };

            var makeIterator = function (tasks) {
              var makeCallback = function (index) {
                var fn = function () {
                  if (tasks.length) {
                    tasks[index].apply(null, arguments);
                  }

                  return fn.next();
                };

                fn.next = function () {
                  return index < tasks.length - 1 ? makeCallback(index + 1) : null;
                };

                return fn;
              };

              return makeCallback(0);
            };

            var _isArray = Array.isArray || function (maybeArray) {
              return Object.prototype.toString.call(maybeArray) === '[object Array]';
            };

            var waterfall = function (tasks, callback, forceAsync) {
              var nextTick = forceAsync ? executeAsync : executeSync;

              callback = callback || function () {};

              if (!_isArray(tasks)) {
                var err = new Error('First argument to waterfall must be an array of functions');
                return callback(err);
              }

              if (!tasks.length) {
                return callback();
              }

              var wrapIterator = function (iterator) {
                return function (err) {
                  if (err) {
                    callback.apply(null, arguments);

                    callback = function () {};
                  } else {
                    var args = Array.prototype.slice.call(arguments, 1);
                    var next = iterator.next();

                    if (next) {
                      args.push(wrapIterator(next));
                    } else {
                      args.push(callback);
                    }

                    nextTick(function () {
                      iterator.apply(null, args);
                    });
                  }
                };
              };

              wrapIterator(makeIterator(tasks))();
            };

            {
              !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = function () {
                return waterfall;
              }.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)); // RequireJS
            }
          })();
          /***/

        }, function (module, exports, __webpack_require__) {

          var lib = __webpack_require__(1);

          var r = __webpack_require__(2);

          var exports = module.exports = {};

          function normalize(value, defaultValue) {
            if (value === null || value === undefined || value === false) {
              return defaultValue;
            }

            return value;
          }

          exports.abs = Math.abs;

          function isNaN(num) {
            return num !== num; // eslint-disable-line no-self-compare
          }

          function batch(arr, linecount, fillWith) {
            var i;
            var res = [];
            var tmp = [];

            for (i = 0; i < arr.length; i++) {
              if (i % linecount === 0 && tmp.length) {
                res.push(tmp);
                tmp = [];
              }

              tmp.push(arr[i]);
            }

            if (tmp.length) {
              if (fillWith) {
                for (i = tmp.length; i < linecount; i++) {
                  tmp.push(fillWith);
                }
              }

              res.push(tmp);
            }

            return res;
          }

          exports.batch = batch;

          function capitalize(str) {
            str = normalize(str, '');
            var ret = str.toLowerCase();
            return r.copySafeness(str, ret.charAt(0).toUpperCase() + ret.slice(1));
          }

          exports.capitalize = capitalize;

          function center(str, width) {
            str = normalize(str, '');
            width = width || 80;

            if (str.length >= width) {
              return str;
            }

            var spaces = width - str.length;
            var pre = lib.repeat(' ', spaces / 2 - spaces % 2);
            var post = lib.repeat(' ', spaces / 2);
            return r.copySafeness(str, pre + str + post);
          }

          exports.center = center;

          function default_(val, def, bool) {
            if (bool) {
              return val || def;
            } else {
              return val !== undefined ? val : def;
            }
          } // TODO: it is confusing to export something called 'default'


          exports['default'] = default_; // eslint-disable-line dot-notation

          function dictsort(val, caseSensitive, by) {
            if (!lib.isObject(val)) {
              throw new lib.TemplateError('dictsort filter: val must be an object');
            }

            var array = []; // deliberately include properties from the object's prototype

            for (var k in val) {
              // eslint-disable-line guard-for-in, no-restricted-syntax
              array.push([k, val[k]]);
            }

            var si;

            if (by === undefined || by === 'key') {
              si = 0;
            } else if (by === 'value') {
              si = 1;
            } else {
              throw new lib.TemplateError('dictsort filter: You can only sort by either key or value');
            }

            array.sort(function (t1, t2) {
              var a = t1[si];
              var b = t2[si];

              if (!caseSensitive) {
                if (lib.isString(a)) {
                  a = a.toUpperCase();
                }

                if (lib.isString(b)) {
                  b = b.toUpperCase();
                }
              }

              return a > b ? 1 : a === b ? 0 : -1; // eslint-disable-line no-nested-ternary
            });
            return array;
          }

          exports.dictsort = dictsort;

          function dump(obj, spaces) {
            return JSON.stringify(obj, null, spaces);
          }

          exports.dump = dump;

          function escape(str) {
            if (str instanceof r.SafeString) {
              return str;
            }

            str = str === null || str === undefined ? '' : str;
            return r.markSafe(lib.escape(str.toString()));
          }

          exports.escape = escape;

          function safe(str) {
            if (str instanceof r.SafeString) {
              return str;
            }

            str = str === null || str === undefined ? '' : str;
            return r.markSafe(str.toString());
          }

          exports.safe = safe;

          function first(arr) {
            return arr[0];
          }

          exports.first = first;

          function forceescape(str) {
            str = str === null || str === undefined ? '' : str;
            return r.markSafe(lib.escape(str.toString()));
          }

          exports.forceescape = forceescape;

          function groupby(arr, attr) {
            return lib.groupBy(arr, attr);
          }

          exports.groupby = groupby;

          function indent(str, width, indentfirst) {
            str = normalize(str, '');

            if (str === '') {
              return '';
            }

            width = width || 4; // let res = '';

            var lines = str.split('\n');
            var sp = lib.repeat(' ', width);
            var res = lines.map(function (l, i) {
              return i === 0 && !indentfirst ? l + "\n" : "" + sp + l + "\n";
            }).join('');
            return r.copySafeness(str, res);
          }

          exports.indent = indent;

          function join(arr, del, attr) {
            del = del || '';

            if (attr) {
              arr = lib.map(arr, function (v) {
                return v[attr];
              });
            }

            return arr.join(del);
          }

          exports.join = join;

          function last(arr) {
            return arr[arr.length - 1];
          }

          exports.last = last;

          function lengthFilter(val) {
            var value = normalize(val, '');

            if (value !== undefined) {
              if (typeof Map === 'function' && value instanceof Map || typeof Set === 'function' && value instanceof Set) {
                // ECMAScript 2015 Maps and Sets
                return value.size;
              }

              if (lib.isObject(value) && !(value instanceof r.SafeString)) {
                // Objects (besides SafeStrings), non-primative Arrays
                return lib.keys(value).length;
              }

              return value.length;
            }

            return 0;
          }

          exports.length = lengthFilter;

          function list(val) {
            if (lib.isString(val)) {
              return val.split('');
            } else if (lib.isObject(val)) {
              return lib._entries(val || {}).map(function (_ref) {
                var key = _ref[0],
                    value = _ref[1];
                return {
                  key: key,
                  value: value
                };
              });
            } else if (lib.isArray(val)) {
              return val;
            } else {
              throw new lib.TemplateError('list filter: type not iterable');
            }
          }

          exports.list = list;

          function lower(str) {
            str = normalize(str, '');
            return str.toLowerCase();
          }

          exports.lower = lower;

          function nl2br(str) {
            if (str === null || str === undefined) {
              return '';
            }

            return r.copySafeness(str, str.replace(/\r\n|\n/g, '<br />\n'));
          }

          exports.nl2br = nl2br;

          function random(arr) {
            return arr[Math.floor(Math.random() * arr.length)];
          }

          exports.random = random;

          function rejectattr(arr, attr) {
            return arr.filter(function (item) {
              return !item[attr];
            });
          }

          exports.rejectattr = rejectattr;

          function selectattr(arr, attr) {
            return arr.filter(function (item) {
              return !!item[attr];
            });
          }

          exports.selectattr = selectattr;

          function replace(str, old, new_, maxCount) {
            var originalStr = str;

            if (old instanceof RegExp) {
              return str.replace(old, new_);
            }

            if (typeof maxCount === 'undefined') {
              maxCount = -1;
            }

            var res = ''; // Output
            // Cast Numbers in the search term to string

            if (typeof old === 'number') {
              old = '' + old;
            } else if (typeof old !== 'string') {
              // If it is something other than number or string,
              // return the original string
              return str;
            } // Cast numbers in the replacement to string


            if (typeof str === 'number') {
              str = '' + str;
            } // If by now, we don't have a string, throw it back


            if (typeof str !== 'string' && !(str instanceof r.SafeString)) {
              return str;
            } // ShortCircuits


            if (old === '') {
              // Mimic the python behaviour: empty string is replaced
              // by replacement e.g. "abc"|replace("", ".") -> .a.b.c.
              res = new_ + str.split('').join(new_) + new_;
              return r.copySafeness(str, res);
            }

            var nextIndex = str.indexOf(old); // if # of replacements to perform is 0, or the string to does
            // not contain the old value, return the string

            if (maxCount === 0 || nextIndex === -1) {
              return str;
            }

            var pos = 0;
            var count = 0; // # of replacements made

            while (nextIndex > -1 && (maxCount === -1 || count < maxCount)) {
              // Grab the next chunk of src string and add it with the
              // replacement, to the result
              res += str.substring(pos, nextIndex) + new_; // Increment our pointer in the src string

              pos = nextIndex + old.length;
              count++; // See if there are any more replacements to be made

              nextIndex = str.indexOf(old, pos);
            } // We've either reached the end, or done the max # of
            // replacements, tack on any remaining string


            if (pos < str.length) {
              res += str.substring(pos);
            }

            return r.copySafeness(originalStr, res);
          }

          exports.replace = replace;

          function reverse(val) {
            var arr;

            if (lib.isString(val)) {
              arr = list(val);
            } else {
              // Copy it
              arr = lib.map(val, function (v) {
                return v;
              });
            }

            arr.reverse();

            if (lib.isString(val)) {
              return r.copySafeness(val, arr.join(''));
            }

            return arr;
          }

          exports.reverse = reverse;

          function round(val, precision, method) {
            precision = precision || 0;
            var factor = Math.pow(10, precision);
            var rounder;

            if (method === 'ceil') {
              rounder = Math.ceil;
            } else if (method === 'floor') {
              rounder = Math.floor;
            } else {
              rounder = Math.round;
            }

            return rounder(val * factor) / factor;
          }

          exports.round = round;

          function slice(arr, slices, fillWith) {
            var sliceLength = Math.floor(arr.length / slices);
            var extra = arr.length % slices;
            var res = [];
            var offset = 0;

            for (var i = 0; i < slices; i++) {
              var start = offset + i * sliceLength;

              if (i < extra) {
                offset++;
              }

              var end = offset + (i + 1) * sliceLength;
              var currSlice = arr.slice(start, end);

              if (fillWith && i >= extra) {
                currSlice.push(fillWith);
              }

              res.push(currSlice);
            }

            return res;
          }

          exports.slice = slice;

          function sum(arr, attr, start) {
            if (start === void 0) {
              start = 0;
            }

            if (attr) {
              arr = lib.map(arr, function (v) {
                return v[attr];
              });
            }

            return start + arr.reduce(function (a, b) {
              return a + b;
            }, 0);
          }

          exports.sum = sum;
          exports.sort = r.makeMacro(['value', 'reverse', 'case_sensitive', 'attribute'], [], function (arr, reversed, caseSens, attr) {
            // Copy it
            var array = lib.map(arr, function (v) {
              return v;
            });
            array.sort(function (a, b) {
              var x = attr ? a[attr] : a;
              var y = attr ? b[attr] : b;

              if (!caseSens && lib.isString(x) && lib.isString(y)) {
                x = x.toLowerCase();
                y = y.toLowerCase();
              }

              if (x < y) {
                return reversed ? 1 : -1;
              } else if (x > y) {
                return reversed ? -1 : 1;
              } else {
                return 0;
              }
            });
            return array;
          });

          function string(obj) {
            return r.copySafeness(obj, obj);
          }

          exports.string = string;

          function striptags(input, preserveLinebreaks) {
            input = normalize(input, '');
            var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>|<!--[\s\S]*?-->/gi;
            var trimmedInput = trim(input.replace(tags, ''));
            var res = '';

            if (preserveLinebreaks) {
              res = trimmedInput.replace(/^ +| +$/gm, '') // remove leading and trailing spaces
              .replace(/ +/g, ' ') // squash adjacent spaces
              .replace(/(\r\n)/g, '\n') // normalize linebreaks (CRLF -> LF)
              .replace(/\n\n\n+/g, '\n\n'); // squash abnormal adjacent linebreaks
            } else {
              res = trimmedInput.replace(/\s+/gi, ' ');
            }

            return r.copySafeness(input, res);
          }

          exports.striptags = striptags;

          function title(str) {
            str = normalize(str, '');
            var words = str.split(' ').map(function (word) {
              return capitalize(word);
            });
            return r.copySafeness(str, words.join(' '));
          }

          exports.title = title;

          function trim(str) {
            return r.copySafeness(str, str.replace(/^\s*|\s*$/g, ''));
          }

          exports.trim = trim;

          function truncate(input, length, killwords, end) {
            var orig = input;
            input = normalize(input, '');
            length = length || 255;

            if (input.length <= length) {
              return input;
            }

            if (killwords) {
              input = input.substring(0, length);
            } else {
              var idx = input.lastIndexOf(' ', length);

              if (idx === -1) {
                idx = length;
              }

              input = input.substring(0, idx);
            }

            input += end !== undefined && end !== null ? end : '...';
            return r.copySafeness(orig, input);
          }

          exports.truncate = truncate;

          function upper(str) {
            str = normalize(str, '');
            return str.toUpperCase();
          }

          exports.upper = upper;

          function urlencode(obj) {
            var enc = encodeURIComponent;

            if (lib.isString(obj)) {
              return enc(obj);
            } else {
              var keyvals = lib.isArray(obj) ? obj : lib._entries(obj);
              return keyvals.map(function (_ref2) {
                var k = _ref2[0],
                    v = _ref2[1];
                return enc(k) + "=" + enc(v);
              }).join('&');
            }
          }

          exports.urlencode = urlencode; // For the jinja regexp, see
          // https://github.com/mitsuhiko/jinja2/blob/f15b814dcba6aa12bc74d1f7d0c881d55f7126be/jinja2/utils.py#L20-L23

          var puncRe = /^(?:\(|<|&lt;)?(.*?)(?:\.|,|\)|\n|&gt;)?$/; // from http://blog.gerv.net/2011/05/html5_email_address_regexp/

          var emailRe = /^[\w.!#$%&'*+\-\/=?\^`{|}~]+@[a-z\d\-]+(\.[a-z\d\-]+)+$/i;
          var httpHttpsRe = /^https?:\/\/.*$/;
          var wwwRe = /^www\./;
          var tldRe = /\.(?:org|net|com)(?:\:|\/|$)/;

          function urlize(str, length, nofollow) {
            if (isNaN(length)) {
              length = Infinity;
            }

            var noFollowAttr = nofollow === true ? ' rel="nofollow"' : '';
            var words = str.split(/(\s+)/).filter(function (word) {
              // If the word has no length, bail. This can happen for str with
              // trailing whitespace.
              return word && word.length;
            }).map(function (word) {
              var matches = word.match(puncRe);
              var possibleUrl = matches ? matches[1] : word;
              var shortUrl = possibleUrl.substr(0, length); // url that starts with http or https

              if (httpHttpsRe.test(possibleUrl)) {
                return "<a href=\"" + possibleUrl + "\"" + noFollowAttr + ">" + shortUrl + "</a>";
              } // url that starts with www.


              if (wwwRe.test(possibleUrl)) {
                return "<a href=\"http://" + possibleUrl + "\"" + noFollowAttr + ">" + shortUrl + "</a>";
              } // an email address of the form username@domain.tld


              if (emailRe.test(possibleUrl)) {
                return "<a href=\"mailto:" + possibleUrl + "\">" + possibleUrl + "</a>";
              } // url that ends in .com, .org or .net that is not an email address


              if (tldRe.test(possibleUrl)) {
                return "<a href=\"http://" + possibleUrl + "\"" + noFollowAttr + ">" + shortUrl + "</a>";
              }

              return word;
            });
            return words.join('');
          }

          exports.urlize = urlize;

          function wordcount(str) {
            str = normalize(str, '');
            var words = str ? str.match(/\w+/g) : null;
            return words ? words.length : null;
          }

          exports.wordcount = wordcount;

          function float(val, def) {
            var res = parseFloat(val);
            return isNaN(res) ? def : res;
          }

          exports.float = float;

          function int(val, def) {
            var res = parseInt(val, 10);
            return isNaN(res) ? def : res;
          }

          exports.int = int; // Aliases

          exports.d = exports.default;
          exports.e = exports.escape;
          /***/
        }, function (module, exports, __webpack_require__) {
          //
          // Permission is hereby granted, free of charge, to any person obtaining a
          // copy of this software and associated documentation files (the
          // "Software"), to deal in the Software without restriction, including
          // without limitation the rights to use, copy, modify, merge, publish,
          // distribute, sublicense, and/or sell copies of the Software, and to permit
          // persons to whom the Software is furnished to do so, subject to the
          // following conditions:
          //
          // The above copyright notice and this permission notice shall be included
          // in all copies or substantial portions of the Software.
          //
          // THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
          // OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
          // MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
          // NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
          // DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
          // OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
          // USE OR OTHER DEALINGS IN THE SOFTWARE.

          var R = typeof Reflect === 'object' ? Reflect : null;
          var ReflectApply = R && typeof R.apply === 'function' ? R.apply : function ReflectApply(target, receiver, args) {
            return Function.prototype.apply.call(target, receiver, args);
          };
          var ReflectOwnKeys;

          if (R && typeof R.ownKeys === 'function') {
            ReflectOwnKeys = R.ownKeys;
          } else if (Object.getOwnPropertySymbols) {
            ReflectOwnKeys = function ReflectOwnKeys(target) {
              return Object.getOwnPropertyNames(target).concat(Object.getOwnPropertySymbols(target));
            };
          } else {
            ReflectOwnKeys = function ReflectOwnKeys(target) {
              return Object.getOwnPropertyNames(target);
            };
          }

          function ProcessEmitWarning(warning) {
            if (console && console.warn) console.warn(warning);
          }

          var NumberIsNaN = Number.isNaN || function NumberIsNaN(value) {
            return value !== value;
          };

          function EventEmitter() {
            EventEmitter.init.call(this);
          }

          module.exports = EventEmitter; // Backwards-compat with node 0.10.x

          EventEmitter.EventEmitter = EventEmitter;
          EventEmitter.prototype._events = undefined;
          EventEmitter.prototype._eventsCount = 0;
          EventEmitter.prototype._maxListeners = undefined; // By default EventEmitters will print a warning if more than 10 listeners are
          // added to it. This is a useful default which helps finding memory leaks.

          var defaultMaxListeners = 10;
          Object.defineProperty(EventEmitter, 'defaultMaxListeners', {
            enumerable: true,
            get: function () {
              return defaultMaxListeners;
            },
            set: function (arg) {
              if (typeof arg !== 'number' || arg < 0 || NumberIsNaN(arg)) {
                throw new RangeError('The value of "defaultMaxListeners" is out of range. It must be a non-negative number. Received ' + arg + '.');
              }

              defaultMaxListeners = arg;
            }
          });

          EventEmitter.init = function () {
            if (this._events === undefined || this._events === Object.getPrototypeOf(this)._events) {
              this._events = Object.create(null);
              this._eventsCount = 0;
            }

            this._maxListeners = this._maxListeners || undefined;
          }; // Obviously not all Emitters should be limited to 10. This function allows
          // that to be increased. Set to zero for unlimited.


          EventEmitter.prototype.setMaxListeners = function setMaxListeners(n) {
            if (typeof n !== 'number' || n < 0 || NumberIsNaN(n)) {
              throw new RangeError('The value of "n" is out of range. It must be a non-negative number. Received ' + n + '.');
            }

            this._maxListeners = n;
            return this;
          };

          function $getMaxListeners(that) {
            if (that._maxListeners === undefined) return EventEmitter.defaultMaxListeners;
            return that._maxListeners;
          }

          EventEmitter.prototype.getMaxListeners = function getMaxListeners() {
            return $getMaxListeners(this);
          };

          EventEmitter.prototype.emit = function emit(type) {
            var args = [];

            for (var i = 1; i < arguments.length; i++) args.push(arguments[i]);

            var doError = type === 'error';
            var events = this._events;
            if (events !== undefined) doError = doError && events.error === undefined;else if (!doError) return false; // If there is no 'error' event listener then throw.

            if (doError) {
              var er;
              if (args.length > 0) er = args[0];

              if (er instanceof Error) {
                // Note: The comments on the `throw` lines are intentional, they show
                // up in Node's output if this results in an unhandled exception.
                throw er; // Unhandled 'error' event
              } // At least give some kind of context to the user


              var err = new Error('Unhandled error.' + (er ? ' (' + er.message + ')' : ''));
              err.context = er;
              throw err; // Unhandled 'error' event
            }

            var handler = events[type];
            if (handler === undefined) return false;

            if (typeof handler === 'function') {
              ReflectApply(handler, this, args);
            } else {
              var len = handler.length;
              var listeners = arrayClone(handler, len);

              for (var i = 0; i < len; ++i) ReflectApply(listeners[i], this, args);
            }

            return true;
          };

          function _addListener(target, type, listener, prepend) {
            var m;
            var events;
            var existing;

            if (typeof listener !== 'function') {
              throw new TypeError('The "listener" argument must be of type Function. Received type ' + typeof listener);
            }

            events = target._events;

            if (events === undefined) {
              events = target._events = Object.create(null);
              target._eventsCount = 0;
            } else {
              // To avoid recursion in the case that type === "newListener"! Before
              // adding it to the listeners, first emit "newListener".
              if (events.newListener !== undefined) {
                target.emit('newListener', type, listener.listener ? listener.listener : listener); // Re-assign `events` because a newListener handler could have caused the
                // this._events to be assigned to a new object

                events = target._events;
              }

              existing = events[type];
            }

            if (existing === undefined) {
              // Optimize the case of one listener. Don't need the extra array object.
              existing = events[type] = listener;
              ++target._eventsCount;
            } else {
              if (typeof existing === 'function') {
                // Adding the second element, need to change to array.
                existing = events[type] = prepend ? [listener, existing] : [existing, listener]; // If we've already got an array, just append.
              } else if (prepend) {
                existing.unshift(listener);
              } else {
                existing.push(listener);
              } // Check for listener leak


              m = $getMaxListeners(target);

              if (m > 0 && existing.length > m && !existing.warned) {
                existing.warned = true; // No error code for this since it is a Warning
                // eslint-disable-next-line no-restricted-syntax

                var w = new Error('Possible EventEmitter memory leak detected. ' + existing.length + ' ' + String(type) + ' listeners ' + 'added. Use emitter.setMaxListeners() to ' + 'increase limit');
                w.name = 'MaxListenersExceededWarning';
                w.emitter = target;
                w.type = type;
                w.count = existing.length;
                ProcessEmitWarning(w);
              }
            }

            return target;
          }

          EventEmitter.prototype.addListener = function addListener(type, listener) {
            return _addListener(this, type, listener, false);
          };

          EventEmitter.prototype.on = EventEmitter.prototype.addListener;

          EventEmitter.prototype.prependListener = function prependListener(type, listener) {
            return _addListener(this, type, listener, true);
          };

          function onceWrapper() {
            var args = [];

            for (var i = 0; i < arguments.length; i++) args.push(arguments[i]);

            if (!this.fired) {
              this.target.removeListener(this.type, this.wrapFn);
              this.fired = true;
              ReflectApply(this.listener, this.target, args);
            }
          }

          function _onceWrap(target, type, listener) {
            var state = {
              fired: false,
              wrapFn: undefined,
              target: target,
              type: type,
              listener: listener
            };
            var wrapped = onceWrapper.bind(state);
            wrapped.listener = listener;
            state.wrapFn = wrapped;
            return wrapped;
          }

          EventEmitter.prototype.once = function once(type, listener) {
            if (typeof listener !== 'function') {
              throw new TypeError('The "listener" argument must be of type Function. Received type ' + typeof listener);
            }

            this.on(type, _onceWrap(this, type, listener));
            return this;
          };

          EventEmitter.prototype.prependOnceListener = function prependOnceListener(type, listener) {
            if (typeof listener !== 'function') {
              throw new TypeError('The "listener" argument must be of type Function. Received type ' + typeof listener);
            }

            this.prependListener(type, _onceWrap(this, type, listener));
            return this;
          }; // Emits a 'removeListener' event if and only if the listener was removed.


          EventEmitter.prototype.removeListener = function removeListener(type, listener) {
            var list, events, position, i, originalListener;

            if (typeof listener !== 'function') {
              throw new TypeError('The "listener" argument must be of type Function. Received type ' + typeof listener);
            }

            events = this._events;
            if (events === undefined) return this;
            list = events[type];
            if (list === undefined) return this;

            if (list === listener || list.listener === listener) {
              if (--this._eventsCount === 0) this._events = Object.create(null);else {
                delete events[type];
                if (events.removeListener) this.emit('removeListener', type, list.listener || listener);
              }
            } else if (typeof list !== 'function') {
              position = -1;

              for (i = list.length - 1; i >= 0; i--) {
                if (list[i] === listener || list[i].listener === listener) {
                  originalListener = list[i].listener;
                  position = i;
                  break;
                }
              }

              if (position < 0) return this;
              if (position === 0) list.shift();else {
                spliceOne(list, position);
              }
              if (list.length === 1) events[type] = list[0];
              if (events.removeListener !== undefined) this.emit('removeListener', type, originalListener || listener);
            }

            return this;
          };

          EventEmitter.prototype.off = EventEmitter.prototype.removeListener;

          EventEmitter.prototype.removeAllListeners = function removeAllListeners(type) {
            var listeners, events, i;
            events = this._events;
            if (events === undefined) return this; // not listening for removeListener, no need to emit

            if (events.removeListener === undefined) {
              if (arguments.length === 0) {
                this._events = Object.create(null);
                this._eventsCount = 0;
              } else if (events[type] !== undefined) {
                if (--this._eventsCount === 0) this._events = Object.create(null);else delete events[type];
              }

              return this;
            } // emit removeListener for all listeners on all events


            if (arguments.length === 0) {
              var keys = Object.keys(events);
              var key;

              for (i = 0; i < keys.length; ++i) {
                key = keys[i];
                if (key === 'removeListener') continue;
                this.removeAllListeners(key);
              }

              this.removeAllListeners('removeListener');
              this._events = Object.create(null);
              this._eventsCount = 0;
              return this;
            }

            listeners = events[type];

            if (typeof listeners === 'function') {
              this.removeListener(type, listeners);
            } else if (listeners !== undefined) {
              // LIFO order
              for (i = listeners.length - 1; i >= 0; i--) {
                this.removeListener(type, listeners[i]);
              }
            }

            return this;
          };

          function _listeners(target, type, unwrap) {
            var events = target._events;
            if (events === undefined) return [];
            var evlistener = events[type];
            if (evlistener === undefined) return [];
            if (typeof evlistener === 'function') return unwrap ? [evlistener.listener || evlistener] : [evlistener];
            return unwrap ? unwrapListeners(evlistener) : arrayClone(evlistener, evlistener.length);
          }

          EventEmitter.prototype.listeners = function listeners(type) {
            return _listeners(this, type, true);
          };

          EventEmitter.prototype.rawListeners = function rawListeners(type) {
            return _listeners(this, type, false);
          };

          EventEmitter.listenerCount = function (emitter, type) {
            if (typeof emitter.listenerCount === 'function') {
              return emitter.listenerCount(type);
            } else {
              return listenerCount.call(emitter, type);
            }
          };

          EventEmitter.prototype.listenerCount = listenerCount;

          function listenerCount(type) {
            var events = this._events;

            if (events !== undefined) {
              var evlistener = events[type];

              if (typeof evlistener === 'function') {
                return 1;
              } else if (evlistener !== undefined) {
                return evlistener.length;
              }
            }

            return 0;
          }

          EventEmitter.prototype.eventNames = function eventNames() {
            return this._eventsCount > 0 ? ReflectOwnKeys(this._events) : [];
          };

          function arrayClone(arr, n) {
            var copy = new Array(n);

            for (var i = 0; i < n; ++i) copy[i] = arr[i];

            return copy;
          }

          function spliceOne(list, index) {
            for (; index + 1 < list.length; index++) list[index] = list[index + 1];

            list.pop();
          }

          function unwrapListeners(arr) {
            var ret = new Array(arr.length);

            for (var i = 0; i < ret.length; ++i) {
              ret[i] = arr[i].listener || arr[i];
            }

            return ret;
          }
          /***/

        }, function (module, exports, __webpack_require__) {

          var SafeString = __webpack_require__(2).SafeString;
          /**
           * Returns `true` if the object is a function, otherwise `false`.
           * @param { any } value
           * @returns { boolean }
           */


          function callable(value) {
            return typeof value === 'function';
          }

          exports.callable = callable;
          /**
           * Returns `true` if the object is strictly not `undefined`.
           * @param { any } value
           * @returns { boolean }
           */

          function defined(value) {
            return value !== undefined;
          }

          exports.defined = defined;
          /**
           * Returns `true` if the operand (one) is divisble by the test's argument
           * (two).
           * @param { number } one
           * @param { number } two
           * @returns { boolean }
           */

          function divisibleby(one, two) {
            return one % two === 0;
          }

          exports.divisibleby = divisibleby;
          /**
           * Returns true if the string has been escaped (i.e., is a SafeString).
           * @param { any } value
           * @returns { boolean }
           */

          function escaped(value) {
            return value instanceof SafeString;
          }

          exports.escaped = escaped;
          /**
           * Returns `true` if the arguments are strictly equal.
           * @param { any } one
           * @param { any } two
           */

          function equalto(one, two) {
            return one === two;
          }

          exports.equalto = equalto; // Aliases

          exports.eq = exports.equalto;
          exports.sameas = exports.equalto;
          /**
           * Returns `true` if the value is evenly divisible by 2.
           * @param { number } value
           * @returns { boolean }
           */

          function even(value) {
            return value % 2 === 0;
          }

          exports.even = even;
          /**
           * Returns `true` if the value is falsy - if I recall correctly, '', 0, false,
           * undefined, NaN or null. I don't know if we should stick to the default JS
           * behavior or attempt to replicate what Python believes should be falsy (i.e.,
           * empty arrays, empty dicts, not 0...).
           * @param { any } value
           * @returns { boolean }
           */

          function falsy(value) {
            return !value;
          }

          exports.falsy = falsy;
          /**
           * Returns `true` if the operand (one) is greater or equal to the test's
           * argument (two).
           * @param { number } one
           * @param { number } two
           * @returns { boolean }
           */

          function ge(one, two) {
            return one >= two;
          }

          exports.ge = ge;
          /**
           * Returns `true` if the operand (one) is greater than the test's argument
           * (two).
           * @param { number } one
           * @param { number } two
           * @returns { boolean }
           */

          function greaterthan(one, two) {
            return one > two;
          }

          exports.greaterthan = greaterthan; // alias

          exports.gt = exports.greaterthan;
          /**
           * Returns `true` if the operand (one) is less than or equal to the test's
           * argument (two).
           * @param { number } one
           * @param { number } two
           * @returns { boolean }
           */

          function le(one, two) {
            return one <= two;
          }

          exports.le = le;
          /**
           * Returns `true` if the operand (one) is less than the test's passed argument
           * (two).
           * @param { number } one
           * @param { number } two
           * @returns { boolean }
           */

          function lessthan(one, two) {
            return one < two;
          }

          exports.lessthan = lessthan; // alias

          exports.lt = exports.lessthan;
          /**
           * Returns `true` if the string is lowercased.
           * @param { string } value
           * @returns { boolean }
           */

          function lower(value) {
            return value.toLowerCase() === value;
          }

          exports.lower = lower;
          /**
           * Returns `true` if the operand (one) is less than or equal to the test's
           * argument (two).
           * @param { number } one
           * @param { number } two
           * @returns { boolean }
           */

          function ne(one, two) {
            return one !== two;
          }

          exports.ne = ne;
          /**
           * Returns true if the value is strictly equal to `null`.
           * @param { any }
           * @returns { boolean }
           */

          function nullTest(value) {
            return value === null;
          }

          exports.null = nullTest;
          /**
           * Returns true if value is a number.
           * @param { any }
           * @returns { boolean }
           */

          function number(value) {
            return typeof value === 'number';
          }

          exports.number = number;
          /**
           * Returns `true` if the value is *not* evenly divisible by 2.
           * @param { number } value
           * @returns { boolean }
           */

          function odd(value) {
            return value % 2 === 1;
          }

          exports.odd = odd;
          /**
           * Returns `true` if the value is a string, `false` if not.
           * @param { any } value
           * @returns { boolean }
           */

          function string(value) {
            return typeof value === 'string';
          }

          exports.string = string;
          /**
           * Returns `true` if the value is not in the list of things considered falsy:
           * '', null, undefined, 0, NaN and false.
           * @param { any } value
           * @returns { boolean }
           */

          function truthy(value) {
            return !!value;
          }

          exports.truthy = truthy;
          /**
           * Returns `true` if the value is undefined.
           * @param { any } value
           * @returns { boolean }
           */

          function undefinedTest(value) {
            return value === undefined;
          }

          exports.undefined = undefinedTest;
          /**
           * Returns `true` if the string is uppercased.
           * @param { string } value
           * @returns { boolean }
           */

          function upper(value) {
            return value.toUpperCase() === value;
          }

          exports.upper = upper;
          /**
           * If ES6 features are available, returns `true` if the value implements the
           * `Symbol.iterator` method. If not, it's a string or Array.
           *
           * Could potentially cause issues if a browser exists that has Set and Map but
           * not Symbol.
           *
           * @param { any } value
           * @returns { boolean }
           */

          function iterable(value) {
            if (typeof Symbol !== 'undefined') {
              return !!value[Symbol.iterator];
            } else {
              return Array.isArray(value) || typeof value === 'string';
            }
          }

          exports.iterable = iterable;
          /**
           * If ES6 features are available, returns `true` if the value is an object hash
           * or an ES6 Map. Otherwise just return if it's an object hash.
           * @param { any } value
           * @returns { boolean }
           */

          function mapping(value) {
            // only maps and object hashes
            var bool = value !== null && value !== undefined && typeof value === 'object' && !Array.isArray(value);

            if (Set) {
              return bool && !(value instanceof Set);
            } else {
              return bool;
            }
          }

          exports.mapping = mapping;
          /***/
        }, function (module, exports, __webpack_require__) {

          function _cycler(items) {
            var index = -1;
            return {
              current: null,
              reset: function reset() {
                index = -1;
                this.current = null;
              },
              next: function next() {
                index++;

                if (index >= items.length) {
                  index = 0;
                }

                this.current = items[index];
                return this.current;
              }
            };
          }

          function _joiner(sep) {
            sep = sep || ',';
            var first = true;
            return function () {
              var val = first ? '' : sep;
              first = false;
              return val;
            };
          } // Making this a function instead so it returns a new object
          // each time it's called. That way, if something like an environment
          // uses it, they will each have their own copy.


          function globals() {
            return {
              range: function range(start, stop, step) {
                if (typeof stop === 'undefined') {
                  stop = start;
                  start = 0;
                  step = 1;
                } else if (!step) {
                  step = 1;
                }

                var arr = [];

                if (step > 0) {
                  for (var i = start; i < stop; i += step) {
                    arr.push(i);
                  }
                } else {
                  for (var _i = start; _i > stop; _i += step) {
                    // eslint-disable-line for-direction
                    arr.push(_i);
                  }
                }

                return arr;
              },
              cycler: function cycler() {
                return _cycler(Array.prototype.slice.call(arguments));
              },
              joiner: function joiner(sep) {
                return _joiner(sep);
              }
            };
          }

          module.exports = globals;
          /***/
        }, function (module, exports, __webpack_require__) {
          var path = __webpack_require__(0);

          module.exports = function express(env, app) {
            function NunjucksView(name, opts) {
              this.name = name;
              this.path = name;
              this.defaultEngine = opts.defaultEngine;
              this.ext = path.extname(name);

              if (!this.ext && !this.defaultEngine) {
                throw new Error('No default engine was specified and no extension was provided.');
              }

              if (!this.ext) {
                this.name += this.ext = (this.defaultEngine[0] !== '.' ? '.' : '') + this.defaultEngine;
              }
            }

            NunjucksView.prototype.render = function render(opts, cb) {
              env.render(this.name, opts, cb);
            };

            app.set('view', NunjucksView);
            app.set('nunjucksEnv', env);
            return env;
          };
          /***/

        }, function (module, exports, __webpack_require__) {
          function installCompat() {
            /* eslint-disable camelcase */
            // This must be called like `nunjucks.installCompat` so that `this`
            // references the nunjucks instance

            var runtime = this.runtime;
            var lib = this.lib; // Handle slim case where these 'modules' are excluded from the built source

            var Compiler = this.compiler.Compiler;
            var Parser = this.parser.Parser;
            this.nodes;
            this.lexer;
            var orig_contextOrFrameLookup = runtime.contextOrFrameLookup;
            var orig_memberLookup = runtime.memberLookup;
            var orig_Compiler_assertType;
            var orig_Parser_parseAggregate;

            if (Compiler) {
              orig_Compiler_assertType = Compiler.prototype.assertType;
            }

            if (Parser) {
              orig_Parser_parseAggregate = Parser.prototype.parseAggregate;
            }

            function uninstall() {
              runtime.contextOrFrameLookup = orig_contextOrFrameLookup;
              runtime.memberLookup = orig_memberLookup;

              if (Compiler) {
                Compiler.prototype.assertType = orig_Compiler_assertType;
              }

              if (Parser) {
                Parser.prototype.parseAggregate = orig_Parser_parseAggregate;
              }
            }

            runtime.contextOrFrameLookup = function contextOrFrameLookup(context, frame, key) {
              var val = orig_contextOrFrameLookup.apply(this, arguments);

              if (val !== undefined) {
                return val;
              }

              switch (key) {
                case 'True':
                  return true;

                case 'False':
                  return false;

                case 'None':
                  return null;

                default:
                  return undefined;
              }
            };

            function sliceLookup(obj, start, stop, step) {
              obj = obj || [];

              if (start === null) {
                start = step < 0 ? obj.length - 1 : 0;
              }

              if (stop === null) {
                stop = step < 0 ? -1 : obj.length;
              } else if (stop < 0) {
                stop += obj.length;
              }

              if (start < 0) {
                start += obj.length;
              }

              var results = [];

              for (var i = start;; i += step) {
                if (i < 0 || i > obj.length) {
                  break;
                }

                if (step > 0 && i >= stop) {
                  break;
                }

                if (step < 0 && i <= stop) {
                  break;
                }

                results.push(runtime.memberLookup(obj, i));
              }

              return results;
            }

            function hasOwnProp(obj, key) {
              return Object.prototype.hasOwnProperty.call(obj, key);
            }

            var ARRAY_MEMBERS = {
              pop: function pop(index) {
                if (index === undefined) {
                  return this.pop();
                }

                if (index >= this.length || index < 0) {
                  throw new Error('KeyError');
                }

                return this.splice(index, 1);
              },
              append: function append(element) {
                return this.push(element);
              },
              remove: function remove(element) {
                for (var i = 0; i < this.length; i++) {
                  if (this[i] === element) {
                    return this.splice(i, 1);
                  }
                }

                throw new Error('ValueError');
              },
              count: function count(element) {
                var count = 0;

                for (var i = 0; i < this.length; i++) {
                  if (this[i] === element) {
                    count++;
                  }
                }

                return count;
              },
              index: function index(element) {
                var i;

                if ((i = this.indexOf(element)) === -1) {
                  throw new Error('ValueError');
                }

                return i;
              },
              find: function find(element) {
                return this.indexOf(element);
              },
              insert: function insert(index, elem) {
                return this.splice(index, 0, elem);
              }
            };
            var OBJECT_MEMBERS = {
              items: function items() {
                return lib._entries(this);
              },
              values: function values() {
                return lib._values(this);
              },
              keys: function keys() {
                return lib.keys(this);
              },
              get: function get(key, def) {
                var output = this[key];

                if (output === undefined) {
                  output = def;
                }

                return output;
              },
              has_key: function has_key(key) {
                return hasOwnProp(this, key);
              },
              pop: function pop(key, def) {
                var output = this[key];

                if (output === undefined && def !== undefined) {
                  output = def;
                } else if (output === undefined) {
                  throw new Error('KeyError');
                } else {
                  delete this[key];
                }

                return output;
              },
              popitem: function popitem() {
                var keys = lib.keys(this);

                if (!keys.length) {
                  throw new Error('KeyError');
                }

                var k = keys[0];
                var val = this[k];
                delete this[k];
                return [k, val];
              },
              setdefault: function setdefault(key, def) {
                if (def === void 0) {
                  def = null;
                }

                if (!(key in this)) {
                  this[key] = def;
                }

                return this[key];
              },
              update: function update(kwargs) {
                lib._assign(this, kwargs);

                return null; // Always returns None
              }
            };
            OBJECT_MEMBERS.iteritems = OBJECT_MEMBERS.items;
            OBJECT_MEMBERS.itervalues = OBJECT_MEMBERS.values;
            OBJECT_MEMBERS.iterkeys = OBJECT_MEMBERS.keys;

            runtime.memberLookup = function memberLookup(obj, val, autoescape) {
              if (arguments.length === 4) {
                return sliceLookup.apply(this, arguments);
              }

              obj = obj || {}; // If the object is an object, return any of the methods that Python would
              // otherwise provide.

              if (lib.isArray(obj) && hasOwnProp(ARRAY_MEMBERS, val)) {
                return ARRAY_MEMBERS[val].bind(obj);
              }

              if (lib.isObject(obj) && hasOwnProp(OBJECT_MEMBERS, val)) {
                return OBJECT_MEMBERS[val].bind(obj);
              }

              return orig_memberLookup.apply(this, arguments);
            };

            return uninstall;
          }

          module.exports = installCompat;
          /***/
        }])
      );
    });
  })(nunjucksSlim);

  var nunjucks = /*@__PURE__*/getDefaultExportFromCjs(nunjucksSlim.exports);

  /**
   * Nunjucks {% spaceless %} extension
   * from `vf-core`
   */

  function SpacelessExtension() {
    this.tags = ['spaceless'];

    this.parse = function (parser, nodes, lexer) {
      var tok = parser.nextToken();
      var args = parser.parseSignature(null, true);
      parser.advanceAfterBlockEnd(tok.value);
      var body = parser.parseUntilBlocks('error', 'endspaceless');
      var errorBody = null;

      if (parser.skipSymbol('error')) {
        parser.skip(lexer.TOKEN_BLOCK_END);
        errorBody = parser.parseUntilBlocks('endremote');
      }

      parser.advanceAfterBlockEnd();
      return new nodes.CallExtension(this, 'run', args, [body, errorBody]);
    };

    this.run = function (context, body) {
      return body().replace(/\s+/g, ' ').replace(/>\s</g, '><');
    };
  } // export default SpacelessExtension;


  var nunjucksSpaceless = SpacelessExtension;

  /**
   * Return Nunjucks environment object
   */

  const useNunjucks = () => {
    // initialise on first request to ensure precompiled templates exist
    if (!window.vfNunjucks) {
      const env = new nunjucks.Environment(null, {
        lstripBlocks: true,
        trimBlocks: true,
        autoescape: false
      });
      env.addExtension('spaceless', new nunjucksSpaceless());
      window.vfNunjucks = env;
    }

    return window.vfNunjucks;
  };

  /**
   * Render the template for a VF Gutenberg block asynchronously
   */

  const useVFRenderTemplate = (name, attrs) => {
    try {
      const nunjucks = useNunjucks();
      const html = nunjucks.render(name.replace(/^vf\//, 'vf-'), attrs);
      return {
        html: html,
        hash: useHashsum(html)
      };
    } catch (err) {
      console.log(err);
      return null;
    }
  };

  const renderStore$1 = {};

  const useVFRender = props => {
    const [data, setData] = React.useState(null);
    const [isLoading, setLoading] = React.useState(false);

    if (props.isRenderable === false) {
      return [data, false];
    }

    const hasTemplate = ('render' in props.attributes); // extract attributes and remove protected

    const renderAttrs = { ...props.attributes,
      ...(props.transient || {})
    };
    delete renderAttrs['ver'];
    delete renderAttrs['mode'];
    delete renderAttrs['render'];
    const renderHash = useHashsum([props.name, renderAttrs]);

    const fetchData = async () => {
      if (renderStore$1.hasOwnProperty(renderHash)) {
        setData(renderStore$1[renderHash]);
        return;
      }

      let newData = null;

      if (hasTemplate) {
        newData = useVFRenderTemplate(props.name, renderAttrs);
      } else {
        return;
      }

      renderStore$1[renderHash] = newData;
      setData(newData);
    }; // provide attributes hash to avoid rerenders


    React.useEffect(() => {
      fetchData();
    }, [renderHash, props.isEditing]);

    if (data && hasTemplate) {
      props.setAttributes({
        render: data.html
      });
    }

    return [data, isLoading];
  };

  /**
   * VFBlockControls (component)
   * Toolbar to toggle between "edit" and "view" modes.
   */

  const EditButton = ({
    onClick
  }) => wp.element.createElement(components.Button, {
    label: i18n.__('Edit'),
    icon: "edit",
    onClick: onClick
  }); // The togglable "View" button added to `BlockControls`


  const ViewButton = ({
    onClick
  }) => wp.element.createElement(components.Button, {
    label: i18n.__('Preview'),
    icon: "visibility",
    onClick: onClick
  });

  const VFBlockControls = props => {
    const {
      isEditing,
      onToggle
    } = props;
    return wp.element.createElement(blockEditor.BlockControls, null, wp.element.createElement(components.Toolbar, null, isEditing ? wp.element.createElement(ViewButton, {
      onClick: onToggle
    }) : wp.element.createElement(EditButton, {
      onClick: onToggle
    })));
  };

  /**
   * BlockEdit (component)
   * In "edit" mode render the editing controls. This component is sparse
   * because blocks will provide their own children, or use `VFBlockFields`
   * to automatically generate controls.
   */

  const UpdateButton = ({
    onClick
  }) => wp.element.createElement(components.Button, {
    isSecondary: true,
    onClick: onClick
  }, i18n.__('Preview'));

  const VFBlockEdit = props => {
    const {
      onToggle
    } = props;
    return wp.element.createElement("div", {
      className: "vf-block__edit"
    }, props.children, onToggle && wp.element.createElement(UpdateButton, {
      onClick: onToggle
    }));
  };

  /**
   * Return `onLoad` and `onUnload` functions for an iframe.
   * Adjust iframe height automatically whilst mounted.
   */
  const useVFIFrame = (iframe, iframeHTML, onHeight) => {
    // TOTO: handle by global in `vf-blocks.jsx` - is onHeight necessary?
    // update iframe height from `postMessage` event
    const onMessage = ({
      data
    }) => {
      if (data !== Object(data) || data.id !== iframe.id) {
        return;
      }

      window.requestAnimationFrame(() => {
        // TODO: now handled by global
        // iframe.style.height = `${data.height}px`;
        onHeight(data.height);
      });
    };

    const onLoad = () => {
      if (!iframe.vfActive) {
        window.addEventListener('message', onMessage);
      }

      iframe.vfActive = true; // set HTML content for block

      const body = iframe.contentWindow.document.body;

      if (iframeHTML) {
        body.innerHTML = iframeHTML;
      } // create and append script to handle automatic iframe resize
      // this cannot be inline of `html` for browser security


      const script = document.createElement('script');
      script.type = 'text/javascript';
      script.innerHTML = `
if (ResizeObserver) {
  const observer = new ResizeObserver(entries => {
    entries.forEach(entry => {
      window.parent.postMessage({
          id: '${iframe.id}',
          height: entry.contentRect.height
        }, '*'
      );
    });
  });
  observer.observe(document.body);
} else {
  const vfResize = () => {
    window.parent.postMessage({
        id: '${iframe.id}',
        height: document.documentElement.scrollHeight
      }, '*'
    );
  };
  window.addEventListener('resize', vfResize);
  setTimeout(vfResize, 100);
  vfResize();
}
    `;
      body.appendChild(script);
    }; // cleanup function for dismount


    const onUnload = () => {
      window.removeEventListener('message', onMessage);
      iframe.vfActive = false;
    };

    return {
      onLoad,
      onUnload
    };
  };

  /**
   * BlockView (component)
   * In "view" mode; fetch a block template from the VF-WP plugin and render
   * within an iframe (to scope CSS and JavaScript).
   */

  const VFBlockView = ({
    render,
    uniqueId,
    onHeight
  }) => {
    const {
      renderPrefix,
      renderSuffix
    } = useVFGutenberg(); // Use existing iframe appended to the DOM

    const iframeId = `vfwp_${uniqueId}`;
    const iframeHTML = render.html ? `${renderPrefix}${render.html}${renderSuffix}` : '';
    let iframe = document.getElementById(iframeId); // or create a new iframe element

    if (!iframe) {
      iframe = document.createElement('iframe');
      iframe.id = iframeId;
      iframe.className = 'vf-block__iframe';
      iframe.setAttribute('scrolling', 'no');

      if (render.src) {
        iframe.src = render.src;
      }
    } // Use an asynchronous hook to fetch the iframe content via WordPress API


    const rootEl = React.useRef();
    const {
      onLoad,
      onUnload
    } = useVFIFrame(iframe, iframeHTML, onHeight); // Append the iframe element on mount - we cannot use `<iframe onLoad={} />`
    // in React, this does not fire properly in Webkit browsers for
    // iframe elements when `src` is empty

    React.useEffect(() => {
      iframe.addEventListener('load', ev => onLoad(ev));
      rootEl.current.appendChild(iframe); // Cleanup after dismount

      return () => {
        onUnload();
      };
    }, [render.hash]);
    return wp.element.createElement("div", {
      className: "vf-block__view",
      ref: rootEl
    });
  }; // Memoize to avoid unnecessary heavy updates


  var VFBlockView$1 = /*#__PURE__*/React__default['default'].memo(VFBlockView);

  /**
   * VFBlock (component)
   * Base component for VF Gutenberg blocks.
   * The component supports "edit" and "view" modes.
   */

  const VFBlock = props => {
    const {
      isEditing,
      isEditable,
      isPlugin,
      isRenderable,
      isSelected
    } = props;
    const uniqueId = useUniqueId(props);
    const [render, isLoading] = useVFRender(props);
    const [minHeight, setMinHeight] = React.useState(100); // ensure version is encoded in post content

    if (!props.attributes.ver) {
      const newAttr = {
        ver: props.ver || '1.0.0'
      }; // use defaults for VF plugin blocks

      if (isPlugin) {
        newAttr.defaults = 1;
      }

      props.setAttributes(newAttr);
    } // callback to toggle block mode


    const onToggle = () => {
      props.setAttributes({
        mode: isEditing ? 'view' : 'edit'
      });
    }; // show block controls if both modes exist


    const hasControls = isEditable && isRenderable; // show "edit" mode when edit state is active

    const hasEdit = isEditable && isEditing; // show "view" mode when not editing and render is available

    const hasView = !hasEdit && !isLoading && render; // height change callback

    const onHeight = height => height !== minHeight && setMinHeight(height);

    if (hasEdit) {
      onHeight(100);
    } // add DOM attributes for styling


    const rootAttrs = {
      className: `vf-block ${props.className}`,
      'data-ver': props.attributes.ver,
      'data-name': props.name,
      'data-editing': isEditing,
      'data-loading': isLoading,
      'data-selected': isSelected,
      style: {
        minHeight: `${minHeight}px`
      }
    };
    return wp.element.createElement(React.Fragment, null, hasControls && wp.element.createElement(VFBlockControls, {
      isEditing,
      onToggle
    }), wp.element.createElement("div", rootAttrs, hasEdit && wp.element.createElement(VFBlockEdit, {
      onToggle: isRenderable ? onToggle : null,
      children: props.children
    }), hasView && wp.element.createElement(VFBlockView$1, {
      render: render,
      uniqueId: uniqueId,
      onHeight: onHeight
    }), isLoading && wp.element.createElement(components.Spinner, null)));
  };

  const useVFCoreSettings = settings => {
    const defaults = useVFDefaults(); // get block settings

    let {
      attributes,
      example,
      fields,
      styles,
      allowedBlocks
    } = settings; // block options

    const hasBlocks = Array.isArray(allowedBlocks);
    const hasFields = !!(Array.isArray(fields) && fields.length);
    const hasStyles = !!(Array.isArray(styles) && styles.length); // Assume true unless specifically opted out

    const isRenderable = settings.isRenderable !== false; // Setup block attributes

    attributes = { ...defaults.attributes,
      ...(attributes || {}),
      __isExample: {
        type: 'integer',
        default: 0
      }
    }; // Setup example attributes

    example = { ...example
    };
    example.attributes = { ...example.attributes,
      mode: 'view',
      __isExample: 1
    }; // Enable `render` attribute for Nunjucks template

    if (isRenderable) {
      attributes.render = {
        type: 'string',
        default: ''
      };
    } // Enable `mode` attribute


    if (hasFields || hasBlocks) {
      attributes.mode = {
        type: 'string',
        default: 'edit'
      };
    } // Sort the fields into their locations


    const blockFields = [];
    const inspectorFields = [];

    if (hasFields) {
      fields.forEach(field => {
        if (field.inspector) {
          inspectorFields.push(field);
        } else {
          blockFields.push(field);
        }
      });
    }

    const Save = () => {
      return hasBlocks ? wp.element.createElement(React.Fragment, null, wp.element.createElement(blockEditor.InnerBlocks.Content, null)) : null;
    };

    let Edit = props => {
      const ver = settings.ver;
      const isEditable = !!(props.attributes.mode && props.attributes.__isExample !== 1);
      const isEditing = props.attributes.mode === 'edit';
      return wp.element.createElement(React.Fragment, null, wp.element.createElement(VFBlock, _extends({}, props, {
        ver: ver,
        isRenderable: isRenderable,
        isEditable: isEditable,
        isEditing: isEditing
      }), !!blockFields.length && wp.element.createElement(VFBlockFields, _extends({}, props, {
        fields: blockFields
      })), hasBlocks && wp.element.createElement(blockEditor.InnerBlocks, {
        allowedBlocks: allowedBlocks
      })), !!inspectorFields.length && wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
        title: i18n.__('Settings'),
        initialOpen: false
      }, wp.element.createElement(VFBlockFields, _extends({}, props, {
        fields: inspectorFields
      })))));
    }; // Wrap higher-order components


    if (Array.isArray(settings.withHOC)) {
      settings.withHOC.forEach(([HoC, ...args]) => Edit = HoC(Edit, ...args));
    } // Return the Gutenberg settings


    return { ...defaults,
      name: settings.name,
      title: settings.title,
      category: 'vf/core',
      parent: settings.parent || null,
      description: i18n.__('Visual Framework (core)'),
      keywords: [...defaults.keywords],
      attributes: attributes,
      example: example,
      styles: hasStyles ? styles : [],
      supports: { ...defaults.supports,
        customClassName: hasStyles,
        inserter: settings.isInsertable !== false
      },
      transforms: { ...(settings.transforms || null)
      },
      edit: Edit,
      save: Save
    };
  };

  /**
   * Block transforms for: `vf/blockquote`
   */
  const fromParagraph = () => {
    return {
      type: 'block',
      blocks: ['core/paragraph'],
      transform: attributes => {
        return blocks.createBlock('vf/blockquote', {
          html: attributes.content
        });
      }
    };
  };
  const fromQuote = () => {
    return {
      type: 'block',
      blocks: ['core/quote'],
      transform: attributes => {
        let {
          citation,
          value
        } = attributes;

        if (/^\s*$/.test(citation)) {
          citation = '';
        }

        return blocks.createBlock('vf/blockquote', {
          html: value + citation
        });
      }
    };
  };

  /**
   * Precompiled Nunjucks template: vf-blockquote.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-blockquote"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  if(runtime.contextOrFrameLookup(context, frame, "context")) {
  var t_1;
  t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
  frame.set("id", t_1, true);
  if(frame.topLevel) {
  context.setVariable("id", t_1);
  }
  if(frame.topLevel) {
  context.addExport("id", t_1);
  }
  var t_2;
  t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
  frame.set("override_class", t_2, true);
  if(frame.topLevel) {
  context.setVariable("override_class", t_2);
  }
  if(frame.topLevel) {
  context.addExport("override_class", t_2);
  }
  var t_3;
  t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"blockquote_text");
  frame.set("blockquote_text", t_3, true);
  if(frame.topLevel) {
  context.setVariable("blockquote_text", t_3);
  }
  if(frame.topLevel) {
  context.addExport("blockquote_text", t_3);
  }
  var t_4;
  t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"blockquote_citation");
  frame.set("blockquote_citation", t_4, true);
  if(frame.topLevel) {
  context.setVariable("blockquote_citation", t_4);
  }
  if(frame.topLevel) {
  context.addExport("blockquote_citation", t_4);
  }
  ;
  }
  output += "\n<blockquote";
  if(runtime.contextOrFrameLookup(context, frame, "id")) {
  output += " id=\"";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
  output += "\"";
  ;
  }
  output += " class=\"vf-blockquote";
  if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
  output += " | ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
  ;
  }
  output += " | vf-stack vf-stack--400\">\n";
  if((runtime.contextOrFrameLookup(context, frame, "blockquote_text")) || (runtime.contextOrFrameLookup(context, frame, "html")) || (runtime.contextOrFrameLookup(context, frame, "text"))) {
  output += "    <p class=\"vf-blockquote__text\">";
  output += runtime.suppressValue(((runtime.contextOrFrameLookup(context, frame, "html")) || (runtime.contextOrFrameLookup(context, frame, "blockquote_text"))?(runtime.contextOrFrameLookup(context, frame, "html")) || env.getFilter("safe").call(context, (runtime.contextOrFrameLookup(context, frame, "blockquote_text"))):runtime.contextOrFrameLookup(context, frame, "text")), env.opts.autoescape);
  output += "</p>\n";
  ;
  }
  output += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "blockquote_citation")) {
  output += "  <footer class=\"vf-blockquote__footer\">\n    <cite class=\"vf-blockquote__citation\">";
  output += runtime.suppressValue(env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "blockquote_citation")), env.opts.autoescape);
  output += "</cite>\n  </footer>\n";
  ;
  }
  output += "\n</blockquote>\n";
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Blockquote
  */
  var vfBlockquote = useVFCoreSettings({
    name: 'vf/blockquote',
    title: i18n.__('Blockquote'),
    attributes: {
      html: {
        type: 'string'
      }
    },
    fields: [{
      name: 'html',
      control: 'rich',
      default: '',
      label: '',
      tag: 'p',
      placeholder: i18n.__('Type blockquote…')
    }],
    transforms: {
      from: [fromParagraph(), fromQuote()]
    }
  });

  /**
  Block Name: Breadcrumbs Item
  */
  var vfBreadcrumbsItem = useVFCoreSettings({
    name: 'vf/breadcrumbs-item',
    title: i18n.__('Breadcrumbs Item'),
    parent: ['vf/breadcrumbs'],
    isRenderable: false,
    attributes: {
      text: {
        type: 'string',
        default: i18n.__('Breadcrumb')
      },
      url: {
        type: 'string',
        default: '/'
      }
    },
    fields: [{
      name: 'text',
      control: 'text',
      label: i18n.__('Text')
    }, {
      name: 'url',
      control: 'url',
      label: i18n.__('URL')
    }]
  });

  /**
   * Wrap block edit function to add transient property
   * assigned to custom attribute.
   */

  const withTransientAttribute = (Edit, attr) => {
    return props => {
      return Edit({ ...props,
        transient: { ...(props.transient || {}),
          [attr.key]: attr.value || props.attributes[attr.key]
        }
      });
    };
  };
  /**
   * Wrap block edit function to add block style as transient property
   * Optionally use BEM notation
   */

  const withTransientStyle = (Edit, opts) => {
    return props => {
      const isBEM = ('BEM' in opts);
      const style = useStyleName(props.className);
      const name = props.name.replace(/^vf\//, 'vf-');
      const value = isBEM ? `${name}--${style}` : style;

      if (isBEM && !style) {
        return Edit(props);
      }

      return withTransientAttribute(Edit, {
        key: opts.key,
        value
      })(props);
    };
  };
  /**
   * Wrap the Gutenberg block settings `edit` function.
   * Map block attributes to transient ones to support potential compatibility
   * changes to the Nunjucks template; example:

    settings.edit = withTransientAttributeMap(settings.edit, [
      {from: 'text', to: 'vf_lede_text'}
    ]);

   */

  const withTransientAttributeMap = (Edit, map) => {
    return props => {
      // props.transient = {
      //   ...(props.transient || {})
      // };
      const transient = { ...(props.transient || {})
      };
      map.forEach(item => {
        if (props.attributes.hasOwnProperty(item.from)) {
          transient[item.to] = props.attributes[item.from];
        }
      });
      return Edit({ ...props,
        transient
      });
    };
  };
  /**
   * Wrap the Gutenberg block settings `edit` function.
   * Add `<InnerBlocks.Content />` content as a transient property.
   */

  const withTransientInnerBlocks = Edit => {
    return props => {
      const innerBlocks = data$1.select('core/block-editor').getBlocks(props.clientId);
      const transient = { ...(props.transient || {}),
        innerBlocks: []
      };
      innerBlocks.forEach(block => transient.innerBlocks.push({
        name: block.name,
        attributes: { ...block.attributes
        }
      }));
      return Edit({ ...props,
        transient
      });
    };
  };

  /**
   * Precompiled Nunjucks template: vf-breadcrumbs.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-breadcrumbs"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  output += "<nav class=\"vf-breadcrumbs\" aria-label=\"Breadcrumb\">\n  <ul class=\"vf-breadcrumbs__list | vf-list vf-list--inline\">\n";
  frame = frame.push();
  var t_3 = runtime.contextOrFrameLookup(context, frame, "breadcrumbs");
  if(t_3) {t_3 = runtime.fromIterator(t_3);
  var t_2 = t_3.length;
  for(var t_1=0; t_1 < t_3.length; t_1++) {
  var t_4 = t_3[t_1];
  frame.set("breadcrumb", t_4);
  frame.set("loop.index", t_1 + 1);
  frame.set("loop.index0", t_1);
  frame.set("loop.revindex", t_2 - t_1);
  frame.set("loop.revindex0", t_2 - t_1 - 1);
  frame.set("loop.first", t_1 === 0);
  frame.set("loop.last", t_1 === t_2 - 1);
  frame.set("loop.length", t_2);
  output += "    <li class=\"vf-breadcrumbs__item\"\n";
  if(runtime.memberLookup((t_4),"currentPage")) {
  output += " aria-current=\"location\"";
  ;
  }
  output += ">\n";
  if(runtime.memberLookup((t_4),"breadcrumb_href")) {
  output += "      <a href=\"";
  output += runtime.suppressValue(runtime.memberLookup((t_4),"breadcrumb_href"), env.opts.autoescape);
  output += "\" class=\"vf-breadcrumbs__link\">";
  output += runtime.suppressValue(runtime.memberLookup((t_4),"text"), env.opts.autoescape);
  output += "</a>\n";
  ;
  }
  else {
  output += "      ";
  output += runtime.suppressValue(runtime.memberLookup((t_4),"text"), env.opts.autoescape);
  output += "\n";
  ;
  }
  output += "    </li>\n";
  ;
  }
  }
  frame = frame.pop();
  output += "  </ul>\n</nav>\n";
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Breadcrumbs
  */

  const withBreadcrumbsItems = Edit => {
    return props => {
      const transient = { ...(props.transient || {})
      };
      transient.breadcrumbs = [];

      if (Array.isArray(transient.innerBlocks)) {
        transient.innerBlocks.forEach(block => {
          if (block.name === 'vf/breadcrumbs-item') {
            transient.breadcrumbs.push({
              text: block.attributes.text,
              url: block.attributes.url,
              // Nunjucks template changed from `url`?
              breadcrumb_href: block.attributes.url
            });
          }
        });
      }

      return Edit({ ...props,
        transient
      });
    };
  };

  var vfBreadcrumbs = useVFCoreSettings({
    name: 'vf/breadcrumbs',
    title: i18n.__('Breadcrumbs'),
    allowedBlocks: ['vf/breadcrumbs-item'],
    withHOC: [[withBreadcrumbsItems], [withTransientInnerBlocks]]
  });

  const defaults$6 = useVFDefaults();
  const ver$3 = '1.0.0';
  const settings$b = { ...defaults$6,
    name: 'vf/cluster',
    title: i18n.__('VF Cluster'),
    category: 'vf/core',
    description: i18n.__('Visual Framework (core)'),
    attributes: { ...defaults$6.attributes,
      alignment: {
        type: 'string',
        default: 'center'
      },
      spacing: {
        type: 'string',
        default: 'small'
      },
      customSpacing: {
        type: 'number',
        default: 0
      }
    }
  };

  const Cluster = props => {
    const {
      alignment,
      spacing,
      customSpacing
    } = props.attributes;
    const classes = ['vf-cluster'];

    if (spacing === 'medium') {
      classes.push('vf-cluster--600');
    } else if (spacing === 'large') {
      classes.push('vf-cluster--800');
    } else if (spacing !== 'custom') {
      classes.push('vf-cluster--400');
    }

    const styles = {};
    styles['--vf-cluster__item--flex'] = '25% 1 0';

    if (spacing === 'custom') {
      styles['--vf-cluster-margin'] = `${customSpacing}px`;
    }

    if (alignment === 'start') {
      styles['--vf-cluster-alignment'] = 'flex-start';
    } else if (alignment === 'end') {
      styles['--vf-cluster-alignment'] = 'flex-end';
    } else if (alignment === 'stretch') {
      styles['--vf-cluster-alignment'] = 'stretch';
    } else {
      styles['--vf-cluster-alignment'] = 'center';
    }

    return wp.element.createElement("div", {
      "data-ver": props.isEdit ? ver$3 : null,
      className: classes.join(' '),
      style: styles
    }, wp.element.createElement("div", {
      className: "vf-cluster__inner"
    }, props.children));
  };

  settings$b.save = props => {
    return wp.element.createElement(Cluster, props, wp.element.createElement(blockEditor.InnerBlocks.Content, null));
  };

  settings$b.edit = props => {
    if (ver$3 !== props.attributes.ver) {
      props.setAttributes({
        ver: ver$3
      });
    }

    const {
      clientId
    } = props;
    const {
      alignment,
      spacing
    } = props.attributes;
    const onSpacing = React.useCallback((name, value) => {
      props.setAttributes({
        [name]: value
      });

      if (value !== 'custom') {
        props.setAttributes({
          customSpacing: 0
        });
      }
    }, [clientId]); // Inspector controls

    const fields = [{
      name: 'alignment',
      label: i18n.__('Alignment'),
      control: 'select',
      options: [{
        label: i18n.__('Stretch'),
        value: 'stretch'
      }, {
        label: i18n.__('Start'),
        value: 'start'
      }, {
        label: i18n.__('Center'),
        value: 'center'
      }, {
        label: i18n.__('End'),
        value: 'end'
      }]
    }, {
      name: 'spacing',
      label: i18n.__('Spacing'),
      control: 'select',
      options: [{
        label: i18n.__('Small'),
        value: 'small'
      }, {
        label: i18n.__('Medium'),
        value: 'medium'
      }, {
        label: i18n.__('Large'),
        value: 'large'
      }, {
        label: i18n.__('Custom'),
        value: 'custom'
      }],
      onChange: onSpacing
    }];

    if (spacing === 'custom') {
      fields.push({
        name: 'customSpacing',
        label: i18n.__('Custom spacing'),
        control: 'range',
        allowReset: true,
        min: 0,
        max: 100
      });
    } // Return inner blocks and inspector controls


    return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
      title: i18n.__('Settings'),
      initialOpen: true
    }, wp.element.createElement(VFBlockFields, _extends({}, props, {
      fields: fields
    })))), wp.element.createElement(Cluster, _extends({}, props, {
      isEdit: true
    }), wp.element.createElement(blockEditor.InnerBlocks, {
      renderAppender: () => wp.element.createElement(blockEditor.InnerBlocks.ButtonBlockAppender, null)
    })));
  };

  /**
   * Precompiled Nunjucks template: vf-embed.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-embed"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  output += "<div\n  class=\"vf-embed";
  if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_16x9") == true) {
  output += " vf-embed--16x9";
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_4x3") == true) {
  output += " vf-embed--4x3";
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_custom") == true) {
  output += " vf-embed--custom-ratio";
  ;
  }
  output += "\"\n\n  style=\"";
  if(runtime.contextOrFrameLookup(context, frame, "vf_embed_max_width")) {
  output += "--vf-embed-max-width: ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_max_width"), env.opts.autoescape);
  output += ";\n";
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "vf_embed_variant_custom") == true) {
  output += "    --vf-embed-custom-ratio-x: ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_custom_ratio_X"), env.opts.autoescape);
  output += ";\n    --vf-embed-custom-ratio-y: ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embed_custom_ratio_Y"), env.opts.autoescape);
  output += ";";
  ;
  }
  output += "\"\n>";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "vf_embedded_content"), env.opts.autoescape);
  output += "</div>\n";
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Video
  */
  const RATIOS = {
    '2:1': {
      label: i18n.__('(2:1) Cinema'),
      ratio: '2:1',
      width: 640,
      height: 320
    },
    '16:9': {
      label: i18n.__('(16:9) Widescreen'),
      ratio: '16:9',
      width: 640,
      height: 360
    },
    '4:3': {
      label: i18n.__('(4:3) Standard'),
      ratio: '4:3',
      width: 640,
      height: 480
    },
    '1:1': {
      label: i18n.__('(1:1) Square'),
      ratio: '1:1',
      width: 640,
      height: 640
    }
  };

  const withRatioAttributes = Edit => {
    return props => {
      const transient = { ...(props.transient || {})
      };
      let {
        ratio,
        width,
        height,
        maxWidth
      } = props.attributes;

      if (ratio && ratio in RATIOS) {
        width = RATIOS[ratio].width;
        height = RATIOS[ratio].height;
        props.setAttributes({
          width,
          height
        });
      }

      if (isNaN(width)) {
        width = RATIOS['16:9'].width;
        props.setAttributes({
          width
        });
      }

      if (isNaN(height)) {
        height = RATIOS['16:9'].height;
        props.setAttributes({
          height
        });
      }

      transient.vf_embed_variant_custom = true;
      transient.vf_embed_custom_ratio_X = width;
      transient.vf_embed_custom_ratio_Y = height;

      if (maxWidth > 0) {
        transient.vf_embed_max_width = `${maxWidth}px`;
      } else {
        transient.vf_embed_max_width = '100%';
      }

      transient.vf_embedded_content = '';

      if (transient.src) {
        transient.vf_embedded_content = `<iframe width="${width}" height="${height}" src="${transient.src}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
      }

      return Edit({ ...props,
        transient
      });
    };
  };

  var vfEmbed = useVFCoreSettings({
    name: 'vf/embed',
    title: i18n.__('Embed'),
    attributes: {
      url: {
        type: 'string'
      },
      responsive: {
        type: 'integer',
        default: 1
      },
      ratio: {
        type: 'string',
        default: '16:9'
      },
      width: {
        type: 'integer'
      },
      height: {
        type: 'integer'
      },
      maxWidth: {
        type: 'string',
        default: 0
      }
    },
    fields: [{
      name: 'url',
      control: 'url',
      label: i18n.__('URL'),
      disableSuggestions: true
    }, {
      name: 'ratio',
      control: 'select',
      label: i18n.__('Preset Ratio'),
      inspector: true,
      options: Object.keys(RATIOS).map(key => ({
        label: RATIOS[key].label,
        value: key
      }))
    }, {
      name: 'width',
      control: 'number',
      label: i18n.__('Width'),
      help: i18n.__('Deselect preset to set a custom width.'),
      inspector: true,
      max: 1920,
      min: 320
    }, {
      name: 'height',
      control: 'number',
      label: i18n.__('Height'),
      help: i18n.__('Deselect preset to set a custom height.'),
      inspector: true,
      max: 1080,
      min: 180
    }, {
      name: 'maxWidth',
      control: 'number',
      label: i18n.__('Maximum Width'),
      help: i18n.__('Restrict embed resize to this width.'),
      inspector: true,
      max: 1920,
      min: 0
    }],
    withHOC: [[withRatioAttributes], [withTransientAttributeMap, [{
      from: 'url',
      to: 'src'
    }]]]
  });

  const BlockWrapper = ({
    blockProps,
    forSave,
    children
  }) => {
    if (blockEditor.__experimentalBlock && blockEditor.__experimentalBlock.div) {
      if (forSave) {
        return wp.element.createElement("div", blockProps, children);
      }

      return wp.element.createElement(blockEditor.__experimentalBlock.div, blockProps, children);
    }

    if (blockEditor.useBlockProps) {
      const allProps = forSave ? blockEditor.useBlockProps.save(blockProps) : blockEditor.useBlockProps(blockProps);
      return wp.element.createElement("div", allProps, children);
    }
  };

  /**
  Block Name: Grid Column
  */
  const defaults$5 = useVFDefaults();
  const settings$a = { ...defaults$5,
    name: 'vf/grid-column',
    title: i18n.__('Grid Column'),
    category: 'vf/core',
    description: i18n.__('Visual Framework (core)'),
    parent: ['vf/grid', 'vf/embl-grid'],
    supports: { ...defaults$5.supports,
      inserter: false,
      lightBlockWrapper: true
    },
    attributes: { ...defaults$5.attributes,
      span: {
        type: 'integer',
        default: 1
      }
    }
  };

  settings$a.save = props => {
    const {
      span
    } = props.attributes;
    const classes = [];

    if (Number.isInteger(span) && span > 1) {
      classes.push(`vf-grid__col--span-${span}`);
    }

    const blockProps = {
      className: classes.join(' ')
    };
    return wp.element.createElement(BlockWrapper, {
      blockProps: blockProps,
      forSave: true
    }, wp.element.createElement(blockEditor.InnerBlocks.Content, null));
  };

  settings$a.edit = props => {
    const {
      clientId
    } = props;
    const {
      span
    } = props.attributes;
    const {
      updateBlockAttributes
    } = data$1.useDispatch('core/block-editor');
    const {
      hasChildBlocks,
      hasSpanSupport,
      rootClientId
    } = data$1.useSelect(select => {
      const {
        getBlockName,
        getBlockOrder,
        getBlockRootClientId
      } = select('core/block-editor');
      const rootClientId = getBlockRootClientId(clientId);
      const hasChildBlocks = getBlockOrder(clientId).length > 0;
      const hasSpanSupport = getBlockName(rootClientId) === 'vf/grid';
      return {
        rootClientId,
        hasChildBlocks,
        hasSpanSupport
      };
    }, [clientId]);
    React.useEffect(() => {
      if (!hasSpanSupport && span !== 1) {
        props.setAttributes({
          span: 1
        });
      }
    }, [clientId]);
    const onSpanChange = React.useCallback(value => {
      if (span !== value) {
        props.setAttributes({
          span: value
        });
        updateBlockAttributes(rootClientId, {
          dirty: Date.now()
        });
      }
    }, [span, clientId, rootClientId]);
    const classes = [];

    if (hasSpanSupport) {
      if (Number.isInteger(span) && span > 1) {
        classes.push(`vf-grid__col--span-${span}`);
      }
    }

    const blockProps = {
      className: classes.join(' ')
    };
    return wp.element.createElement(React__default['default'].Fragment, null, hasSpanSupport && wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
      title: i18n.__('Advanced Settings'),
      initialOpen: true
    }, wp.element.createElement(components.RangeControl, {
      label: i18n.__('Column span'),
      help: i18n.__('Columns may be merged to fit.'),
      value: Number.isInteger(span) ? span : 1,
      onChange: onSpanChange,
      allowReset: true,
      step: 1,
      min: 1,
      max: 6
    }))), wp.element.createElement(BlockWrapper, {
      blockProps: blockProps
    }, wp.element.createElement(blockEditor.InnerBlocks, {
      templateLock: false,
      renderAppender: hasChildBlocks ? undefined : () => wp.element.createElement(blockEditor.InnerBlocks.ButtonBlockAppender, null)
    })));
  };

  /**
   * Block transforms for: `vf/grid`, `vf/embl-grid`, and `core/columns`
   */
  // New columns are appended to match minimum
  // End columns are merged to match maximum

  const fromColumns = (fromBlock, toBlock, min, max) => {
    return {
      type: 'block',
      blocks: [fromBlock],
      // Match function (ignore initial placeholder state)
      isMatch: attributes => attributes.placeholder !== 1,
      // Transform function
      transform: (attributes, innerBlocks) => {
        // Map column props
        let innerProps = innerBlocks.map(block => ({
          attributes: { ...block.attributes
          },
          innerBlocks: [...block.innerBlocks]
        })); // Fill empty props to match min number of columns

        while (innerProps.length < min) {
          innerProps.push({});
        } // Merge end props to match max number of columns


        while (innerProps.length > max) {
          const mergeProps = innerProps.pop();
          innerProps[innerProps.length - 1].innerBlocks.push(...mergeProps.innerBlocks);
        } // Return new grid block with inner columns


        return blocks.createBlock(toBlock, {
          columns: innerProps.length
        }, innerProps.map(props => blocks.createBlock('vf/grid-column', props.attributes || {}, props.innerBlocks || [])));
      }
    };
  };

  const defaults$4 = useVFDefaults();
  const ver$2 = '1.1.0';
  const MIN_COLUMNS$1 = 2;
  const MAX_COLUMNS$1 = 4;
  const settings$9 = { ...defaults$4,
    name: 'vf/embl-grid',
    title: i18n.__('EMBL Grid'),
    category: 'vf/core',
    description: i18n.__('Visual Framework (core)'),
    supports: { ...defaults$4.supports,
      lightBlockWrapper: true
    },
    attributes: { ...defaults$4.attributes,
      placeholder: {
        type: 'integer',
        default: 0
      },
      columns: {
        type: 'integer',
        default: 0
      },
      sidebar: {
        type: 'integer',
        default: 0
      },
      centered: {
        type: 'integer',
        default: 0
      }
    }
  };

  settings$9.save = props => {
    const {
      placeholder,
      sidebar,
      centered
    } = props.attributes;

    if (placeholder === 1) {
      return null;
    }

    let className = 'embl-grid';

    if (!!sidebar) {
      className = `${className} embl-grid--has-sidebar`;
    }

    if (!!centered) {
      className = `${className} embl-grid--has-centered-content`;
    }

    const blockProps = blockEditor.useBlockProps.save({
      className
    });
    return wp.element.createElement("div", blockProps, wp.element.createElement(blockEditor.InnerBlocks.Content, null));
  };

  settings$9.edit = props => {
    if (ver$2 !== props.attributes.ver) {
      props.setAttributes({
        ver: ver$2
      });
    }

    const {
      clientId
    } = props;
    const {
      columns,
      centered,
      sidebar,
      placeholder
    } = props.attributes; // Turn on setup placeholder if no columns are defined

    React.useEffect(() => {
      if (columns === 0) {
        props.setAttributes({
          placeholder: 1
        });
      }
    }, [clientId]);
    const {
      replaceInnerBlocks
    } = data$1.useDispatch('core/block-editor');
    const {
      setColumns
    } = data$1.useSelect(select => {
      const {
        getBlocks
      } = select('core/block-editor'); // Remove columns by merging their inner blocks

      const removeColumns = newColumns => {
        const innerColumns = getBlocks(clientId);
        const mergeBlocks = [];

        for (let i = newColumns - 1; i < innerColumns.length; i++) {
          mergeBlocks.push(...innerColumns[i].innerBlocks);
        }

        replaceInnerBlocks(innerColumns[newColumns - 1].clientId, mergeBlocks, false);
        replaceInnerBlocks(clientId, getBlocks(clientId).slice(0, newColumns), false);
      }; // Append new columns


      const addColumns = newColumns => {
        const innerColumns = getBlocks(clientId);

        while (innerColumns.length < newColumns) {
          innerColumns.push(blocks.createBlock('vf/grid-column', {}, []));
        }

        replaceInnerBlocks(clientId, innerColumns, false);
      };

      const setColumns = newColumns => {
        const innerColumns = getBlocks(clientId);

        if (newColumns < innerColumns.length) {
          removeColumns(newColumns);
        }

        if (newColumns > innerColumns.length) {
          addColumns(newColumns);
        }

        props.setAttributes({
          columns: newColumns,
          placeholder: 0
        });

        if (newColumns !== 3) {
          props.setAttributes({
            sidebar: 0,
            centered: 0
          });
        }
      };

      return {
        setColumns
      };
    }, [clientId]); // Toggle attribute `onChange` callback

    const setToggle = React.useCallback((name, value) => {
      value = value ? 1 : 0;
      props.setAttributes({
        sidebar: 0,
        centered: 0,
        [name]: value
      });

      if (value) {
        setColumns(3);
      }
    }); // Setup placeholder fields

    const fields = [{
      control: 'columns',
      min: MIN_COLUMNS$1,
      max: MAX_COLUMNS$1,
      value: columns,
      onChange: setColumns
    }, {
      label: i18n.__('With Sidebar'),
      control: 'toggle',
      name: 'sidebar',
      onChange: setToggle
    }, {
      label: i18n.__('Centered Content'),
      control: 'toggle',
      name: 'centered',
      onChange: setToggle
    }]; // Return setup placeholder

    if (placeholder === 1) {
      const blockProps = blockEditor.useBlockProps({
        className: 'vf-block vf-block--placeholder'
      });
      return wp.element.createElement("div", blockProps, wp.element.createElement(components.Placeholder, {
        label: i18n.__('EMBL Grid'),
        icon: 'admin-generic'
      }, wp.element.createElement(VFBlockFields, _extends({}, props, {
        fields: fields
      }))));
    } // Amend fields for inspector


    fields[0].help = i18n.__('Content may be reorganised when columns are reduced.');
    fields[1].help = i18n.__('3 column only.');
    fields[2].help = fields[1].help;
    let className = 'embl-grid';

    if (!!sidebar) {
      className = `${className} embl-grid--has-sidebar`;
    }

    if (!!centered) {
      className = `${className} embl-grid--has-centered-content`;
    }

    const blockProps = blockEditor.useBlockProps({
      className
    }); // Return inner blocks and inspector controls

    return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
      title: i18n.__('Settings'),
      initialOpen: true
    }, wp.element.createElement(VFBlockFields, _extends({}, props, {
      fields: fields
    })))), wp.element.createElement("div", _extends({}, blockProps, {
      "data-ver": ver$2,
      "data-embl": true,
      "data-sidebar": sidebar,
      "data-centered": centered
    }), wp.element.createElement(blockEditor.InnerBlocks, {
      allowedBlocks: ['vf/grid-column'],
      template: Array(columns).fill(['vf/grid-column']),
      templateLock: "all"
    })));
  }; // Block transforms


  settings$9.transforms = {
    from: [fromColumns('core/columns', 'vf/embl-grid', MIN_COLUMNS$1, MAX_COLUMNS$1), fromColumns('vf/grid', 'vf/embl-grid', MIN_COLUMNS$1, MAX_COLUMNS$1)]
  };

  /**
  Block Name: Grid
  */
  const defaults$3 = useVFDefaults();
  const MIN_COLUMNS = 1;
  const MAX_COLUMNS = 6;

  const GridControl = props => {
    return wp.element.createElement(ColumnsControl, props);
  };

  const settings$8 = { ...defaults$3,
    name: 'vf/grid',
    title: i18n.__('VF Grid'),
    category: 'vf/core',
    description: i18n.__('Visual Framework (core)'),
    supports: { ...defaults$3.supports,
      lightBlockWrapper: true
    },
    attributes: { ...defaults$3.attributes,
      placeholder: {
        type: 'integer',
        default: 0
      },
      columns: {
        type: 'integer',
        default: 0
      },
      dirty: {
        type: 'integer',
        default: 0
      }
    }
  };

  settings$8.save = props => {
    const {
      columns,
      placeholder
    } = props.attributes;

    if (placeholder === 1) {
      return null;
    }

    const className = `vf-grid | vf-grid__col-${columns}`;
    const blockProps = blockEditor.useBlockProps.save({
      className
    });
    return wp.element.createElement("div", blockProps, wp.element.createElement(blockEditor.InnerBlocks.Content, null));
  };

  settings$8.edit = props => {
    const {
      clientId
    } = props;
    const {
      dirty,
      columns,
      placeholder
    } = props.attributes; // Turn on setup placeholder if no columns are defined

    React.useEffect(() => {
      if (columns === 0) {
        props.setAttributes({
          placeholder: 1
        });
      }
    }, [clientId]);
    const {
      replaceInnerBlocks
    } = data$1.useDispatch('core/block-editor');
    const {
      setColumns,
      updateColumns
    } = data$1.useSelect(select => {
      const {
        getBlocks,
        getBlockAttributes
      } = select('core/block-editor'); // Return total number of columns accounting for spans

      const countSpans = blocks => {
        let count = 0;
        blocks.forEach(block => {
          const {
            span
          } = block.attributes;

          if (Number.isInteger(span) && span > 0) {
            count += span;
          } else {
            count++;
          }
        });
        return count;
      }; // Append new columns


      const addColumns = maxSpans => {
        const innerColumns = getBlocks(clientId);

        while (countSpans(innerColumns) < maxSpans) {
          innerColumns.push(blocks.createBlock('vf/grid-column', {}, []));
        }

        replaceInnerBlocks(clientId, innerColumns, false);
      }; // Remove columns by merging their inner blocks


      const removeColumns = maxSpans => {
        let innerColumns = getBlocks(clientId);
        let mergeBlocks = [];

        while (innerColumns.length > 1 && countSpans(innerColumns) > maxSpans) {
          mergeBlocks = mergeBlocks.concat(innerColumns.pop().innerBlocks);
        }

        replaceInnerBlocks(innerColumns[innerColumns.length - 1].clientId, mergeBlocks.concat(innerColumns[innerColumns.length - 1].innerBlocks), false);
        replaceInnerBlocks(clientId, getBlocks(clientId).slice(0, innerColumns.length), false);
      };

      const setColumns = newColumns => {
        props.setAttributes({
          columns: newColumns,
          placeholder: 0
        });
        const innerColumns = getBlocks(clientId);
        const count = countSpans(innerColumns);

        if (newColumns < count) {
          removeColumns(newColumns);
        }

        if (newColumns > count) {
          addColumns(newColumns);
        }
      };

      const updateColumns = () => {
        const {
          columns
        } = getBlockAttributes(clientId);
        setColumns(columns);
        props.setAttributes({
          dirty: 0
        });
      };

      return {
        setColumns,
        updateColumns
      };
    }, [clientId]);
    React.useEffect(() => {
      if (dirty > 0) {
        updateColumns();
      }
    }, [dirty]); // Return setup placeholder

    if (placeholder === 1) {
      const blockProps = blockEditor.useBlockProps({
        className: 'vf-block vf-block--placeholder'
      });
      return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement("div", blockProps, wp.element.createElement(components.Placeholder, {
        label: i18n.__('VF Grid'),
        icon: 'admin-generic'
      }, wp.element.createElement(GridControl, {
        value: columns,
        min: MIN_COLUMNS,
        max: MAX_COLUMNS,
        onChange: React.useCallback(value => setColumns(value), [])
      }))));
    }

    const className = `vf-grid | vf-grid__col-${columns}`;
    const styles = {
      ['--block-columns']: columns
    };
    const blockProps = blockEditor.useBlockProps({
      className,
      style: styles
    }); // Return inner blocks and inspector controls

    return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
      title: i18n.__('Advanced Settings'),
      initialOpen: true
    }, wp.element.createElement(GridControl, {
      value: columns,
      min: MIN_COLUMNS,
      max: MAX_COLUMNS,
      onChange: React.useCallback(value => setColumns(value), []),
      help: i18n.__('Content may be reorganised when columns are reduced.')
    }))), wp.element.createElement("div", blockProps, wp.element.createElement(blockEditor.InnerBlocks, {
      allowedBlocks: ['vf/grid-column'],
      templateLock: "all"
    })));
  }; // Block transforms


  settings$8.transforms = {
    from: [fromColumns('core/columns', 'vf/grid', MIN_COLUMNS, MAX_COLUMNS), fromColumns('vf/embl-grid', 'vf/grid', MIN_COLUMNS, MAX_COLUMNS)]
  };

  const defaults$2 = useVFDefaults();
  const ver$1 = '1.0.0';
  const settings$7 = { ...defaults$2,
    name: 'vf/tabs-section',
    title: i18n.__('VF Tab Section'),
    category: 'vf/core',
    description: i18n.__('Visual Framework (core)'),
    parent: ['vf/tabs'],
    supports: { ...defaults$2.supports,
      inserter: false
    },
    attributes: { ...defaults$2.attributes,
      id: {
        type: 'string',
        default: ''
      },
      label: {
        type: 'string',
        default: ''
      },
      unlabelled: {
        type: 'integer',
        default: 0
      }
    }
  };

  settings$7.save = props => {
    const {
      id,
      label,
      unlabelled
    } = props.attributes;
    const attr = {
      className: `vf-tabs__section`
    };

    if (id !== '') {
      attr.id = `vf-tabs__section-${id}`;
    }

    const heading = {};

    if (unlabelled === 1) {
      heading.className = 'vf-u-sr-only';
    }

    return wp.element.createElement("section", attr, wp.element.createElement("h2", heading, label), wp.element.createElement(blockEditor.InnerBlocks.Content, null));
  };

  settings$7.edit = props => {
    if (ver$1 !== props.attributes.ver) {
      props.setAttributes({
        ver: ver$1
      });
    }

    const {
      clientId
    } = props;
    let {
      id,
      label,
      unlabelled
    } = props.attributes;
    const {
      removeBlock,
      updateBlockAttributes
    } = data$1.useDispatch('core/block-editor');
    const {
      tabOrder,
      updateTabs
    } = data$1.useSelect(select => {
      const {
        getBlockOrder,
        getBlockRootClientId
      } = select('core/block-editor');
      const rootClientId = getBlockRootClientId(clientId);
      const parentBlockOrder = getBlockOrder(rootClientId);
      return {
        tabOrder: parentBlockOrder.indexOf(clientId) + 1,
        updateTabs: () => {
          updateBlockAttributes(rootClientId, {
            dirty: Date.now()
          });
        }
      };
    }, [clientId]); // Default to the `clientId` for the ID attribute

    React.useEffect(() => {
      if (id === '') {
        props.setAttributes({
          id: clientId
        });
      }
    }, [id]); // Default to "Tab [N]" for the tab heading

    React.useEffect(() => {
      if (label === '') {
        props.setAttributes({
          label: i18n.__(`Tab ${tabOrder}`)
        });
      }
    }, [label]); // Flag the parent tabs block as "dirty" if any attributes change

    React.useEffect(() => {
      updateTabs();
    }, [id, label, tabOrder]); // Callback for inspector changes to update attributes
    // Flags the parent tabs block as "dirty"

    const onChange = React.useCallback((name, value) => {
      if (name === 'id') {
        value = value.replace(/[\s\./]+/g, '-').replace(/[^\w-]+/g, '').toLowerCase().trim();
      }

      props.setAttributes({
        [name]: value
      });
    }, [clientId]); // Inspector controls

    const fields = [{
      name: 'label',
      control: 'text',
      label: i18n.__('Tab Label'),
      onChange
    }, {
      name: 'unlabelled',
      control: 'toggle',
      label: i18n.__('Hide Heading'),
      onChange
    }, {
      name: 'id',
      control: 'text',
      label: i18n.__('Anchor ID'),
      onChange
    }, {
      control: 'button',
      label: i18n.__('Delete Tab'),
      isSecondary: true,
      isDestructive: true,
      onClick: () => {
        removeBlock(clientId, false);
      }
    }];
    return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
      title: i18n.__('Settings'),
      initialOpen: true
    }, wp.element.createElement(VFBlockFields, _extends({}, props, {
      fields: fields
    })))), wp.element.createElement("div", {
      className: "vf-tabs__section"
    }, unlabelled ? false : wp.element.createElement("h2", null, label), wp.element.createElement(blockEditor.InnerBlocks, null)));
  };

  const defaults$1 = useVFDefaults();
  const ver = '1.0.0';
  const settings$6 = { ...defaults$1,
    name: 'vf/tabs',
    title: i18n.__('VF Tabs'),
    category: 'vf/core',
    description: i18n.__('Visual Framework (core)'),
    attributes: { ...defaults$1.attributes,
      dirty: {
        type: 'integer',
        default: 0
      },
      tabs: {
        type: 'array',
        default: []
      }
    }
  };

  settings$6.save = props => {
    const blockProps = blockEditor.useBlockProps.save({
      className: 'vf-tabs'
    });
    return wp.element.createElement("div", blockProps, wp.element.createElement("ul", {
      className: "vf-tabs__list",
      "data-vf-js-tabs": true
    }, props.attributes.tabs.map((tab, i) => {
      return wp.element.createElement("li", {
        key: i + tab.id,
        className: "vf-tabs__item"
      }, wp.element.createElement("a", {
        className: "vf-tabs__link",
        href: `#vf-tabs__section-${tab.id}`
      }, tab.label));
    })), wp.element.createElement("div", {
      className: "vf-tabs-content",
      "data-vf-js-tabs-content": true
    }, wp.element.createElement(blockEditor.InnerBlocks.Content, null)));
  };

  settings$6.edit = props => {
    if (ver !== props.attributes.ver) {
      props.setAttributes({
        ver
      });
    }

    const {
      clientId
    } = props;
    const {
      dirty,
      tabs
    } = props.attributes;
    const {
      replaceInnerBlocks,
      selectBlock
    } = data$1.useDispatch('core/block-editor');
    const {
      appendTab,
      getTabs,
      getTabsOrder,
      updateTabs
    } = data$1.useSelect(select => {
      const {
        getBlockOrder,
        getBlocks
      } = select('core/block-editor');

      const getTabs = () => {
        return getBlocks(clientId);
      };

      const getTabsOrder = () => {
        return getBlockOrder(clientId);
      };

      const appendTab = () => {
        const innerTabs = getTabs();
        innerTabs.push(blocks.createBlock('vf/tabs-section', {}, []));
        replaceInnerBlocks(clientId, innerTabs, false);
        selectBlock(innerTabs.slice(-1)[0].clientId);
      };

      const updateTabs = () => {
        const innerTabs = getTabs();
        const newTabs = [];
        innerTabs.forEach(block => {
          const {
            id,
            label
          } = block.attributes;
          newTabs.push({
            id,
            label
          });
        });
        props.setAttributes({
          dirty: 0,
          tabs: newTabs
        });
      };

      return {
        appendTab,
        getTabs,
        getTabsOrder,
        updateTabs
      };
    }, [clientId]);
    const tabsOrder = getTabsOrder(); // Callback to switch tabs using the tab list interface

    const selectTab = React.useCallback(index => {
      if (index < tabsOrder.length) {
        selectBlock(tabsOrder[index]);
      }
    }, [tabsOrder]); // Flag as "dirty" if the tabs and inner blocks do not match

    React.useEffect(() => {
      if (dirty === 0) {
        if (Object.keys(tabs).length !== getTabs().length) {
          props.setAttributes({
            dirty: Date.now()
          });
        }
      }
    }, [getTabs().length]); // Update attributes if the block is flagged as "dirty"

    React.useEffect(() => {
      if (dirty > 0) {
        updateTabs();
      }
    }, [dirty]); // Inspector controls

    const fields = [{
      control: 'button',
      label: i18n.__('Add Tab'),
      isSecondary: true,
      icon: 'insert',
      onClick: () => {
        appendTab();
      }
    }]; // Return inner blocks and inspector controls

    const blockProps = blockEditor.useBlockProps({
      className: 'vf-tabs'
    });
    return wp.element.createElement(React__default['default'].Fragment, null, wp.element.createElement(blockEditor.InspectorControls, null, wp.element.createElement(components.PanelBody, {
      title: i18n.__('Settings'),
      initialOpen: true
    }, wp.element.createElement(VFBlockFields, {
      fields: fields
    }))), wp.element.createElement("div", _extends({}, blockProps, {
      "data-ver": ver
    }), wp.element.createElement("ul", {
      className: "vf-tabs__list"
    }, tabs.map((tab, i) => {
      return wp.element.createElement("li", {
        key: i + tab.id,
        className: "vf-tabs__item"
      }, wp.element.createElement("a", {
        className: "vf-tabs__link",
        onClick: () => selectTab(i)
      }, tab.label));
    }), wp.element.createElement("li", {
      className: "vf-tabs__item"
    }, wp.element.createElement(components.Button, _extends({}, fields[0], {
      isTertiary: true,
      isSecondary: false
    }), wp.element.createElement("span", null, fields[0].label)))), wp.element.createElement(blockEditor.InnerBlocks, {
      allowedBlocks: ['vf/tabs-section'],
      template: Array(1).fill(['vf/tabs-section'])
    })));
  };

  /**
  Block Name: Activity List Item
  */
  const settings$5 = useVFCoreSettings({
    name: 'vf/activity-item',
    title: i18n.__('Activity Item'),
    parent: ['vf/activity-list'],
    isRenderable: false,
    attributes: {
      text: {
        type: 'string',
        default: '<strong>Author</strong> published <a href="#">\'Article Title\'</a> on <a href="#">Source</a>.'
      }
    },
    fields: [{
      name: 'text',
      control: 'rich',
      label: '',
      tag: 'p',
      placeholder: i18n.__('Type activity…')
    }]
  });
  var vfActivityItem = { ...settings$5,
    supports: { ...settings$5.supports,
      inserter: false
    }
  };

  /**
   * Block transforms for: `vf/divider`
   */
  const fromCore$3 = () => {
    return {
      type: 'block',
      blocks: ['core/list'],
      transform: attributes => {
        const innerBlocks = []; // Only transform browser-side via DOM to parse HTML in `value` attribute

        if (typeof window !== 'object') {
          return blocks.createBlock('vf/activity-list');
        }

        const list = window.document.createElement('ul');
        list.innerHTML = attributes.values;
        list.children.forEach(el => {
          if (el.nodeName.toLowerCase() === 'li') {
            innerBlocks.push(blocks.createBlock('vf/activity-item', {
              text: el.innerHTML
            }));
          }
        });
        return blocks.createBlock('vf/activity-list', {}, innerBlocks);
      }
    };
  };

  /**
   * Precompiled Nunjucks template: vf-activity-list.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-activity-list"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  output += "<div class=\"vf-activity\">\n\n  <p class=\"vf-activity__date\">";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "date"), env.opts.autoescape);
  output += "</p>\n\n  <ul class=\"vf-activity__list | vf-list\">\n\n";
  frame = frame.push();
  var t_3 = runtime.contextOrFrameLookup(context, frame, "list");
  if(t_3) {t_3 = runtime.fromIterator(t_3);
  var t_2 = t_3.length;
  for(var t_1=0; t_1 < t_3.length; t_1++) {
  var t_4 = t_3[t_1];
  frame.set("item", t_4);
  frame.set("loop.index", t_1 + 1);
  frame.set("loop.index0", t_1);
  frame.set("loop.revindex", t_2 - t_1);
  frame.set("loop.revindex0", t_2 - t_1 - 1);
  frame.set("loop.first", t_1 === 0);
  frame.set("loop.last", t_1 === t_2 - 1);
  frame.set("loop.length", t_2);
  output += "\n    <li class=\"vf-activity__item\">\n      ";
  output += runtime.suppressValue(t_4, env.opts.autoescape);
  output += "\n    </li>\n\n";
  ;
  }
  }
  frame = frame.pop();
  output += "\n  </ul>\n</div>\n";
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Activity List
  */

  const withActivityItems = Edit => {
    return props => {
      const transient = { ...(props.transient || {})
      };
      transient.list = [];

      if (Array.isArray(transient.innerBlocks)) {
        transient.innerBlocks.forEach(block => {
          if (block.name === 'vf/activity-item') {
            transient.list.push(block.attributes.text);
          }
        });
      }

      return Edit({ ...props,
        transient
      });
    };
  };

  const settings$4 = useVFCoreSettings({
    name: 'vf/activity-list',
    title: i18n.__('Activity List'),
    attributes: {
      heading: {
        type: 'string',
        default: i18n.__('Activity List')
      }
    },
    fields: [{
      name: 'heading',
      control: 'text',
      label: i18n.__('Heading')
    }],
    allowedBlocks: ['vf/activity-item'],
    transforms: {
      from: [fromCore$3()]
    },
    withHOC: [[withTransientAttributeMap, [{
      from: 'heading',
      to: 'date'
    }]], [withActivityItems], [withTransientInnerBlocks]]
  });
  var vfActivityList = { ...settings$4,
    supports: { ...settings$4.supports,
      inserter: false
    }
  };

  /**
   * Precompiled Nunjucks template: vf-badge.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-badge"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  if(runtime.contextOrFrameLookup(context, frame, "context")) {
  output += "\n";
  var t_1;
  t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"badge_href");
  frame.set("badge_href", t_1, true);
  if(frame.topLevel) {
  context.setVariable("badge_href", t_1);
  }
  if(frame.topLevel) {
  context.addExport("badge_href", t_1);
  }
  var t_2;
  t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"theme");
  frame.set("theme", t_2, true);
  if(frame.topLevel) {
  context.setVariable("theme", t_2);
  }
  if(frame.topLevel) {
  context.addExport("theme", t_2);
  }
  var t_3;
  t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"text");
  frame.set("text", t_3, true);
  if(frame.topLevel) {
  context.setVariable("text", t_3);
  }
  if(frame.topLevel) {
  context.addExport("text", t_3);
  }
  var t_4;
  t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"style");
  frame.set("style", t_4, true);
  if(frame.topLevel) {
  context.setVariable("style", t_4);
  }
  if(frame.topLevel) {
  context.addExport("style", t_4);
  }
  var t_5;
  t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"html");
  frame.set("html", t_5, true);
  if(frame.topLevel) {
  context.setVariable("html", t_5);
  }
  if(frame.topLevel) {
  context.addExport("html", t_5);
  }
  output += "\n";
  ;
  }
  output += runtime.suppressValue(env.getExtension("spaceless")["run"](context,function(cb) {
  if(!cb) { cb = function(err) { if(err) { throw err; }};}
  var t_6 = "";t_6 += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "badge_href")) {
  var t_7;
  t_7 = "a";
  frame.set("tags", t_7, true);
  if(frame.topLevel) {
  context.setVariable("tags", t_7);
  }
  if(frame.topLevel) {
  context.addExport("tags", t_7);
  }
  ;
  }
  else {
  var t_8;
  t_8 = "span";
  frame.set("tags", t_8, true);
  if(frame.topLevel) {
  context.setVariable("tags", t_8);
  }
  if(frame.topLevel) {
  context.addExport("tags", t_8);
  }
  ;
  }
  t_6 += "\n";
  t_6 += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "theme")) {
  var t_9;
  t_9 = runtime.contextOrFrameLookup(context, frame, "theme");
  frame.set("theme_class", t_9, true);
  if(frame.topLevel) {
  context.setVariable("theme_class", t_9);
  }
  if(frame.topLevel) {
  context.addExport("theme_class", t_9);
  }
  ;
  }
  t_6 += "\n\n";
  t_6 += "\n<";
  t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
  t_6 += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "tags") == "a") {
  t_6 += " href=\"";
  t_6 += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "badge_href")?runtime.contextOrFrameLookup(context, frame, "badge_href"):"#"), env.opts.autoescape);
  t_6 += "\"";
  ;
  }
  t_6 += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "id")) {
  t_6 += " id=\"";
  t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
  t_6 += "\"";
  ;
  }
  t_6 += "\nclass=\"vf-badge";
  if(runtime.contextOrFrameLookup(context, frame, "theme_class")) {
  t_6 += " vf-badge--";
  t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "theme_class"), env.opts.autoescape);
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "style")) {
  frame = frame.push();
  var t_12 = runtime.contextOrFrameLookup(context, frame, "style");
  if(t_12) {t_12 = runtime.fromIterator(t_12);
  var t_11 = t_12.length;
  for(var t_10=0; t_10 < t_12.length; t_10++) {
  var t_13 = t_12[t_10];
  frame.set("styles", t_13);
  frame.set("loop.index", t_10 + 1);
  frame.set("loop.index0", t_10);
  frame.set("loop.revindex", t_11 - t_10);
  frame.set("loop.revindex0", t_11 - t_10 - 1);
  frame.set("loop.first", t_10 === 0);
  frame.set("loop.last", t_10 === t_11 - 1);
  frame.set("loop.length", t_11);
  t_6 += " vf-badge--";
  t_6 += runtime.suppressValue(t_13, env.opts.autoescape);
  ;
  }
  }
  frame = frame.pop();
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
  t_6 += " | ";
  t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
  ;
  }
  t_6 += "\">";
  t_6 += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "html")?env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "html")):runtime.contextOrFrameLookup(context, frame, "text")), env.opts.autoescape);
  t_6 += "</";
  t_6 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
  t_6 += ">\n";
  cb(null, t_6);
  ;
  return t_6;
  }
  ,null), true && env.opts.autoescape);
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Badge
  */

  const withBEMModifiers$1 = Edit => {
    return props => {
      const transient = { ...(props.transient || {})
      };
      transient.style = [];

      if (props.attributes.outline) {
        transient.style.push('outline');
      }

      if (props.attributes.rounded) {
        transient.style.push('rounded');
      }

      if (props.attributes.pill) {
        transient.style.push('pill');
      }

      return Edit({ ...props,
        transient
      });
    };
  };

  const settings$3 = useVFCoreSettings({
    name: 'vf/badge',
    title: i18n.__('Badge'),
    attributes: {
      text: {
        type: 'string',
        default: i18n.__('Badge')
      },
      outline: {
        type: 'integer'
      },
      rounded: {
        type: 'integer'
      },
      pill: {
        type: 'integer'
      }
    },
    fields: [{
      name: 'text',
      control: 'text',
      label: i18n.__('Text')
    }, {
      name: 'outline',
      control: 'true_false',
      label: i18n.__('Outline'),
      inspector: true
    }, {
      name: 'rounded',
      control: 'true_false',
      label: i18n.__('Rounded'),
      inspector: true
    }, {
      name: 'pill',
      control: 'true_false',
      label: i18n.__('Pill'),
      inspector: true
    }],
    styles: [{
      name: 'default',
      label: i18n.__('Default'),
      isDefault: true
    }, {
      name: 'primary',
      label: i18n.__('Primary')
    }, {
      name: 'secondary',
      label: i18n.__('Secondary')
    }, {
      name: 'tertiary',
      label: i18n.__('Tertiary')
    }],
    withHOC: [[withBEMModifiers$1], [withTransientStyle, {
      key: 'theme_class'
    }]]
  });
  var vfBadge = { ...settings$3,
    supports: { ...settings$3.supports,
      inserter: false
    }
  };

  /**
   * Precompiled Nunjucks template: vf-box.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-box"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  if(runtime.contextOrFrameLookup(context, frame, "context")) {
  var t_1;
  t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"deprecated_text");
  frame.set("deprecated_text", t_1, true);
  if(frame.topLevel) {
  context.setVariable("deprecated_text", t_1);
  }
  if(frame.topLevel) {
  context.addExport("deprecated_text", t_1);
  }
  var t_2;
  t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_href");
  frame.set("box_href", t_2, true);
  if(frame.topLevel) {
  context.setVariable("box_href", t_2);
  }
  if(frame.topLevel) {
  context.addExport("box_href", t_2);
  }
  var t_3;
  t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
  frame.set("id", t_3, true);
  if(frame.topLevel) {
  context.setVariable("id", t_3);
  }
  if(frame.topLevel) {
  context.addExport("id", t_3);
  }
  var t_4;
  t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"variant");
  frame.set("variant", t_4, true);
  if(frame.topLevel) {
  context.setVariable("variant", t_4);
  }
  if(frame.topLevel) {
  context.addExport("variant", t_4);
  }
  var t_5;
  t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"theme");
  frame.set("theme", t_5, true);
  if(frame.topLevel) {
  context.setVariable("theme", t_5);
  }
  if(frame.topLevel) {
  context.addExport("theme", t_5);
  }
  var t_6;
  t_6 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_modifier");
  frame.set("box_modifier", t_6, true);
  if(frame.topLevel) {
  context.setVariable("box_modifier", t_6);
  }
  if(frame.topLevel) {
  context.addExport("box_modifier", t_6);
  }
  var t_7;
  t_7 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_spacing");
  frame.set("box_spacing", t_7, true);
  if(frame.topLevel) {
  context.setVariable("box_spacing", t_7);
  }
  if(frame.topLevel) {
  context.addExport("box_spacing", t_7);
  }
  var t_8;
  t_8 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
  frame.set("override_class", t_8, true);
  if(frame.topLevel) {
  context.setVariable("override_class", t_8);
  }
  if(frame.topLevel) {
  context.addExport("override_class", t_8);
  }
  var t_9;
  t_9 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_heading");
  frame.set("box_heading", t_9, true);
  if(frame.topLevel) {
  context.setVariable("box_heading", t_9);
  }
  if(frame.topLevel) {
  context.addExport("box_heading", t_9);
  }
  var t_10;
  t_10 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"box_text");
  frame.set("box_text", t_10, true);
  if(frame.topLevel) {
  context.setVariable("box_text", t_10);
  }
  if(frame.topLevel) {
  context.addExport("box_text", t_10);
  }
  ;
  }
  output += "\n<div";
  if(runtime.contextOrFrameLookup(context, frame, "id")) {
  output += " id=\"";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
  output += "\"";
  ;
  }
  output += "\n  class=\"vf-box";
  if(runtime.contextOrFrameLookup(context, frame, "box_href")) {
  output += " vf-box--is-link";
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "box_spacing")) {
  output += " vf-box--";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_spacing"), env.opts.autoescape);
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "theme")) {
  output += " vf-box-theme--";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "theme"), env.opts.autoescape);
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "variant")) {
  output += " vf-box--";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "variant"), env.opts.autoescape);
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "box_modifier")) {
  output += " ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_modifier"), env.opts.autoescape);
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
  output += " | ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
  ;
  }
  output += "\">\n\n  <h3 class=\"vf-box__heading\">";
  if(runtime.contextOrFrameLookup(context, frame, "box_href")) {
  output += "<a class=\"vf-box__link\" href=\"";
  output += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "box_href")?runtime.contextOrFrameLookup(context, frame, "box_href"):"#"), env.opts.autoescape);
  output += "\">";
  ;
  }
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "box_heading"), env.opts.autoescape);
  if(runtime.contextOrFrameLookup(context, frame, "box_href")) {
  output += "</a>";
  ;
  }
  output += "</h3>\n  <p class=\"vf-box__text\">";
  output += runtime.suppressValue(env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "box_text")), env.opts.autoescape);
  output += "</p>\n</div>\n";
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Box

  DEPRECATED

  Deprecated in favour of the ACF block.
  Block is hidden from the Gutenberg inserter with `isInsertable` setting.

  */
  var vfBox = useVFCoreSettings({
    ver: '1.0.2',
    name: 'vf/box',
    title: i18n.__('Box'),
    isInsertable: false,
    attributes: {
      box_heading: {
        type: 'string'
      },
      box_text: {
        type: 'string'
      }
    },
    example: {
      attributes: {
        box_heading: i18n.__('Did You Know?'),
        box_text: i18n.__('Invasive cancer is the leading cause of death in the developed world and the second leading in the developing world.')
      }
    },
    fields: [{
      name: 'box_heading',
      control: 'rich',
      // default: '',
      label: '',
      tag: 'h3',
      placeholder: i18n.__('Type box heading…')
    }, {
      name: 'box_text',
      control: 'rich',
      // default: '',
      label: '',
      tag: 'p',
      placeholder: i18n.__('Type box content…')
    }],
    styles: [{
      name: 'default',
      label: i18n.__('Default'),
      isDefault: true
    }, {
      name: 'factoid',
      label: i18n.__('Factoid')
    }, {
      name: 'inlay',
      label: i18n.__('Inlayed')
    }],
    withHOC: [[withTransientStyle, {
      key: 'box_modifier',
      BEM: true
    }]]
  });

  /**
   * Block transforms for: `vf/button`
   */
  const fromCore$2 = () => {
    return {
      type: 'block',
      blocks: ['core/button'],
      transform: attributes => {
        const {
          url,
          text,
          className
        } = attributes;
        const outline = /\-outline/.test(className) ? 1 : 0;
        return blocks.createBlock('vf/button', {
          text,
          outline,
          href: url
        });
      }
    };
  };

  /**
   * Precompiled Nunjucks template: vf-button.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-button"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  if(runtime.contextOrFrameLookup(context, frame, "context")) {
  var t_1;
  t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"button_href");
  frame.set("button_href", t_1, true);
  if(frame.topLevel) {
  context.setVariable("button_href", t_1);
  }
  if(frame.topLevel) {
  context.addExport("button_href", t_1);
  }
  var t_2;
  t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"theme");
  frame.set("theme", t_2, true);
  if(frame.topLevel) {
  context.setVariable("theme", t_2);
  }
  if(frame.topLevel) {
  context.addExport("theme", t_2);
  }
  var t_3;
  t_3 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
  frame.set("id", t_3, true);
  if(frame.topLevel) {
  context.setVariable("id", t_3);
  }
  if(frame.topLevel) {
  context.addExport("id", t_3);
  }
  var t_4;
  t_4 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"text");
  frame.set("text", t_4, true);
  if(frame.topLevel) {
  context.setVariable("text", t_4);
  }
  if(frame.topLevel) {
  context.addExport("text", t_4);
  }
  var t_5;
  t_5 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"style");
  frame.set("style", t_5, true);
  if(frame.topLevel) {
  context.setVariable("style", t_5);
  }
  if(frame.topLevel) {
  context.addExport("style", t_5);
  }
  var t_6;
  t_6 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"size");
  frame.set("size", t_6, true);
  if(frame.topLevel) {
  context.setVariable("size", t_6);
  }
  if(frame.topLevel) {
  context.addExport("size", t_6);
  }
  var t_7;
  t_7 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
  frame.set("override_class", t_7, true);
  if(frame.topLevel) {
  context.setVariable("override_class", t_7);
  }
  if(frame.topLevel) {
  context.addExport("override_class", t_7);
  }
  var t_8;
  t_8 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"html");
  frame.set("html", t_8, true);
  if(frame.topLevel) {
  context.setVariable("html", t_8);
  }
  if(frame.topLevel) {
  context.addExport("html", t_8);
  }
  var t_9;
  t_9 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"text");
  frame.set("text", t_9, true);
  if(frame.topLevel) {
  context.setVariable("text", t_9);
  }
  if(frame.topLevel) {
  context.addExport("text", t_9);
  }
  ;
  }
  output += "\n";
  output += runtime.suppressValue(env.getExtension("spaceless")["run"](context,function(cb) {
  if(!cb) { cb = function(err) { if(err) { throw err; }};}
  var t_10 = "";t_10 += "\n";
  if((runtime.contextOrFrameLookup(context, frame, "button_href")) || (runtime.contextOrFrameLookup(context, frame, "href"))) {
  var t_11;
  t_11 = "a";
  frame.set("tags", t_11, true);
  if(frame.topLevel) {
  context.setVariable("tags", t_11);
  }
  if(frame.topLevel) {
  context.addExport("tags", t_11);
  }
  ;
  }
  else {
  var t_12;
  t_12 = "button";
  frame.set("tags", t_12, true);
  if(frame.topLevel) {
  context.setVariable("tags", t_12);
  }
  if(frame.topLevel) {
  context.addExport("tags", t_12);
  }
  ;
  }
  t_10 += "\n";
  t_10 += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "theme")) {
  var t_13;
  t_13 = runtime.contextOrFrameLookup(context, frame, "theme");
  frame.set("theme_class", t_13, true);
  if(frame.topLevel) {
  context.setVariable("theme_class", t_13);
  }
  if(frame.topLevel) {
  context.addExport("theme_class", t_13);
  }
  ;
  }
  t_10 += "\n<";
  t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
  t_10 += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "tags") == "a") {
  t_10 += " href=\"";
  t_10 += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "button_href")) || (runtime.contextOrFrameLookup(context, frame, "href")), env.opts.autoescape);
  t_10 += "\"";
  ;
  }
  t_10 += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "id")) {
  t_10 += " id=\"";
  t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
  t_10 += "\"";
  ;
  }
  t_10 += "\nclass=\"vf-button";
  t_10 += "\n";
  if(runtime.contextOrFrameLookup(context, frame, "theme_class")) {
  t_10 += " vf-button--";
  t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "theme_class"), env.opts.autoescape);
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "style")) {
  frame = frame.push();
  var t_16 = runtime.contextOrFrameLookup(context, frame, "style");
  if(t_16) {t_16 = runtime.fromIterator(t_16);
  var t_15 = t_16.length;
  for(var t_14=0; t_14 < t_16.length; t_14++) {
  var t_17 = t_16[t_14];
  frame.set("styles", t_17);
  frame.set("loop.index", t_14 + 1);
  frame.set("loop.index0", t_14);
  frame.set("loop.revindex", t_15 - t_14);
  frame.set("loop.revindex0", t_15 - t_14 - 1);
  frame.set("loop.first", t_14 === 0);
  frame.set("loop.last", t_14 === t_15 - 1);
  frame.set("loop.length", t_15);
  t_10 += " vf-button--";
  t_10 += runtime.suppressValue(t_17, env.opts.autoescape);
  ;
  }
  }
  frame = frame.pop();
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "size")) {
  t_10 += "  vf-button--";
  t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "size"), env.opts.autoescape);
  t_10 += "\n";
  ;
  }
  if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
  t_10 += " | ";
  t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
  ;
  }
  t_10 += "\">";
  t_10 += runtime.suppressValue((runtime.contextOrFrameLookup(context, frame, "html")?env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "html")):runtime.contextOrFrameLookup(context, frame, "text")), env.opts.autoescape);
  t_10 += "</";
  t_10 += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "tags"), env.opts.autoescape);
  t_10 += ">\n";
  cb(null, t_10);
  ;
  return t_10;
  }
  ,null), true && env.opts.autoescape);
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Button
  */

  const withBEMModifiers = Edit => {
    return props => {
      const transient = { ...(props.transient || {})
      };
      transient.style = [];

      if (props.attributes.outline) {
        transient.style.push('outline');
      }

      if (props.attributes.rounded) {
        transient.style.push('rounded');
      }

      if (props.attributes.pill) {
        transient.style.push('pill');
      }

      return Edit({ ...props,
        transient
      });
    };
  };

  const settings$2 = useVFCoreSettings({
    name: 'vf/button',
    title: i18n.__('Button'),
    attributes: {
      href: {
        type: 'string',
        default: '/'
      },
      text: {
        type: 'string',
        default: i18n.__('Button')
      },
      theme: {
        type: 'string',
        default: 'primary'
      },
      size: {
        type: 'string',
        default: ''
      },
      outline: {
        type: 'integer'
      },
      rounded: {
        type: 'integer'
      },
      pill: {
        type: 'integer'
      }
    },
    fields: [{
      name: 'text',
      control: 'text',
      label: i18n.__('Label')
    }, {
      name: 'href',
      control: 'url',
      label: i18n.__('URL')
    }, {
      name: 'size',
      control: 'select',
      label: i18n.__('Size'),
      inspector: true,
      options: [{
        label: i18n.__('Small'),
        value: 'sm'
      }, {
        label: i18n.__('Large'),
        value: 'lg'
      }]
    }, {
      name: 'outline',
      control: 'true_false',
      label: i18n.__('Outline'),
      inspector: true
    }, {
      name: 'rounded',
      control: 'true_false',
      label: i18n.__('Rounded'),
      inspector: true
    }, {
      name: 'pill',
      control: 'true_false',
      label: i18n.__('Pill'),
      inspector: true
    }],
    styles: [{
      name: 'primary',
      label: i18n.__('Primary'),
      isDefault: true
    }, {
      name: 'secondary',
      label: i18n.__('Secondary')
    }, {
      name: 'tertiary',
      label: i18n.__('Tertiary')
    }],
    transforms: {
      from: [fromCore$2()]
    },
    withHOC: [[withBEMModifiers], [withTransientAttributeMap, [{
      from: 'href',
      to: 'button_href'
    }]], [withTransientStyle, {
      key: 'theme'
    }]]
  });
  var vfButton = { ...settings$2,
    supports: { ...settings$2.supports,
      inserter: false
    }
  };

  /**
   * Block transforms for: `vf/divider`
   */
  const fromCore$1 = () => {
    return {
      type: 'block',
      blocks: ['core/separator'],
      transform: attributes => {
        return blocks.createBlock('vf/divider');
      }
    };
  };

  /**
   * Precompiled Nunjucks template: vf-divider.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-divider"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  if(runtime.contextOrFrameLookup(context, frame, "context")) {
  var t_1;
  t_1 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"id");
  frame.set("id", t_1, true);
  if(frame.topLevel) {
  context.setVariable("id", t_1);
  }
  if(frame.topLevel) {
  context.addExport("id", t_1);
  }
  var t_2;
  t_2 = runtime.memberLookup((runtime.contextOrFrameLookup(context, frame, "context")),"override_class");
  frame.set("override_class", t_2, true);
  if(frame.topLevel) {
  context.setVariable("override_class", t_2);
  }
  if(frame.topLevel) {
  context.addExport("override_class", t_2);
  }
  ;
  }
  output += "\n<hr\n";
  if(runtime.contextOrFrameLookup(context, frame, "id")) {
  output += " id=\"";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "id"), env.opts.autoescape);
  output += "\"";
  ;
  }
  output += "class=\"vf-divider";
  if(runtime.contextOrFrameLookup(context, frame, "override_class")) {
  output += " | ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "override_class"), env.opts.autoescape);
  ;
  }
  output += "\"";
  if(runtime.contextOrFrameLookup(context, frame, "context_margin__inline")) {
  output += " style=\"--context-margin--inline: ";
  output += runtime.suppressValue(runtime.contextOrFrameLookup(context, frame, "context_margin__inline"), env.opts.autoescape);
  output += ";\"";
  ;
  }
  output += ">\n";
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Divider
  */
  const settings$1 = useVFCoreSettings({
    name: 'vf/divider',
    title: i18n.__('Divider'),
    attributes: {},
    transforms: {
      from: [fromCore$1()]
    }
  });
  var vfDivider = { ...settings$1,
    supports: { ...settings$1.supports,
      inserter: false
    }
  };

  /**
   * Block transforms for: `vf/lede`
   */
  const fromCore = () => {
    return {
      type: 'block',
      blocks: ['core/heading', 'core/paragraph'],
      transform: attributes => {
        return blocks.createBlock('vf/lede', {
          text: attributes.content
        });
      }
    };
  };

  /**
   * Precompiled Nunjucks template: vf-lede.njk
   */
  (function() {(window.nunjucksPrecompiled = window.nunjucksPrecompiled || {})["vf-lede"] = (function() {
  function root(env, context, frame, runtime, cb) {
  var lineno = 0;
  var colno = 0;
  var output = "";
  try {
  var parentTemplate = null;
  output += "<p class=\"vf-lede\">";
  output += runtime.suppressValue(env.getFilter("safe").call(context, runtime.contextOrFrameLookup(context, frame, "vf_lede_text")), env.opts.autoescape);
  output += "</p>\n";
  if(parentTemplate) {
  parentTemplate.rootRenderFunc(env, context, frame, runtime, cb);
  } else {
  cb(null, output);
  }
  ;
  } catch (e) {
    cb(runtime.handleError(e, lineno, colno));
  }
  }
  return {
  root: root
  };

  })();
  })();

  /**
  Block Name: Lede
  */
  const settings = useVFCoreSettings({
    name: 'vf/lede',
    title: i18n.__('Lede'),
    attributes: {
      text: {
        type: 'string'
      }
    },
    fields: [{
      name: 'text',
      control: 'rich',
      label: '',
      tag: 'h1'
    }],
    transforms: {
      from: [fromCore()]
    },
    withHOC: [[withTransientAttributeMap, [{
      from: 'text',
      to: 'vf_lede_text'
    }]]]
  });
  var vfLede = { ...settings,
    supports: { ...settings.supports,
      inserter: false
    }
  };

  /**
  Block Name: Plugin
  Notes:
    * This is not actually a VF component
    * It's named `vf/plugin` to avoid breaking existing usage
    * VF_Block and VF_Container plugins have default content, e.g.:

    <!-- wp:vf/plugin {"ref":"vf_masthead"} /-->

    */
  const defaults = useVFDefaults();
  const renderStore = {};

  const Edit = props => {
    const [acfId] = React.useState(acf.uniqid('block_'));
    const [isFetching, setFetching] = React.useState(true);
    const [isLoading, setLoading] = React.useState(true);
    const [render, setRender] = React.useState('');
    const [script, setScript] = React.useState(null);
    const ref = React.useRef(null);
    const {
      clientId
    } = props;
    const onMessage = React.useCallback(ev => {
      const {
        id
      } = ev.data;

      if (id && id.includes(acfId)) {
        clearTimeout(window[`${id}_onMessage`]);
        window[`${id}_onMessage`] = setTimeout(() => {
          window.removeEventListener('message', onMessage);
          setLoading(false);
        }, 100);
      }
    }, [clientId]);
    React.useEffect(() => {
      setLoading(true);
      setFetching(true);
      window.removeEventListener('message', onMessage);
      window.addEventListener('message', onMessage);

      const fetch = async () => {
        let render;
        const fields = {
          is_plugin: 1,
          ...props.transient.fields
        };
        const renderHash = useHashsum(fields);

        if (renderStore.hasOwnProperty(renderHash)) {
          render = await new Promise(resolve => setTimeout(() => {
            resolve(renderStore[renderHash]);
          }, 1));
        } else {
          const response = await wp.ajax.post('acf/ajax/fetch-block', {
            query: {
              preview: true
            },
            nonce: acf.get('nonce'),
            post_id: acf.get('post_id'),
            block: JSON.stringify({
              id: acfId,
              name: props.attributes.ref,
              data: fields,
              align: '',
              mode: 'preview'
            })
          });

          if (response && response.preview) {
            render = response.preview;
            renderStore[renderHash] = render;
          }
        }

        if (render) {
          const html = render.split(/<script[^>]*?>/)[0];
          const script = render.match(/<script[^>]*?>(.*)<\/script>/ms);
          setScript(Array.isArray(script) ? script[1] : null);
          setRender(html);
          setFetching(false);
        }
      };

      fetch();
    }, [clientId, props.attributes.__acfUpdate]);
    React.useEffect(() => {
      if (isFetching) {
        return;
      }

      ref.current.innerHTML = render;

      if (script) {
        const el = document.createElement('script');
        el.type = 'text/javascript';
        el.innerHTML = script;
        ref.current.appendChild(el);
      }
    }, [isFetching]); // add DOM attributes for styling

    const rootAttrs = {
      className: `vf-block ${props.className}`,
      'data-ver': props.attributes.ver,
      'data-name': props.name,
      'data-editing': false,
      'data-loading': isLoading,
      style: {}
    };

    if (isLoading) {
      rootAttrs.style.minHeight = '100px';
    }

    const viewStyle = {};

    if (isLoading) {
      viewStyle.visibility = 'hidden';
    }

    return wp.element.createElement("div", rootAttrs, isLoading && wp.element.createElement(components.Spinner, null), wp.element.createElement("div", {
      ref: ref,
      style: viewStyle,
      className: "vf-block__view"
    }));
  };

  const withACFUpdates = Edit => {
    const transient = {
      fields: {}
    };
    return props => {
      const {
        clientId
      } = props;
      React.useEffect(() => {
        if (hooks.hasAction('vf_plugin_acf_update', 'vf_plugin')) {
          return;
        }

        hooks.addAction('vf_plugin_acf_update', 'vf_plugin', data => {
          transient.fields[data.name] = data.value;
          props.setAttributes({
            __acfUpdate: Date.now()
          });
        });
      }, [clientId]);
      return Edit({ ...props,
        transient: { ...(props.transient || {}),
          ...transient
        }
      });
    };
  };
  var vfPlugin = { ...defaults,
    name: 'vf/plugin',
    title: i18n.__('Preview'),
    category: 'vf/wp',
    description: '',
    attributes: { ...defaults.attributes,
      ref: {
        type: 'string'
      }
    },
    supports: { ...defaults.supports,
      inserter: false,
      reusable: false
    },
    edit: withACFUpdates(Edit),
    save: () => null
  };

  /**!
   * VF Gutenberg blocks
   */

  const {
    coreOptin
  } = useVFGutenberg(); // Register VF Core blocks

  if (parseInt(coreOptin) === 1) {
    const coreBlocks = [//Tabs
    settings$7, settings$6, // Grid
    settings$a, settings$9, settings$8, // Inner Blocks
    settings$b, // Elements
    vfBadge, vfBlockquote, vfButton, vfDivider, // Blocks
    vfActivityItem, vfActivityList, vfBox, vfBreadcrumbsItem, vfBreadcrumbs, vfLede, vfEmbed];
    coreBlocks.forEach(settings => blocks.registerBlockType(settings.name, settings));
  } // Register experimental preview block
  blocks.registerBlockType('vf/plugin', vfPlugin); // Handle iframe preview resizing globally
  // TODO: remove necessity from `useVFIFrame`

  window.addEventListener('message', ({
    data
  }) => {
    if (data !== Object(data) || !/^vfwp_/.test(data.id)) {
      return;
    }

    const iframe = document.getElementById(data.id);

    if (!iframe || !iframe.vfActive) {
      return;
    }

    window.requestAnimationFrame(() => {
      iframe.style.height = `${data.height}px`;
    });
  });

})));
