<?php
/*
Plugin Name: VF-WP Latest Posts (Deprecated)
Description: VF-WP theme block.
Version: 0.1.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Latest_Posts extends VF_Plugin {

  public function is_deprecated() {
    return true;
  }

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_latest_posts',
    'post_title' => 'Latest Posts',
  );

  public function __construct(array $params = array()) {
    parent::__construct('vf_latest_posts');
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize();
    add_action('admin_head', array($this, 'admin_head'), 15);
    add_action(
      'admin_print_footer_scripts',
      array($this, 'admin_print_footer_scripts')
    );
  }

  function is_template_standalone() {
    return true;
  }

  function admin_head() {
?>
<style>
.wp-block[data-type="vf/latest-posts"] {
  max-width: none;
}
</style>
<?php
  }

  function admin_print_footer_scripts() {
    if ( ! function_exists('get_current_screen')) {
      return;
    }
    $screen = get_current_screen();
    if ($screen->base !== 'post' || $screen->id !== 'vf_block') {
      return;
    }
    global $post;
    if (
      ! $post instanceof WP_Post ||
      $post->post_name !== $this->config['post_name']
    ) {
      return;
    }
?>
<script type="text/javascript">
(function(wp) {
  wp.data.dispatch('core/notices').createNotice(
    'error',
    'This block is deprecated! Please refer to the new version found in the "EMBL – WordPress" editor category.',
    {
      isDismissible: false
    }
  );
})(window.wp);
</script>
<?php
  }

} // VF_Latest_Posts

$plugin = new VF_Latest_Posts(array('init' => true));

?>
