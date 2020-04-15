<?

get_header();

// Use post type label for default title
// $post_type_object = get_post_type_object(
//   VF_Events::type()
// );
// $title = $post_type_object->label;

$title = __('Upcoming Events', 'vfwp');

$upcoming_title = get_field('vf_event_upcoming_title', 'options');
$upcoming_title = trim($upcoming_title);

$past_title = get_field('vf_event_past_title', 'options');
$past_title = trim($past_title);

if ( ! empty($upcoming_title)) {
  $title = $upcoming_title;
}

// Get pagination vars and URLs
global $wp_query;
$found = intval($wp_query->found_posts);
$limit = $wp_query->get(
  'posts_per_page',
  get_option('posts_per_page')
);
$pages = intval(
  ceil($found / $limit)
);
$next_url = false;
$prev_url = false;
if (
  preg_match(
    '#href="([^"]*)"#is',
    get_next_posts_link('', $pages),
    $matches)
) {
  $next_url = trim($matches[1]);
}
if (
  preg_match(
    '#href="([^"]*)"#is',
    get_previous_posts_link('', $pages),
    $matches)
) {
  $prev_url = trim($matches[1]);
}

?>
<div class="vf-intro | embl-grid embl-grid--has-centered-content">
  <div><!--empty--></div>
  <div>
    <h1 class="vf-intro__heading">
      <?php echo esc_html($title); ?>
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

    <?php if ($prev_url || $next_url) { ?>
    <nav class="vf-pagination" aria-label="<?php esc_attr_e('Pagination', 'vfwp'); ?>">
      <ul class="vf-pagination__list">
        <?php if ($prev_url) { ?>
        <li class="vf-pagination__item vf-pagination__item--previous-page">
          <a href="<?php echo esc_url($prev_url); ?>" class="vf-pagination__link">
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
        <?php if ($next_url) { ?>
        <li class="vf-pagination__item vf-pagination__item--next-page">
          <a href="<?php echo esc_url($next_url); ?>" class="vf-pagination__link">
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

  </div>
  <div><!--empty--></div>
</div>
<!--/embl-grid-->
<?

get_footer();

?>
