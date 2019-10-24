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
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <h1 class="vf-text vf-text-heading--1"><?php echo esc_html($title); ?></h1>
      <?php
      while (have_posts()) {
        the_post();
        include(locate_template('partials/vf-summary--article.php', false, false));
        if ( ! vf_last_post()) {
          echo '<hr class="vf-divider">';
        }
      }
      vf_pagination();
      ?>
    </main>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>
    <aside class="vf-inlay__content--additional">
      <?php vf_sidebar('sidebar-blog'); ?>
    </aside>
    <?php } ?>
  </div>
</section>
<?php

get_footer();

?>
