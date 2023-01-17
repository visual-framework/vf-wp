<?php
 include(locate_template('blocks/vfwp-latest-posts/partials/loops/loops.php', false, false)); 
  
  $ids = array();
  while ($loopPost->have_posts()) : $loopPost->the_post();
  $ids[] = get_the_ID();

?>

<article class="vf-summary vf-summary--news" style="display: block; display: unset;">
<?php 
  if ( has_post_thumbnail() ) {
    the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image vf-u-margin__bottom--400', 'style' => 'max-width: 100%; height: auto;' ) ); 
  }
  else { 
    if ( 'community-blog' == get_post_type() ) { ?>
    <img class="vf-summary__image" src="https://www.embl.org/internal-information/wp-content/uploads/Announcementes-and-updates.jpg" alt="Placeholder" loading="lazy" style="max-width: 100%; height: auto;">
  <?php } } ?>

  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <?php if ($show_excerpt) { ?>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt();?>
  </p>
  <?php } ?>

  <span class="vf-summary__date"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
      title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
      <?php if ($show_location == 1) { 
      $location_terms = get_the_terms( $post->ID, 'embl-location' );
      if( $location_terms ) {
          $locations_list = array(); 
          foreach( $location_terms as $term ) {
            $locations_list[] = '<span style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . esc_html( $term->name ) . '</span>'; }
            echo ' | ' . implode(', ', $locations_list); }?>  </span>
    <?php } ?>    

    </span>
  <?php if ($show_categories == 1) { ?>
  <span class="vf-summary__category">
    <?php echo get_the_category_list(', '); ?>
  </span>
  <?php } ?>
  <?php if ($show_topics == 1) { ?>
  <span class="vf-summary__category">
  <?php if (get_post_type() === 'insites') { 
  $topic_terms = get_the_terms( $post->ID, 'topic' );
  if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<span style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</span>'; }
            echo implode(', ', $topics_list); }?>  </span>
  <?php } ?>
  <?php if (get_post_type() === 'community-blog') { 
  $topic_terms = get_the_terms( $post->ID, 'updates-topic' );
  if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<span style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</span>'; }
            echo implode(', ', $topics_list); }?>  </span>
  <?php } } ?>
</article>

<!--/vf-summary-->
<?php endwhile;?>
<?php wp_reset_postdata(); ?>
