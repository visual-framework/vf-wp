<?php

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Is this the last post in a `while (have_posts())` loop?
 */
function vf_last_post() {
  global $wp_query;
  return $wp_query->current_post + 1 >= $wp_query->post_count;
}

?>
