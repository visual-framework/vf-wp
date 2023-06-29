<?php
$topic_terms = get_field('topic');
$locations = get_field('embl_location');
$customDateSorting = get_the_time('Ymd');
?>
<article class="vf-summary vf-summary--news" data-jplist-item>
  <span class="vf-summary__date" data-eventtime="<?php echo $customDateSorting; ?>"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
      title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <p class="vf-summary__meta | vf-u-margin__bottom--0">
    <?php if (($topic_terms)) { ?>
    <span class="topic">
      <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<a class="vf-link ' . esc_attr( $term->slug ) . '"style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
            echo implode(', ', $topics_list); }?>
    </span>
  </p>

  <?php } 
    if (($locations)) { ?>
  <p class="vf-text-body vf-text-body--5
 location vf-u-margin__top--0">
    <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
  </p>
  <?php } ?>
</article>
