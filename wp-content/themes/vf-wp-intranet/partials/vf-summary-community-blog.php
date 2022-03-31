<?php
$topic_terms = get_field('cb_topic');
$locations = get_field('cb_embl_location');

?>
<article class="vf-summary vf-summary--news" data-jplist-item>
  <span class="vf-summary__date"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
      title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
  <h3 class="vf-summary__title" <?php if (is_front_page()) { echo 'style="font-size: 18px;"'; } ?>>
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <?php if (is_post_type_archive('community-blog')) { ?>
    <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
  <?php } ?>
  <p class="vf-summary__text | vf-u-margin__top--200">
    <span class="vf-summary__category">
    <?php if (($topic_terms)) { ?>
      <span class="topic">
        <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<a class="vf-link ' . esc_attr( $term->slug ) . '"style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
            echo implode(', ', $topics_list); }?>
      </span>
      <?php } 
    if (($locations) && ($topic_terms))
    { ?>
      <span style="color: #000">&nbsp;|&nbsp;</span>
      <?php
    }
    if (($locations)) { ?>
      <span class="vf-u-text-color--grey | location" style="text-transform: none;">
        <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
      </span>
    </span>
  </p>
  <?php } ?>
</article>
