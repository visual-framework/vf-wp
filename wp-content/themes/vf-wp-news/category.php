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

$category_name = single_cat_title("", false);
?>
<section class="vf-u-margin__bottom--400">
  <div class="vf-news-container vf-news-container--featured | vf-u-margin__bottom--400">
    <div>
      <h2 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--600" style="font-weight: 400;">
      <?php echo esc_html($category_name) ?></h2>
    </div>
    <div class="vf-news-container__content vf-grid vf-grid__col-4">
    <?php 
    $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $popular = new WP_Query(array(
      'paged' => $page,
      'posts_per_page'=> 12, 
      'cat' => get_query_var('cat'),         
      'meta_query'    => array(
        'relation' => 'OR',
        array(
            'key'       => 'field_target_display',
            'value'     => 'embl-ebi',
            'compare' => 'NOT LIKE'
        ),
        array(
          'key' => 'field_target_display',
          'compare' => 'NOT EXISTS'
        )
    ) 
));
        while ($popular->have_posts()) : $popular->the_post();
        include(locate_template('partials/vf-summary--news.php', false, false)); ?>
        <?php endwhile; wp_reset_postdata(); ?>    </div>
    <div class="vf-grid" style="margin: 4%">
      <?php vf_pagination(); ?>
    </div>
  </div>

  <div class="vf-news-container vf-news-container--featured | vf-u-background-color-ui--off-white | vf-u-margin__bottom--100 | vf-u-padding__top--400 | vf-u-fullbleed">
    <h2 class="vf-section-header__heading vf-u-margin__bottom--400">Popular</h2>
  <div class="vf-news-container__content vf-grid vf-grid__col-4">
        <?php $popular = new WP_Query(array(
          'posts_per_page'=>4, 
          'meta_key'=>'popular_posts', 
          'orderby'=>'meta_value_num', 
          'order'=>'DESC', 
          'cat' => get_query_var('cat'),         
          'meta_query'    => array(
            'relation' => 'OR',
            array(
                'key'       => 'field_target_display',
                'value'     => 'embl-ebi',
                'compare' => 'NOT LIKE'
            ),
            array(
              'key' => 'field_target_display',
              'compare' => 'NOT EXISTS'
            )
      ) 
));
        while ($popular->have_posts()) : $popular->the_post();
        include(locate_template('partials/vf-summary--news.php', false, false)); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
  </div>

  <?php include(locate_template('partials/embletc-container.php', false, false)); ?>

  <?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>

<?php get_footer(); ?>
