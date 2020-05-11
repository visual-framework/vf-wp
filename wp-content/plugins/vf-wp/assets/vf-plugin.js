/**!
 * VF Plugin block preview
 */
(function($, wp, acf) {
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
  wp.domReady(function() {
    var fields = acf.getFields();
    fields.forEach(function(field) {
      var data = field.data;
      // Get related input field for jQuery
      var $input = field.$input();
      if (!$input || !$input.length) {
        return;
      }
      data.value = field.val();
      wp.hooks.doAction('vf__experimental__acf_update', data);
      // Bind change event to WordPress hook
      $input.on('change', function(e) {
        data.value = field.val();
        wp.hooks.doAction('vf__experimental__acf_update', data);
      });
    });
  });
})(window.jQuery, window.wp, window.acf);
