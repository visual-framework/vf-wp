<?php
/**
 * VF Customize
 */
if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Admin_Customize') ) :

class VF_Admin_Customize {

  public function __construct() {
    // Add admin hooks
    add_action(
      'customize_register',
      array($this, 'customize_register')
    );
  }

  /**
   * Add theme customisation
   */
  public function customize_register($wp_customize) {
    // No way!
    $wp_customize->remove_section('custom_css');

    // Add VF theme section
    $wp_customize->add_section('vf_theme' , array(
      'title'    => __('Visual Framework', 'vfwp'),
      'priority' => 30
    ));

    // Allow child theme access
    VF_Theme::do_action('vf/admin/customize', $wp_customize);
  }

} // VF_Admin_Customize

endif;

?>
