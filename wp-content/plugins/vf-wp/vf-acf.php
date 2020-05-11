<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_ACF') ) :

class VF_ACF {

  function initialize() {
    add_filter(
      'acf/settings/show_admin',
      array($this, 'acf_settings_show_admin')
    );
    add_action(
      'admin_notices',
      array($this, 'admin_notices')
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
    add_action(
      'enqueue_block_editor_assets',
      array($this, 'enqueue_block_editor_assets')
    );
  }

  /**
   * Enqeue script for live plugin previews
   */
  function enqueue_block_editor_assets() {
    global $post;
    $plugin = VF_Plugin::get_plugin($post->post_name);

    if ( ! $plugin || ! $plugin->__experimental__has_admin_preview()) {
      return;
    }

    wp_register_script(
      'vf-plugin',
      plugins_url(
        '/assets/vf-plugin.js',
        __FILE__
      ),
      array('wp-editor', 'wp-blocks'),
      false,
      true
    );

    $local = $plugin->config();

    wp_localize_script('vf-plugin', 'vfPlugin', $local);
    wp_enqueue_script('vf-plugin');
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
   * Action: add notice to ACF screens
   */
  public function admin_notices() {
    if ( ! function_exists('get_current_screen')) {
      return;
    }
    // if (vf_debug()) {
    //   return;
    // }
    $ids = array(
      'edit-acf-field-group',
      'acf-field-group',
    );
    $screen = get_current_screen();
    if ( ! in_array($screen->id, $ids)) {
      return;
    }
    printf('<div class="%1$s"><h3><b>%2$s</b></h3><p><b>%3$s</b> %4$s</p><p>%5$s</p></div>',
      esc_attr('notice notice-warning | vfwp-notice'),
      esc_html__('Attention!', 'vfwp'),
      esc_html__('Advanced custom fields should not be synced or edited on live websites.', 'vfwp'),
      esc_html__('Only configure them during development of plugins or themes. Visit the repository below to find developer documentation:', 'vfwp'),
      sprintf(
        '<a href="%1$s" target="_blank">%2$s %3$s</a>',
        esc_attr('https://github.com/visual-framework/vf-wp'),
        '<span class="dashicons dashicons-external"></span>',
        esc_html__('VF-WP on GitHub', 'vfwp')
      )
    );
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
      // Deprecated in favour of `VF_Templates`
      // 'group_vf_containers'
    );
    if (in_array($group['key'], $core_groups)) {
      update_option('vf__acf_save_json', plugin_dir_path(__FILE__));
    } else {
      delete_option('vf__acf_save_json');
    }
    return $group;
  }

} // VF_ACF

endif;

?>
