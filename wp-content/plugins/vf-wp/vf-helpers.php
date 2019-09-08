<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Return true if in debug mode
 */
function vf_debug() {
  if (function_exists('wp_get_current_user')) {
    $user = wp_get_current_user();
    if ( ! array_intersect(array('administrator'), $user->roles)) {
      return false;
    }
  }
  return defined('WP_DEBUG') && WP_DEBUG;
}

/**
 * Returns true if a string is empty (including empty HTML)
 */
function vf_html_empty($str) {
  return preg_match('#\S#', strip_tags($str)) !== 1;
}

/**
 * Remove unwanted characters
 */
function vf_search_keyword($str) {
  $str = stripslashes($str);
  // Restrict to whitespace, alpha-numeric (+unicode), and hyphens
  $str = preg_replace('#[^-\s\d\w\p{L}]#u', ' ', $str);
  // Remove excess whitespace
  $str = preg_replace('/\s+/', ' ', $str);
  $str = trim($str);
  return $str;
}

?>
