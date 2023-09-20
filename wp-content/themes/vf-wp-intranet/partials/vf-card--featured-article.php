<?php
$title = esc_html(get_the_title());
$topic_terms = get_field('topic');




?>
<div class="vf-card vf-card--very-easy | vf-u-margin__bottom--400">
  <a style="display: flex;" href="<?php the_permalink(); ?>">
    <?php the_post_thumbnail( 'full', array( 'class' => 'vf-card__image' ) ); ?>
  </a>
  <div class="vf-card__content">
    <h3 class="vf-card__title">
      <a href="<?php the_permalink(); ?>" class="vf-link"><?php echo $title; ?></a>
    </h3>
    <p class="vf-card__text | vf-u-margin__bottom--100">
    <?php echo get_the_excerpt(); ?>
    </p>
    <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0; margin-top: 10px;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
      <p class="vf-summary__meta | vf-u-margin__top--100">
      <?php 
      if( $topic_terms ) {
        $topics_list = array(); 
      foreach( $topic_terms as $term ) {
        $topics_list[] = '<a class="vf-link"  href="' . esc_url(get_term_link( $term )) . '" class="vf-link">' . esc_html( $term->name ) . '</a>'; }
      echo implode(', ', $topics_list); }?>
      </p>

  </div>
</div>