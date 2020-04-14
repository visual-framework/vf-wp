<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Events_ACF') ) :

class VF_Events_ACF {

  // Root plugin `__FILE__`
  private $plugin;

  function __construct($plugin) {
    $this->plugin = $plugin;

    // Add hooks
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json')
    );
  }

  /**
   * Filter: `acf/settings/load_json`
   */
  public function acf_settings_load_json($paths) {
    $dir = plugin_dir_path($this->plugin);
    $paths[] = "{$dir}acf-json";
    return $paths;
  }

} // VF_Events_ACF

endif;

?>
