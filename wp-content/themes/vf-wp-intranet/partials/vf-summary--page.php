<?php

$title = esc_html(get_the_title());
$redirect_url = get_field('vf_wp_intranet_redirect');

?>
<article class="vf-summary" data-jplist-item>
  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php
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
  <p class="page vf-u-display-none | used-for-filtering">Page</p>
</article>

<!--/vf-summary-->