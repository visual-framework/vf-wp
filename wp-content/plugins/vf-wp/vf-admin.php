<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Admin') ) :

class VF_Admin {

  function __construct() {
    add_filter(
      'acf/settings/show_admin',
      array($this, 'acf_settings_show_admin')
    );
    add_filter(
      'acf/settings/save_json',
      array($this, 'acf_settings_save_json'),
      1
    );
    add_action(
      'acf/update_field_group',
      array($this, 'acf_update_field_group'),
      1, 1
    );
  }

  /**
   * Filter: hide ACF for non-admins
   */
  function acf_settings_show_admin() {
    if ( ! vf_debug()) {
      return false;
    }
    return true;
  }

  /**
   * Filter: save to default theme directory unless plugin has set option
   */
  function acf_settings_save_json() {
    $path = get_template_directory() . '/config';
    $save = get_option('vf__acf_save_json');
    if ($save) {
      if (is_dir($save)) {
        $path = $save;
      }
      delete_option('vf__acf_save_json');
    }
    return $path;
  }

  /**
   * Action: update save path option for ACF json if this group is edited
   */
  function acf_update_field_group($group) {
    $core_groups = array(
      'group_vf_containers'
    );
    if (in_array($group['key'], $core_groups)) {
      update_option('vf__acf_save_json', plugin_dir_path(__FILE__));
    } else {
      delete_option('vf__acf_save_json');
    }
    return $group;
  }

} // VF_Admin

endif;

?>
