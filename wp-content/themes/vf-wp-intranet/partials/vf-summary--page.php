<?php

$title = esc_html(get_the_title());
$redirect_url = get_field('vf_wp_intranet_redirect');
$user_id = get_the_author_meta('ID');
$cpid = get_field('cpid');
$orcid = get_field('orcid');
$photo = get_field('photo');
$email = get_field('email');
$position = get_field('positions_name_1', $post->ID);
$outstation = get_field('outstation', $post->ID);
$full_name = get_field('full_name');
$room = get_field('room');
$biography = get_field('biography');
$team_1 = get_field('team_name_1', $post->ID);
$team_2 = get_field('team_name_2');
$team_3 = get_field('team_name_3');
$team_4 = get_field('team_name_4');
$primary_1 = get_field('is_primary_1');
$primary_2 = get_field('is_primary_2');
$primary_3 = get_field('is_primary_3');
$primary_4 = get_field('is_primary_4');
$telephone = get_field('telephone');
?>
<article class="vf-summary" data-jplist-item>

  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php
    if ( get_post_type() ==  'people') {
    echo '<b>People</b> | ' . $position . ' | ' . $team_1 . ' | ' . $outstation;  
  }
  if ( get_post_type() ==  'page') {
    echo '<b>Page</b> | ';  
  }
  if ( get_post_type() ==  'insites') {
    echo '<b>News</b>';  
  }
  if ( get_post_type() ==  'documents') {
    echo '<b>Document</b>';  
  }
  if ( get_post_type() ==  'events') {
    echo '<b>Event</b>';  
  }


    if (has_excerpt()) {
      echo get_the_excerpt();
    }
    else {
    $content = strip_tags(get_the_content());
    if (strlen($content) > 200) {

      // truncate content
      $contentCut = substr($content, 0, 200);
      $endPoint = strrpos($contentCut, ' ');
  
      //if the content doesn't contain any space then it will cut without word basis.
      $content = $endPoint? substr($contentCut, 0, $endPoint) : substr($contentCut, 0);
      $content .= '...';
  }
  echo $content; }?></p>
  <?php if (!empty($redirect_url)) { ?>
  <div class="vf-summary__meta"><a href="<?php echo esc_url($redirect_url); ?>"
      class="vf-summary__author vf-summary__link"><?php echo esc_url($redirect_url); ?></a></div> 
  <?php }
  else { ?>    
  <div class="vf-summary__meta"><a href="<?php the_permalink(); ?>"
      class="vf-summary__author vf-summary__link"><?php the_permalink(); ?></a></div>
  <?php } ?>
  <?php
  if ( get_post_type() == 'page' ) {
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
?>
  
</article>


<!--/vf-summary-->