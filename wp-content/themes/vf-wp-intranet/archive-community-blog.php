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
    <input id="search" class="vf-form__input vf-form__input--filter | inputLive" data-jplist-control="textbox-filter"
      data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
      placeholder="Filter by update title" data-clear-btn-id="name-clear-btn">
      <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
              id="total-results-info">Showing <span id="start-counter" class="counter-highlight"></span><span
                id="end-counter" class="counter-highlight"></span> results out of <span id="total-result"
                class="counter-highlight"></span></p>
    </section>
  </div>

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
    <div id="allPosts" data-jplist-group="data-group-1">
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
      ?>
 <!-- no results control -->
 <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-1" data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
        </div>
        <nav id="paging-data" class="vf-pagination" aria-label="Pagination">
          <ul class="vf-pagination__list"></ul>
        </nav>

  </div>
  <div>
        <div class="vf-u-background-color-ui--off-white">
          <div class="vf-content vf-u-padding--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
            href="https://www.embl.org/internal-information/news/">Internal news</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <p class="vf-text-body vf-text-body--3" style="margin-bottom: 6px;"><a
        href="https://www.embl.org/internal-information/communications/how-to-updates/">About this section</a></p>
        <p class="vf-text-body vf-text-body--5 | vf-u-margin__bottom--0">Learn more and publish your updates and announcements.</p>
      </div>
  </div>
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
