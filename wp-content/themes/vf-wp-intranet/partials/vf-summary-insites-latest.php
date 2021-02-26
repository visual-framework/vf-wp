<?php
$topic_terms = get_field('topic');
?>
<article class="vf-summary vf-summary--news">
  <span class="vf-summary__date"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
      title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <p class="vf-summary__text">
    <span class="vf-summary__category">
      <?php 
      if( $topic_terms ) {
        $topics_list = array(); 
      foreach( $topic_terms as $term ) {
        $topics_list[] = '<a class="vf-link" style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '" class="vf-link">' . esc_html( $term->name ) . '</a>'; }
      echo implode(', ', $topics_list); }?>
    </span>
    <?php echo get_the_excerpt(); ?>
  </p>
</article>
