<?php

$search_terms = array();
if (is_search() && function_exists('vfwp_search_get_query_terms')) {
  $search_terms = vfwp_search_get_query_terms(get_search_query(false));
}

$raw_title = get_the_title();
$title = !empty($search_terms) && function_exists('vfwp_search_highlight_text')
  ? vfwp_search_highlight_text($raw_title, $search_terms)
  : esc_html($raw_title);
$redirect_url = get_field('vf_wp_intranet_redirect');
$position = get_field('positions_name_1', $post->ID);
$outstation = get_field('outstation', $post->ID);
$type = get_field('vf-wp-training-training_type', $post->ID);
$team_1 = get_field('team_name_1', $post->ID);
$team_2 = get_field('team_name_2', $post->ID);
$team_3 = get_field('team_name_3', $post->ID);
$team_4 = get_field('team_name_4', $post->ID);
$is_primary_1 = get_field('is_primary_1', $post->ID);
$is_primary_2 = get_field('is_primary_2', $post->ID);
$is_primary_3 = get_field('is_primary_3', $post->ID);
$is_primary_4 = get_field('is_primary_4', $post->ID);
$training_date = get_field('vf-wp-training-start_date', $post->ID);
$training_date_formatted = false;
if (is_string($training_date) && $training_date !== '') {
  $training_date_formatted = DateTime::createFromFormat('Ymd', $training_date);
}
$training_category = get_field('vf-wp-training-category', $post->ID);
$training_location = get_the_terms( $post->ID , 'event-location' );
$teamArray = array(
  array(
"team" => $team_1,
"isPrimary" => $is_primary_1,
),
  array(
"team" => $team_2,
"isPrimary" => $is_primary_2,
),
  array(
"team" => $team_3,
"isPrimary" => $is_primary_3,
  ),
  array(
"team" => $team_4,
"isPrimary" => $is_primary_4
),
);
$key_values = array_column($teamArray, 'isPrimary'); 
array_multisort($key_values, SORT_DESC, $teamArray);
$teamArray = array_map('array_filter', $teamArray);
$teamArray = array_filter($teamArray);

$loc_list = [];
if (!empty($training_location)) {
if (is_array($training_location) || is_object($training_location)) {
foreach( $training_location as $loc ) { 
  $loc_list[] = $loc->name; 
  }
}
}

$content_snippet = '';
if (!empty($search_terms) && function_exists('vfwp_search_get_highlighted_content_snippet')) {
  $content_snippet = vfwp_search_get_highlighted_content_snippet(get_post_field('post_content', $post->ID), $search_terms, 200);
}

$summary_text = '';
if (get_post_type() !== 'training') {
  if ($content_snippet !== '') {
    $summary_text = $content_snippet;
  } elseif (has_excerpt()) {
    $excerpt = get_the_excerpt();
    $summary_text = !empty($search_terms) && function_exists('vfwp_search_highlight_text')
      ? vfwp_search_highlight_text($excerpt, $search_terms)
      : esc_html($excerpt);
  } else {
    $content = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags(get_the_content())));
    if ($content !== '') {
      if (function_exists('mb_strlen')) {
        if (mb_strlen($content, 'UTF-8') > 200) {
          $content = rtrim(mb_substr($content, 0, 200, 'UTF-8')) . '...';
        }
      } else {
        if (strlen($content) > 200) {
          $content = rtrim(substr($content, 0, 200)) . '...';
        }
      }
      $summary_text = !empty($search_terms) && function_exists('vfwp_search_highlight_text')
        ? vfwp_search_highlight_text($content, $search_terms)
        : esc_html($content);
    }
  }
}

?>
<article class="vf-summary" data-jplist-item>

  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo wp_kses($title, array('mark' => array())); ?></a>
  </h2>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php
    // display the post type
    if ( get_post_type() ==  'people') {
    echo '<b>People</b> | ' . $position . ' | ' . $teamArray[0]['team'] . ' | ' . $outstation;  
  }

  if ( get_post_type() ==  'insites') {
    echo '<b>News</b>';  
  }
  if ( get_post_type() ==  'documents') {
    echo '<b>Document</b>';  
  }
  if ( get_post_type() ==  'community-blog') {
    echo '<b>Announcements and updates</b>';  
  }
  if ( get_post_type() ==  'events') {
    echo '<b>Event</b>';  
  }
  if ( get_post_type() ==  'training') {
    if ($type === 'live') {
    echo '<b>Training</b> | ' . $training_category . ' | ' . implode(', ', $loc_list);
    if ($training_date_formatted instanceof DateTime) {
      echo ' | ' . $training_date_formatted->format('j F Y');
    }
    }
    else if ($type === 'on-demand'){
      echo '<b>Training</b> | ' . $training_category . ' | On-demand';
    }
  }
  if ((get_post_type() == 'page') || (get_post_type() == 'teams')) {
    echo '<b>Page</b>';
  }
  if ($summary_text !== '') {
    echo ' | ' . wp_kses($summary_text, array('mark' => array()));
  }
  ?>
  </p>

  <?php if (get_post_type() === 'training' && $content_snippet !== '') : ?>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php echo wp_kses($content_snippet, array('mark' => array())); ?>
  </p>
  <?php endif; ?>

  <?php 
  if ( (get_post_type() == 'page') || (get_post_type() == 'teams')) {
  if (!empty($redirect_url)) { ?>
  <div class="vf-summary__meta"><p class="vf-summary__author"><?php echo esc_url($redirect_url); ?></p></div> 
  <?php }
  else { ?>    
  <div class="vf-summary__meta"><?php 
  $uri = get_page_uri(); 
  echo '<p class="vf-summary__author | vf-u-margin__bottom--0">/' . esc_html__($uri ) . '</p>'; ?></div>
  <?php } }?>
  <?php
  if ((get_post_type() == 'page') || (get_post_type() == 'teams'))  {
    echo '<p class="page vf-u-display-none | used-for-filtering">Page</p>';
}
  elseif ( get_post_type() ==  'people') {
    echo '<p class="people vf-u-display-none | used-for-filtering">People</p>';
}
  elseif ( get_post_type() ==  'documents') {
    echo '<p class="documents vf-u-display-none | used-for-filtering">Documents</p>';
}
  elseif ( get_post_type() ==  'insites') {
    echo '<p class="news vf-u-display-none | used-for-filtering">News</p>';
}
  elseif ( get_post_type() ==  'community-blog') {
    echo '<p class="announcements vf-u-display-none | used-for-filtering">Announcements</p>';
}
  elseif ( get_post_type() ==  'events') {
    echo '<p class="events vf-u-display-none | used-for-filtering">Events</p>';
}
  elseif ( get_post_type() ==  'training') {
    echo '<p class="training vf-u-display-none | used-for-filtering">Training</p>';
}
?>
  
</article>


<!--/vf-summary-->
