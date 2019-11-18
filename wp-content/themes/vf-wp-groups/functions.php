<?php

if( ! defined( 'ABSPATH' ) ) exit;

require_once('functions/groups-theme.php');

/**
 * Wait for parent theme functions to initialise
 * Use `__experimental__` flag for forwards-compatibility
 */
add_action('vf/__experimental__/theme/init', function() {
  global $vf_groups_theme;
  if ( ! isset($vf_groups_theme)) {
    $vf_groups_theme = new VF_Groups_Theme();
  }
});

?>
