/**!
 * VF-WP Plugins
 */
(function ($, wp, acf) {
  if (
    typeof $ !== 'function' ||
    typeof wp !== 'object' ||
    typeof acf !== 'object'
  ) {
    return;
  }
  // Get VF Plugin config from localized global var
  var config = window.vfPlugin;
  if (typeof config !== 'object') {
    return;
  }

  /**
   * Setup live preview update
   */
  const __experimental__has_admin_preview = () => {
    // Configure ACF fields
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
  };

  const wpDomReady = () => {
    __experimental__has_admin_preview();
  };

  wp.domReady(wpDomReady);
})(window.jQuery, window.wp, window.acf);
