/**!
 * VF-WP Plugins
 */
(function($, wp, acf) {
  if (typeof $ !== 'function' || typeof wp !== 'object') {
    return;
  }

  const isVFTemplate = window.vfPlugin.post_type === 'vf_template';
  const isVFBlock = window.vfPlugin.post_type === 'vf_block';
  const isVFContainer = window.vfPlugin.post_type === 'vf_container';
  const isVFPlugin = isVFBlock || isVFContainer;

  const filterRegisterBlockType = (settings, name) => {
    const isVFContainerBlock = settings.category === 'vf/containers';

    // Ensure a supports object
    if (typeof settings.supports !== 'object') {
      settings.supports = {};
    }

    // Show container blocks for `vf_template`
    if (isVFTemplate && isVFContainerBlock) {
      settings.supports.inserter = true;
    }

    // Hide non-container blocks for `vf_template`
    if (isVFTemplate && !isVFContainerBlock) {
      settings.supports.inserter = false;
    }

    // Hide container blocks from non `vf_template`
    if (!isVFTemplate && isVFContainerBlock) {
      settings.supports.inserter = false;
    }

    // Hide old legacy containers (were replaced by ACF)
    if (isVFContainerBlock && /^vf\/container-/.test(name)) {
      settings.supports.inserter = false;
    }

    return settings;
  };

  wp.hooks.addFilter(
    'blocks.registerBlockType',
    'vf/registerBlockType',
    filterRegisterBlockType
  );

  const ensurePluginPreviewBlock = (attempt = 0) => {
    if (!isVFPlugin || !wp.blocks || !wp.data) {
      return;
    }

    const blockName = 'vf/plugin';
    if (!wp.blocks.getBlockType(blockName)) {
      if (attempt < 20) {
        window.setTimeout(() => ensurePluginPreviewBlock(attempt + 1), 100);
      }
      return;
    }

    const editor = wp.data.select('core/block-editor');
    const dispatcher = wp.data.dispatch('core/block-editor');
    if (
      !editor ||
      !dispatcher ||
      typeof editor.getBlocks !== 'function' ||
      typeof dispatcher.insertBlocks !== 'function'
    ) {
      return;
    }

    const blocks = editor.getBlocks();
    const previewBlocks = blocks.filter((block) => block.name === blockName);
    const attrs = {ver: '2.0.0'};
    if (window.vfPlugin.preview_ref) {
      attrs.ref = window.vfPlugin.preview_ref;
    }

    if (!previewBlocks.length) {
      dispatcher.insertBlocks(wp.blocks.createBlock(blockName, attrs), 0);
      return;
    }

    if (
      attrs.ref &&
      previewBlocks[0].attributes.ref !== attrs.ref &&
      typeof dispatcher.updateBlockAttributes === 'function'
    ) {
      dispatcher.updateBlockAttributes(previewBlocks[0].clientId, {
        ref: attrs.ref
      });
    }
  };

  if (window.wp) {
    wp.domReady(ensurePluginPreviewBlock);
  }

  /**
   * Setup live preview update
   */

  if (typeof acf !== 'object') {
    return;
  }

  const previewFields = {};
  const boundFields = new WeakSet();
  let previewStoreTimeout = null;

  const getFieldData = (field) => ({
    key: field.get ? field.get('key') : field.data && field.data.key,
    name: field.get ? field.get('name') : field.data && field.data.name,
    type: field.get ? field.get('type') : field.data && field.data.type
  });

  const getTinyMceEditor = (field) => {
    if (typeof window.tinymce !== 'object') {
      return null;
    }
    const $input = field.$input();
    const id = $input && $input.attr('id');
    return id ? window.tinymce.get(id) : null;
  };

  const inputNameParts = (inputName, fieldKey) => {
    const prefix = `acf[${fieldKey}]`;
    if (inputName === prefix) {
      return [];
    }
    if (inputName.indexOf(`${prefix}[`) !== 0) {
      return null;
    }

    const parts = [];
    inputName
      .slice(prefix.length)
      .replace(/\[([^\]]*)\]/g, (match, part) => {
        parts.push(part);
        return match;
      });
    return parts;
  };

  const addInputValue = (value, path, nextValue) => {
    if (!path.length) {
      return nextValue;
    }

    const nextPath = path.slice(1);
    const key = path[0];
    const target = value && typeof value === 'object' && !Array.isArray(value)
      ? value
      : {};

    if (key === '') {
      const list = Array.isArray(value) ? value : [];
      list.push(nextValue);
      return list;
    }

    target[key] = addInputValue(target[key], nextPath, nextValue);
    return target;
  };

  const isEmptyPreviewValue = (value) => {
    if (Array.isArray(value)) {
      return value.every(isEmptyPreviewValue);
    }
    if (value && typeof value === 'object') {
      return Object.values(value).every(isEmptyPreviewValue);
    }
    return value === '' || value === null || typeof value === 'undefined';
  };

  const normalizePreviewValue = (value) => {
    return isEmptyPreviewValue(value) ? '' : value;
  };

  const getSerializedFieldValue = (field) => {
    const {key} = getFieldData(field);
    if (!key) {
      return undefined;
    }

    let value;
    const $field = field.$el || field.$input().closest('.acf-field');
    $field.find(':input[name]').each(function() {
      const input = this;
      const type = (input.type || '').toLowerCase();
      if (input.disabled || ['button', 'submit', 'reset'].includes(type)) {
        return;
      }
      if ((type === 'checkbox' || type === 'radio') && !input.checked) {
        return;
      }

      const path = inputNameParts(input.name, key);
      if (!path) {
        return;
      }

      const $input = $(input);
      const inputValue = $input.val();
      if (Array.isArray(inputValue)) {
        inputValue.forEach((item) => {
          value = addInputValue(value, path, item);
        });
        return;
      }
      value = addInputValue(value, path, inputValue);
    });

    return normalizePreviewValue(value);
  };

  const getFieldValue = (field) => {
    const editor = getTinyMceEditor(field);
    if (editor && !editor.isHidden()) {
      return editor.getContent();
    }

    const serialized = getSerializedFieldValue(field);
    if (typeof serialized !== 'undefined') {
      return serialized;
    }

    return normalizePreviewValue(field.val());
  };

  const updatePreviewField = (field) => {
    const data = field.data || {};
    const fieldData = getFieldData(field);
    if (!fieldData.name) {
      return;
    }

    const value = getFieldValue(field);
    const fields = {
      [fieldData.name]: value
    };
    if (fieldData.key) {
      fields[`_${fieldData.name}`] = fieldData.key;
    }

    Object.assign(previewFields, fields);
    data.value = value;
    data.name = fieldData.name;
    data.key = fieldData.key;
    data.fields = fields;
    wp.hooks.doAction('vf_plugin_acf_update', data);
    queuePreviewStore();
  };

  const queuePreviewFieldUpdate = (field) => {
    window.setTimeout(() => updatePreviewField(field), 0);
    window.setTimeout(() => updatePreviewField(field), 100);
  };

  const bindPreviewField = (field) => {
    if (!field || boundFields.has(field)) {
      return;
    }

    boundFields.add(field);
    const $input = field.$input();
    const onChange = () => queuePreviewFieldUpdate(field);

    if ($input && $input.length) {
      $input.on(
        'change input keyup paste blur select2:select select2:unselect',
        onChange
      );
    }
    if (field.$el && field.$el.length) {
      field.$el.on(
        'change input keyup paste blur select2:select select2:unselect',
        ':input',
        onChange
      );
    }

    const editor = getTinyMceEditor(field);
    if (editor) {
      editor.on('change keyup input paste undo redo SetContent', onChange);
    }

    queuePreviewFieldUpdate(field);
  };

  const storePreviewFields = () => {
    const config = window.vfPlugin || {};
    if (!isVFPlugin || !config.post_id || !config.preview_nonce) {
      return;
    }
    wp.ajax.post('vf/plugin/preview_fields', {
      post_id: config.post_id,
      nonce: config.preview_nonce,
      fields: previewFields
    });
  };

  const queuePreviewStore = () => {
    clearTimeout(previewStoreTimeout);
    previewStoreTimeout = setTimeout(storePreviewFields, 100);
  };

  const pluginPreviews = () => {
    // Configure ACF fields

    if (!isVFPlugin) {
      return;
    }

    const fields = acf.getFields();
    fields.forEach((field) => {
      bindPreviewField(field);
    });
  };

  acf.addAction('change_field', queuePreviewFieldUpdate);
  acf.addAction('ready', pluginPreviews);
  acf.addAction('append', pluginPreviews);

  acf.addAction('wysiwyg_tinymce_init', function(editor, id) {
    const fields = acf.getFields();
    fields.forEach((field) => {
      const $input = field.$input();
      if (!$input || $input.attr('id') !== id) {
        return;
      }
      const onChange = () => queuePreviewFieldUpdate(field);
      editor.on('change keyup input paste undo redo SetContent', onChange);
    });
  });

  document.addEventListener('click', function(ev) {
    if (!isVFPlugin) {
      return;
    }

    const target = ev.target.closest([
      '.editor-post-preview',
      '.editor-preview-dropdown__button-external',
      '.editor-preview-dropdown__button-resize',
      '[aria-label*="Preview"]'
    ].join(','));

    if (target) {
      storePreviewFields();
    }
  }, true);

  if (window.wp) {
    wp.domReady(function() {
      try {
        pluginPreviews();
      } catch (err) {
        console.log(err);
      }
    });
  }
})(window.jQuery, window.wp, window.acf);
