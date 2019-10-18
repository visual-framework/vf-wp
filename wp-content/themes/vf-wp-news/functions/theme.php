<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Append <body> class for Visual Framework
 */
add_filter('body_class', 'vf__body_class');

function vf__body_class($classes) {
  // $classes[] = 'vf-body';
  $classes[] = 'vf-wp-theme';
  if (is_singular('vf_block') || is_singular('vf_container')) {
    return $classes;
  }
  // $classes[] = 'vf-u-background-color-ui--grey';
  return $classes;
}


?>
