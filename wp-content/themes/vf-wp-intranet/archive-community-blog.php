<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();
?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      Updates and announcements
    </h1>
  </div>
</section>


<section
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div>
  </div>
  <div class="vf-form__item">
    <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
      data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
      placeholder="Filter by update title" data-clear-btn-id="name-clear-btn">
  </div>
  <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
          id="total-results-info">Showing <span id="start-counter" class="counter-highlight"></span><span
            id="end-counter" class="counter-highlight"></span> results out of <span id="total-result"
            class="counter-highlight"></span></p>
</section>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/topic-updates-filter.php', false, false)); ?>
  </div>
  <div>
    <div>
    <?php
// Get sticky posts
$sticky_posts = get_option('sticky_posts');

$displayed_posts = array(); // Array to keep track of displayed posts
// Query for sticky posts
$args_sticky = array(
    'post_type' => 'community-blog',
    'posts_per_page' => 2,
    'post__in' => $sticky_posts,
    'ignore_sticky_posts' => 0,
);


if (!empty($sticky_posts)) {
    $sticky_query = new WP_Query($args_sticky);

    // Display sticky posts
    if ($sticky_query->have_posts()) {
        while ($sticky_query->have_posts()) : $sticky_query->the_post();
            $displayed_posts[] = get_the_ID();
            include(locate_template('partials/vf-summary-community-blog-featured.php', false, false));
        endwhile;
    } else {
        echo 'Sticky query did not return any posts.';
    }
    wp_reset_postdata();
}
?>

            </div>
    <div data-jplist-group="data-group-1">
      <?php

        // Query for regular posts
        $args_main = array(
          'post_type' => 'community-blog',
          'posts_per_page' => -1,
          'post__not_in' => $displayed_posts, // Exclude already displayed posts
          'ignore_sticky_posts' => 1,
        );
        $main_query = new WP_Query($args_main);

        while ($main_query->have_posts()) : $main_query->the_post();
          include(locate_template('partials/vf-summary-community-blog-featured.php', false, false));
        endwhile;
        wp_reset_postdata();
      ?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching posts found
        </p>
      </article>
    </div>

    <?php include(locate_template('partials/paging-controls.php', false, false)); ?>

  </div>
  <div class="vf-stack vf-stack--400">
    <article class="vf-card vf-card--brand vf-card--bordered">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="https://www.embl.org/internal-information/news/">Internal news<svg aria-hidden="true"
              class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg>
          </a></h3>
      </div>
    </article>

    <article class="vf-card vf-card--brand vf-card--striped">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link"
            href="https://www.embl.org/internal-information/communications/how-to-updates/">About this section
            <svg aria-hidden="true"
              class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="currentColor" fill-rule="nonzero"></path>
            </svg>
          </a></h3>
          <p class="vf-card__text">Learn more and publish your updates and announcements.</p>
      </div>
    </article>
  </div>
</section>


<script type="text/javascript">
    jplist.init({
      deepLinking: true
    });
</script>

<?php  include(locate_template('partials/pagination/pagination.php', false, false)); ?>
<?php

get_footer();

?>
