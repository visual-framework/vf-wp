<?php

$title = esc_html(get_the_title());
$redirect_url = get_field('vf_wp_intranet_redirect');
$position = get_field('positions_name_1', $post->ID);
$outstation = get_field('outstation', $post->ID);
$team_1 = get_field('team_name_1', $post->ID);
$team_2 = get_field('team_name_2', $post->ID);
$team_3 = get_field('team_name_3', $post->ID);
$team_4 = get_field('team_name_4', $post->ID);
$is_primary_1 = get_field('is_primary_1', $post->ID);
$is_primary_2 = get_field('is_primary_2', $post->ID);
$is_primary_3 = get_field('is_primary_3', $post->ID);
$is_primary_4 = get_field('is_primary_4', $post->ID);
$training_date = get_field('vf-wp-training-start_date', $post->ID);
$training_date_fromatted = DateTime::createFromFormat('Ymd', $training_date);
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

if (is_array($training_location) || is_object($training_location)) {
$loc_list = [];
foreach( $training_location as $loc ) { 
  $loc_list[] = $loc->name; 
  }
}

?>
<article class="vf-summary" data-jplist-item>

  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
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
    echo '<b>Training</b> | ' . $training_category . ' | ' . implode(', ', $loc_list) . ' | ' . $training_date_fromatted->format('j F Y') ;  
  }
  if ((get_post_type() == 'page') || (get_post_type() == 'teams')) {
    echo '<b>Page</b>';
  }
  if ( get_post_type() !=  'training') {
    if (has_excerpt()) {
      echo ' | ' . get_the_excerpt();
      }
    else {
    $content = strip_tags(get_the_content());
    if ($content != '') {
    if (strlen($content) > 200) {

      // truncate content
      $contentCut = substr($content, 0, 200);
      $endPoint = strrpos($contentCut, ' ');
  
      //if the content doesn't contain any space then it will cut without word basis.
      $content = $endPoint? substr($contentCut, 0, $endPoint) : substr($contentCut, 0);
      $content .= '...';
  }
  echo ' | ' . $content; 
    }
  } }
  ?>
  </p>

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