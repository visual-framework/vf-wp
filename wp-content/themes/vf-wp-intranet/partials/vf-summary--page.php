<?php

$title = esc_html(get_the_title());

?>
<article class="vf-summary">
  <h2 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <p class="vf-summary__text">
    <?php
    $content = strip_tags(get_the_content());
    if (strlen($content) > 200) {

      // truncate content
      $contentCut = substr($content, 0, 200);
      $endPoint = strrpos($contentCut, ' ');
  
      //if the content doesn't contain any space then it will cut without word basis.
      $content = $endPoint? substr($contentCut, 0, $endPoint) : substr($contentCut, 0);
      $content .= '...';
  }
  echo $content; ?></p>
  <div class="vf-summary__meta"><a href="<?php the_permalink(); ?>"
      class="vf-summary__author vf-summary__link"><?php the_permalink(); ?></a></div>

</article>


<!--/vf-summary-->