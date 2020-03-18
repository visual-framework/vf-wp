<?php

get_template_part('partials/header');

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
<section class="vf-inlay | vf-u-margin__bottom--md ">
  <div class="vf-inlay__content vf-u-background-color-ui--off-white | vf-u-padding__top--md">
    <main class="vf-inlay__content--full-width | vf-u-margin__bottom--0">
      <div>
        <h2 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--xl" style="font-weight: 400;">
        <?php echo esc_html($category_name) ?></h2>
      </div>
      <div class="vf-grid vf-grid__col-2">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
if ( $post->ID == $do_not_duplicate ) continue; ?>
        <?php include(locate_template('partials/vf-card--article.php', false, false)); ?>
        <?php endwhile; endif; ?>
      </div>
      <div class="vf-grid" style="margin: 4%">

        <?php vf_pagination(); ?>
      </div>
    </main>
  </div>

  <div class="vf-inlay__content vf-u-background-color-ui-white | category-more">
    <main class="vf-inlay__content--full-width | vf-u-margin__bottom--0">

      <div class="| vf-u-margin__top--xl">
        <h3 class="vf-section-header__heading | vf-u-margin__bottom--xl">Popular in this category</h3>
      </div>
      <div class="vf-grid vf-grid__col-3">
        <?php $popular = new WP_Query(array('posts_per_page'=>3, 'meta_key'=>'popular_posts', 'orderby'=>'meta_value_num', 'order'=>'DESC', 'cat' => get_query_var('cat')));
        while ($popular->have_posts()) : $popular->the_post(); 
        include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false)); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </main>
  </div>

  <?php include(locate_template('partials/embletc-container.php', false, false)); ?>

  <?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>

<?php get_template_part('partials/footer');?>
