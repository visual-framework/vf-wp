<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VFWP_Hero') ) :

class VFWP_Hero extends VFWP_Block {

  public function __construct() {
    parent::__construct(__FILE__);

    add_filter(
      'acf/load_field/name=vf_hero_navigation_menu',
      array($this, 'acf_load_navigation_menu')
    );
  }

  public function acf_load_navigation_menu($field) {
    $field['choices'] = class_exists('VF_Navigation')
      ? VF_Navigation::get_navigation_menu_choices()
      : array();
    $field['default_value'] = class_exists('VF_Navigation')
      ? VF_Navigation::get_primary_menu_source()
      : '';

    return $field;
  }

} // VFWP_Hero

// Initialize one instance
$vfwp_hero = new VFWP_Hero();

endif; ?>
