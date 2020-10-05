<?php

get_header();

$title = get_the_title(get_option('page_for_posts'));
$user_id = get_the_author_meta('ID');

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

<div class="vf-grid">
  <div class="vf-box vf-box--normal vf-box-theme--primary">
    <h2 class="vf-text vf-text-heading--2">About the author</h2>
    <div class="vf-grid" style="max-width: fit-content;">
      <?php echo get_avatar( get_the_author_meta( 'ID' ), '90', '', '', array( 'class' =>  'vf-summary__image vf-summary__image--avatar vf-u-margin__bottom--sm' ) ); ?>
      <div>
        <h3 class="vf-box__heading">
          <?php the_author(); ?></h3>
        <p class="vf-box__text"><?php echo nl2br(get_the_author_meta('description')); ?></p>
        <p class="vf-box__text | vf-u-text-color--ui--grey">
          <a href="mailto:<?php echo nl2br(get_the_author_meta('email')); ?>"
            ><?php echo nl2br(get_the_author_meta('email')); ?></a>
        </p>
      </div>
    </div>
  </div>
</div>

<section class="vf-u-margin__bottom--md ">
  <div class="vf-u-background-color-ui--off-white | vf-u-padding--md">
      <h3 class="vf-section-header__heading vf-u-margin__bottom--xl">Articles by <?php the_author(); ?></h3>
    <div class="vf-grid | vf-grid__col-3">
      <?php 
      $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $author = new WP_Query(array(
        'paged' => $page, 
        'author__in' => $user_id));
					while ($author->have_posts()) : $author->the_post();
					include(locate_template('partials/vf-card--article.php', false, false));?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
      <div class="vf-grid" style="margin: 4%"> <?php vf_pagination();?></div>
  </div>

  <div class="vf-u-background-color-ui-white | vf-u-padding__top--md | vf-u-margin__top--md">
        <h3 class="vf-text vf-text-heading--3">Popular</h3>
      <div class="vf-grid vf-grid__col-3">
        <?php $popular = new WP_Query(array('posts_per_page'=> 3, 'meta_key'=>'popular_posts', 'orderby'=>'meta_value_num', 'order'=>'DESC', 'author__in' => $user_id));
					while ($popular->have_posts()) : $popular->the_post();
					include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false));?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
  </div>

  <?php include(locate_template('partials/embletc-container.php', false, false)); ?>

  <?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>
<?php get_footer(); ?>
