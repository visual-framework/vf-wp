<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Container_Placeholder') ) :

class VF_Container_Placeholder extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_page_template',
    'post_title' => 'Page Template',
    'post_type'  => 'vf_container',
  );

  public function __construct() {
    parent::__construct();
    parent::initialize();
  }

  public function is_acf() {
    return false;
  }

  public function is_builtin() {
    return true;
  }

  public function is_theme() {
    return false;
  }

  public function is_plugin() {
    return false;
  }

  public function template() {
    return;
  }

  public function template_callback() {
?>
<div class="vf-banner vf-banner--info">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo esc_html_e('This is a placeholder for the page content.', 'vfwp'); ?>
    </p>
  </div>
<div>
<?php
  }

} // VF_Container_Placeholder

endif;

?>
