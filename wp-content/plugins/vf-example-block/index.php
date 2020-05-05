<?php
/*
Plugin Name: VF-WP Example
Description: VF-WP theme block (developer example).
Version: 1.0.0-beta.1
Author: EMBL-EBI Web Development
Plugin URI: https://github.com/visual-framework/vf-wp
Text Domain: vfwp
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check the VF-WP plugin dependency exists.
 * Block and Container plugins inherit a shared class.
 */
$path = WP_PLUGIN_DIR . '/vf-wp/vf-plugin.php';
if ( ! file_exists($path)) return;
require_once($path);

/**
 * Extend the base plugin class.
 * Plugin classes act as a wrapper for a `WP_Post` and provide common
 * methods to use ACF post meta in their template.
 */
class VF_Example extends VF_Plugin {

  protected $file = __FILE__;

  protected $config = array(
    'post_name'  => 'vf_example',
    'post_title' => 'Example',

    // Allow block to be previewed in WP admin
    '__experimental__has_admin_preview' => true
  );

  public function __construct(array $params = array()) {
    /**
     * Parent constructor sets up local variables.
     */
    parent::__construct('vf_example');

    if (array_key_exists('init', $params)) {
      $this->init();
    }
  }

  private function init() {
    /**
     * Initialize to add plugin registration hooks.
     * The related `vf_block` or `vf_container` post is created (or updated.)
     */
    parent::initialize();

    /**
     * Add ACF filter hook to dynamically populate a select field
     */
    add_filter(
      'acf/load_field/name=vf_example_select',
      array($this, 'acf_load_select')
    );
  }

  public function acf_load_select($field) {
    $choices = array(
      'Value 1' => 'Choice 1',
      'Value 2' => 'Choice 2',
      'Value 3' => 'Choice 3'
    );
    $field['choices'] = $choices;
    return $field;
  }

} // VF_Example

/**
 * Initialize once from this file only.
 */
$plugin = new VF_Example(array('init' => true));

?>
