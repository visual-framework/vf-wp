<?php
include( locate_template( 'blocks/vfwp-latest-posts/partials/loops/loops.php', false, false ) );

if ( ! isset( $loopPost ) || ! ( $loopPost instanceof WP_Query ) || ! $loopPost->have_posts() ) {
  return;
}

$ids = array();
while ( $loopPost->have_posts() ) :
  $loopPost->the_post();
  $ids[] = get_the_ID();
  $post_id = get_the_ID();
  $current_post_type = get_post_type();
  $event_data = 'events' === $current_post_type ? vfwp_latest_posts_get_event_display_data( $post_id ) : null;
?>

<article class="vf-summary vf-summary--news">
  <?php if ( 'events' === $current_post_type && ! empty( $event_data['has_date'] ) ) : ?>
  <p class="vf-summary__date" data-eventtime="<?php echo esc_attr( $event_data['sort_key'] ); ?>">
    <?php echo esc_html( $event_data['display_date'] ); ?>
    &nbsp;&nbsp;
    <span class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--100" style="text-transform: none;">
      <a href="<?php echo esc_url( $event_data['calendar_url'] ); ?>" target="_blank" rel="nofollow">Add to calendar</a>
    </span>
  </p>
  <?php elseif ( 'events' !== $current_post_type ) : ?>
  <span class="vf-summary__date" title="<?php the_time( 'c' ); ?>" datetime="<?php the_time( 'c' ); ?>">
    <?php the_time( get_option( 'date_format' ) ); ?>
  </span>
  <?php endif; ?>

  <?php if ( 1 == $show_image ) : ?>
    <?php if ( has_post_thumbnail() ) : ?>
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); ?>
    <?php elseif ( 'community-blog' === $current_post_type ) : ?>
  <img class="vf-summary__image"
    src="https://www.embl.org/internal-information/wp-content/uploads/Announcementes-and-updates.jpg" alt="Placeholder"
    loading="lazy">
    <?php endif; ?>
  <?php endif; ?>

  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html( get_the_title() ); ?></a>
  </h3>

  <?php if ( $show_excerpt ) : ?>
  <p class="vf-summary__text">
    <?php echo wp_kses_post( get_the_excerpt() ); ?>
  </p>
  <?php endif; ?>

  <?php if ( 1 == $show_categories ) : ?>
  <p class="vf-summary__text">
    <span class="vf-summary__category">
      <?php echo get_the_category_list( ', ' ); ?>
    </span>
  </p>
  <?php endif; ?>

  <?php if ( 1 == $show_topics ) : ?>
  <p class="vf-summary__text | vf-u-margin__bottom--0">
    <span class="vf-summary__meta">
      <?php
      if ( 'insites' === $current_post_type ) {
        echo vfwp_latest_posts_get_term_links( $post_id, 'topic' );
      } elseif ( 'community-blog' === $current_post_type ) {
        echo vfwp_latest_posts_get_term_links( $post_id, 'updates-topic' );
      } elseif ( 'events' === $current_post_type ) {
        echo vfwp_latest_posts_get_term_links( $post_id, 'events-topic' );
      }
      ?>
    </span>
  </p>
  <?php endif; ?>

  <?php if ( 1 == $show_location ) : ?>
  <p class="vf-summary__text">
    <?php if ( 'community-blog' === $current_post_type || 'insites' === $current_post_type ) : ?>
      <?php $location_list = vfwp_latest_posts_get_term_names( $post_id, 'embl-location', true ); ?>
      <?php if ( ! empty( $location_list ) ) : ?>
    <span class="vf-text-body vf-text-body--5 location vf-u-margin__top--0"><span style="font-weight: 500;">EMBL site:</span>
      <?php echo esc_html( implode( ', ', $location_list ) ); ?>
    </span>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ( 'events' === $current_post_type ) : ?>
      <?php $event_locations_list = vfwp_latest_posts_get_term_names( $post_id, 'event-location' ); ?>
      <?php if ( ! empty( $event_locations_list ) ) : ?>
    <span class="vf-text-body vf-text-body--5 location vf-u-margin__top--0">
      <?php echo esc_html( implode( ', ', $event_locations_list ) ); ?>
    </span>
      <?php endif; ?>
    <?php endif; ?>
  </p>
  <?php endif; ?>
</article>
<!--/vf-summary-->
<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
