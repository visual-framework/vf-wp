<?php
/*
Plugin Name: VF-WP Group Header
Description: VF-WP theme block.
Version: 1.0.0-beta.2
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

class VF_Group_Header extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_group_header',
    'post_title' => 'Group Header',
  );

  private $is_minimal = false;

  function __construct(array $params = array()) {
    parent::__construct('vf_group_header');
    if (array_key_exists('minimal', $params) && $params['minimal']) {
      $this->is_minimal = true;
    }
    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    parent::initialize();
    add_action('admin_head', array($this, 'admin_head'), 15);
    // Do no wrap in `vf-content` classes
    add_filter(
      'vf/theme/content/is_block_wrapped/name=acf/vf-group-header',
      '__return_false'
    );
    // Support older version
    add_filter(
      'vf/theme/content/is_block_wrapped/name=vf/group-header',
      '__return_false'
    );
  }

  function is_minimal() {
    return $this->is_minimal;
  }

  function is_template_standalone() {
    return ! $this->is_minimal();
  }

  function admin_head() {
?>
<style>
.wp-block[data-type="vf/group-header"],
.wp-block[data-type="acf/vf-group-header"] {
  max-width: none;
}
</style>
<?php
  }

} // VF_Group_Header

$plugin = new VF_Group_Header(array('init' => true));

?>
