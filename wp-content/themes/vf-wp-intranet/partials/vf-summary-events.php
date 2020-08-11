<?php
$post_id = get_the_ID();
      $start_date = get_field(
        'vf_event_start_date',
        $post_id
      );
      $location = get_field(
        'vf_event_location',
        $post_id
      );
      $event_type = get_field('vf_event_event_type'); ?>

      <article class="vf-summary vf-summary--event">
        <?php if ( ! empty($start_date)) { ?>
        <p class="vf-summary__date">
          <?php echo esc_html($start_date); ?>
        </p>
        <?php } ?>
        <h3 class="vf-summary__title">
          <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
            <?php the_title(); ?>
          </a>
        </h3>
        <?php if ( ! empty(get_the_excerpt())) { ?>
        <p class="vf-summary__text">
          <?php echo strip_tags(get_the_excerpt()); ?>
        </p>
        <?php } ?>
        <p class="vf-summary__text">
          <?php echo esc_html($event_type); ?>
        </p>
        <?php if ( ! empty($location)) { ?>
        <p class="vf-summary__location">
          <?php echo esc_html($location); ?>
        </p>
        <?php } ?>
      </article>
      <!--/vf-summary-->