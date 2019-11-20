<?php
/*
Plugin Name: VF-WP Latest Posts
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

} // VF_Latest_Posts

$plugin = new VF_Latest_Posts(array('init' => true));

?>
