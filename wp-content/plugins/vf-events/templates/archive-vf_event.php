<?

get_header();

?>
<div class="vf-intro | embl-grid embl-grid--has-centered-content">
  <div><!--empty--></div>
  <div>
    <h1 class="vf-intro__heading">
      <?php echo esc_html(VF_Events::get_archive_title()); ?>
    </h1>
  </div>
  <div><!--empty--></div>
</div>
<!--/vf-intro-->
<div class="vf-intro | embl-grid embl-grid--has-centered-content">
  <div><!--empty--></div>
  <div>
    <?php

    while (have_posts()) {
      the_post();
      $post_id = get_the_ID();

      $start_date = get_field(
        'vf_event_start_date',
        $post_id
      );

      $location = get_field(
        'vf_event_location',
        $post_id
      );

    ?>
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
      <p class="vf-summary__text">
        <?php echo strip_tags(get_the_excerpt()); ?>
      </p>
      <?php if ( ! empty($location)) { ?>
      <p class="vf-summary__location">
        <?php echo esc_html($location); ?>
      </p>
      <?php } ?>
    </article>
    <!--/vf-summary-->
    <?php

      // Output divider after all but last post
      if ($wp_query->current_post < $wp_query->post_count - 1) {
        echo '<hr class="vf-divider">';
      }

    } // while (have_posts())
    ?>

    <?php

    $pagination = VF_Events::get_archive_pages();
    if ($pagination['previous'] || $pagination['next']) {

    ?>
    <nav class="vf-pagination" aria-label="<?php esc_attr_e('Pagination', 'vfwp'); ?>">
      <ul class="vf-pagination__list">
        <?php if ($pagination['previous']) { ?>
        <li class="vf-pagination__item vf-pagination__item--previous-page">
          <a href="<?php echo esc_url($pagination['previous']); ?>" class="vf-pagination__link">
            <?php
            printf(
              '%1$s<span class="vf-u-sr-only"> %2$s</span>',
              _x('Previous', 'events pagination', 'vfwp'),
              _x('page', 'events pagination', 'vfwp')
            );
            ?>
          </a>
        </li>
        <?php } ?>
        <?php if ($pagination['next']) { ?>
        <li class="vf-pagination__item vf-pagination__item--next-page">
          <a href="<?php echo esc_url($pagination['next']); ?>" class="vf-pagination__link">
            <?php
            printf(
              '%1$s<span class="vf-u-sr-only"> %2$s</span>',
              _x('Next', 'events pagination', 'vfwp'),
              _x('page', 'events pagination', 'vfwp')
            );
            ?>
          </a>
        </li>
        <?php } ?>
      </ul>
    </nav>
    <!--/vf-pagination-->
    <?php } ?>

    <?php
    // View alternate past/upcoming archives
    $is_past = VF_Events::is_past_archive();
    $alt_url = VF_Events::get_archive_link( ! $is_past);
    $alt_title = sprintf(
      _x('View %1$s', 'event archive link', 'vfwp'),
      VF_Events::get_archive_title( ! $is_past)
    );
    ?>
    <p class="vf-text-body">
      <a href="<?php echo esc_url($alt_url); ?>">
        <?php echo esc_html($alt_title); ?>
      </a>
    </p>

  </div>
  <div><!--empty--></div>
</div>
<!--/embl-grid-->
<?

get_footer();

?>
