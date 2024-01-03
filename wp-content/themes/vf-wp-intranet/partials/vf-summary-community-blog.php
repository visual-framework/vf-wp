<?php
$topic_terms = get_field('cb_topic');
$locations = get_field('cb_embl_location');

?>

<article class="vf-summary vf-summary--has-image vf-u-margin__bottom--0">
  <a href="<?php the_permalink(); ?>" class="vf-summary__link">
  <?php 
  if ( has_post_thumbnail() ) {
  the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image vf-summary__image--thumbnail' ) ); 
  }
  else { ?>
  <img class="vf-summary__image vf-summary__image--thumbnail"
    src="https://www.embl.org/internal-information/wp-content/uploads/Announcementes-and-updates.jpg" alt="Placeholder"
    loading="lazy">
  <?php } ?>
  </a>
  <p class="vf-summary__date  vf-u-margin__bottom--100">
      <span class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--0"><time class="" style="margin-left: 0;"
          title="<?php the_time('c'); ?>"
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
      <?php if (($topic_terms)) { ?> | 
<span class="vf-text-body vf-text-body--5 | topic | vf-u-margin__bottom--0 | vf-u-margin__top--200">
        <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<a class="vf-link ' . esc_attr( $term->slug ) . '"style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
            echo implode(', ', $topics_list); }?>
      </span>
      <?php } ?>
    </p>
  <h3 class="vf-summary__title" style="font-size: 18px;">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
    <?php echo esc_html(get_the_title()); ?>    </a>
  </h3>
</article>
