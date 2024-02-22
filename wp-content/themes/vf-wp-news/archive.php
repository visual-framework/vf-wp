<?php

get_header();

$title = get_the_title(get_option('page_for_posts'));

if (is_search()) {
  $title = sprintf(__('Search: %s', 'vfwp'), get_search_query());
} elseif (is_category()) {
  $title = sprintf(__('Category: %s', 'vfwp'), single_term_title('', false));
} elseif (is_tag()) {
  $title = sprintf(__('Tag: %s', 'vfwp'), single_term_title('', false));
} elseif (is_author()) {
  $title = sprintf(__('Author: %s', 'vfwp'), get_the_author_meta('display_name'));
} elseif (is_year()) {
  $title = sprintf(__('Year: %s', 'vfwp'), get_the_date('Y'));
} elseif (is_month()) {
  $title = sprintf(__('Month: %s', 'vfwp'), get_the_date('F Y'));
} elseif (is_day()) {
  $title = sprintf(__('Day: %s', 'vfwp'), get_the_date());
} elseif (is_post_type_archive()) {
  $title = sprintf(__('Type: %s', 'vfwp'), post_type_archive_title('', false));
} elseif (is_tax()) {
  $tax = get_taxonomy(get_queried_object()->taxonomy);
  $title = sprintf(__('%s Archives:', 'vfwp'), $tax->labels->singular_name);
}

?>
<section class="vf-u-margin__bottom--200">
      <div>
        <h1 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--600" style="font-weight: 400;">
          <?php single_tag_title(); ?></h1>
      </div>
      <div class="vf-news-container vf-news-container--featured">
        <div class="vf-news-container__content vf-grid vf-grid__col-4">
          <?php if ( have_posts() ) : while ( have_posts() ) : the_post();?>
          <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
          <?php endwhile; endif; ?>
        </div>
        <div class="vf-grid" style="margin: 4%"> <?php vf_pagination();
      ?>
        </div>
    </div>
</section>

<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>


<?php get_footer(); ?>
