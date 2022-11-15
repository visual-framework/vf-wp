<?php

$title = esc_html(get_the_title());
$redirect_url = get_field('vf_wp_intranet_redirect');
$position = get_field('positions_name_1', $post->ID);
$outstation = get_field('outstation', $post->ID);
$team_1 = get_field('team_name_1', $post->ID);

?>
<article class="vf-summary" data-jplist-item>

  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php
    // display the post type
    if ( get_post_type() ==  'people') {
    echo '<b>People</b> | ' . $position . ' | ' . $team_1 . ' | ' . $outstation;  
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
  if ((get_post_type() == 'page') || (get_post_type() == 'teams')) {
    echo '<b>Page</b>';
  }
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
  }
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
  echo '<p class="vf-summary__author">' . esc_html__($uri ) . '</p>'; ?></div>
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
?>
  
</article>


<!--/vf-summary-->