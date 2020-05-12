/**!
 * VF-WP Plugins
 */
(function ($, wp, acf) {
  if (typeof $ !== 'function' || typeof wp !== 'object') {
    return;
  }

  const filterRegisterBlockType = (settings, name) => {
    const isContainer = settings.category === 'vf/containers';
    const isTemplate = window.vfPlugin.post_type === 'vf_template';

    if (typeof settings.supports !== 'object') {
      settings.supports = {};
    }

    // Show container blocks for `vf_template`
    if (isTemplate && isContainer) {
      settings.supports.inserter = true;
    }

    // Hide non-container blocks for `vf_template`
    if (isTemplate && !isContainer) {
      settings.supports.inserter = false;
    }

    // Hide container blocks from non `vf_template`
    if (!isTemplate && isContainer) {
      settings.supports.inserter = false;
    }

    // Hide old legacy containers (were replaced by ACF)
    if (isContainer && /^vf\/container-/.test(name)) {
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

  const __experimental__has_admin_preview = () => {
    // Configure ACF fields
    try {
      // Get VF Plugin config from localized global var
      var plugin = window.vfPlugin.plugin;
      if (
        typeof plugin !== 'object' ||
        plugin.__experimental__has_admin_preview !== true
      ) {
        return;
      }
      const fields = acf.getFields();
      fields.forEach(field => {
        const data = field.data;
        // Get related input field for jQuery
        const $input = field.$input();
        if (!$input || !$input.length) {
          return;
        }
        // Bind change event to WordPress hook
        const onChange = ev => {
          data.value = field.val();
          wp.hooks.doAction('vf__experimental__acf_update', data);
        };
        $input.on('change', onChange);
        // Update initial preview props
        onChange(null);
      });
    } catch (err) {
      // console.log(err);
    }
  };

  const wpDomReady = () => {
    __experimental__has_admin_preview();
  };

  wp.domReady(wpDomReady);
})(window.jQuery, window.wp, window.acf);
