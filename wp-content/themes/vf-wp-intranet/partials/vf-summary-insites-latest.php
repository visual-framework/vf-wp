<?php
$topic_terms = get_field('topic');
$locations = get_field('embl_location');

?>
<article class="vf-summary vf-summary--news" data-jplist-item>
  <span class="vf-summary__date"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
      title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <p class="vf-summary__meta | vf-u-margin__bottom--200">
    <?php if (($topic_terms)) { ?>
    <span class="topic">
      <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<span style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</span>'; }
            echo implode(', ', $topics_list); }?>
    </span>
    <?php } 
    if (($locations) && ($topic_terms))
    { ?>
    <span style="color: #000">&nbsp;|&nbsp;</span>
    <?php
    }
    if (($locations)) { ?>
    <span class="vf-u-text-color--grey | location">
      <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
    </span>
  </p>
  <?php } ?>
</article>
