<?php
//Relevansii customization

add_filter( 'post_limits', 'rlv_postsperpage' );
function rlv_postsperpage( $limits ) {
	if ( is_search() ) {
		global $wp_query;
		$wp_query->query_vars['posts_per_page'] = 25;
	}
	return $limits;
}

add_filter( 'relevanssi_match', 'rlv_customfield_boost' );
function rlv_customfield_boost( $match ) {
	if ( $match->customfield > 0 ) {
		$match->weight *= 200;
	}
	return $match;
}

add_filter( 'relevanssi_match', 'rlv_boost_excerpts' );
function rlv_boost_excerpts( $match ) {
	if ( $match->excerpt > 0 ) {
		$match->weight *= 150;
	}
	return $match;
}

// * Enables the `sentence` parameter in Releavnssi queries.
add_filter( 'relevanssi_modify_wp_query', 'rlv_force_sentence' );
/**
 *
 * @param WP_Query $query The query object.
 *
 * @return WP_Query The modified query object.
 */
function rlv_force_sentence( $query ) {
  $query->set( 'sentence', true );
  return $query;
}

// * Triggers a fallback search when nothing is found.
add_filter( 'relevanssi_fallback', 'rlv_phrase_fallback' );
/**
 *
 * @param array $args The search arguments in the index 'args'.
 *
 * @return array The search arguments, with the results from
 * relevanssi_search() in 'return'.
 */
function rlv_phrase_fallback( $args ) {
  $args['args']['sentence'] = false;
    
  // Disable this filter to avoid an infinite loop.
  remove_filter( 'relevanssi_fallback', 'rlv_phrase_fallback' );
  
  // Run a search with the modified arguments.
  $return = relevanssi_search( $args['args'] );
  
  // Re-enable the filter.
  add_filter( 'relevanssi_fallback', 'rlv_phrase_fallback' );
    
  $args['return'] = $return;
  return $args;
}

// restrict some cpt only for the administrator role
function remove_menu_items() {
  if( !current_user_can( 'administrator' ) ):
      remove_menu_page( 'edit.php?post_type=teams' );
      remove_menu_page( 'edit.php?post_type=people' );
      remove_menu_page( 'edit.php?post_type=insites' );
  endif;
}
add_action( 'admin_menu', 'remove_menu_items' );

// Filters the Did you mean suggestion URL.

add_filter( 'relevanssi_didyoumean_url', 'rlv_add_dym_parameters' );
function rlv_add_dym_parameters( $url ) {
  return add_query_arg( 'post_type', 'any', $url );
}

// To help populate the cache in case the AJAX action fails, Relevanssi has a function that you can run to populate the cache. The function is relevanssi_update_words_option(), and you can call it like this:
  
  if ( function_exists( 'relevanssi_update_words_option' ) ) {
    if ( is_user_logged_in() ) {
     relevanssi_update_words_option();
  } }
  ?>