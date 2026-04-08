<?php

$vfwp_latest_posts_helpers = locate_template( 'blocks/vfwp-latest-posts/partials/helpers.php', false, false );

if ( $vfwp_latest_posts_helpers ) {
  require_once $vfwp_latest_posts_helpers;
}

$loopPost = new WP_Query(
  vfwp_latest_posts_build_query_args(
    array(
      'limit'          => $limit ?? get_field( 'limit' ),
      'post_type'      => $post_type ?? get_field( 'post_type' ),
      'category'       => $category ?? get_field( 'category' ),
      'tag'            => $tag ?? get_field( 'tag' ),
      'topic'          => get_field( 'topic' ),
      'topic_updates'  => get_field( 'topic_updates' ),
      'topic_events'   => get_field( 'topic_events' ),
      'location'       => get_field( 'location' ),
      'event_location' => get_field( 'location_events' ),
      'current_date'   => current_time( 'Ymd' ),
    )
  )
);
