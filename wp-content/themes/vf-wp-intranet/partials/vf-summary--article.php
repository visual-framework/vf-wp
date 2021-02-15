<?php

$title = esc_html(get_the_title());
$topic_terms = get_field('topic');


?>
<article class="vf-summary vf-summary--news">
  <span class="vf-summary__date">
    <time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  </span>
  <h2 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <?php the_post_thumbnail('full', array(
    'class' => 'vf-summary__image', 
    'style' => 'width: 180px; height: auto; border: 1px solid #d0d0ce')); ?>
  <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
  <p class="vf-summary__meta">
      <?php 
      if( $topic_terms ) {
        $topics_list = array(); 
      foreach( $topic_terms as $term ) {
        $topics_list[] = '<a class="vf-link"  href="' . esc_url(get_term_link( $term )) . '" class="vf-link">' . esc_html( $term->name ) . '</a>'; }
      echo implode(', ', $topics_list); }?>
      </p>

</article>
<!--/vf-summary-->