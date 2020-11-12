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

  /**
   * Setup live preview update
   */

  if (typeof acf !== 'object') {
    return;
  }

  const pluginPreviews = () => {
    // Configure ACF fields

    // Get VF Plugin config from localized global var
    var plugin = window.vfPlugin.plugin;
    if (typeof plugin !== 'object') {
      return;
    }
    const fields = acf.getFields();
    fields.forEach((field) => {
      const data = field.data;
      // Get related input field for jQuery
      const $input = field.$input();
      if (!$input || !$input.length) {
        return;
      }
      // Bind change event to WordPress hook
      const onChange = (ev) => {
        data.value = field.val();
        wp.hooks.doAction('vf_plugin_acf_update', data);
      };
      $input.on('change', onChange);
      // Update initial preview props
      // onChange(null);
    });
  };

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
