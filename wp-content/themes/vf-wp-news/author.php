<?php

get_template_part('partials/header');

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

<div class="vf-grid | vf-u-background-color--green--dark | vf-u-padding__bottom--md | vf-u-margin__bottom--xxl">
  <div class="vf-u-margin__right--xl vf-u-margin__left--xl">
    <h3 class="vf-text vf-text-heading--2 | vf-u-margin__top--sm" style="font-weight: 200; color: #d0d0ce;"><em>About
        the author</em></h3>
    <div class="vf-grid | vf-u-padding--0 | author-summary" style="max-width: fit-content;">
      <div class="author-avatar">
        <?php echo get_avatar( get_the_author_meta( 'ID' ), 90 ); ?>
      </div>
      <div class="vf-content | author-description">
        <h2 class="vf-u-text-color--ui--white | vf-u-margin__top--0" style="font-weight: 400;"><?php the_author(); ?>
        </h2>
        <p class="vf-u-text-color--ui--white"><?php echo nl2br(get_the_author_meta('description')); ?> </p>
        <i class="fas fa-envelope" style="font-size: 14px;"></i>
        <p class="vf-text--body vf-text-body--5 | vf-u-text-color--ui--white" style="display: inline-block;">
          <?php echo nl2br(get_the_author_meta('email')); ?></p>
      </div>
    </div>
  </div>
</div>


<div class="vf-grid">
  <h3 class="vf-section-header__heading vf-u-margin__bottom--md">Articles by <?php the_author(); ?></h3>
</div>
<div class="vf-grid | vf-grid__col-3 | category-latest">
  <?php $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
    			'posts_per_page' => 6,
   				'paged' => $page,
				'author__in' => $user_id); 
				query_posts($args);?>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
				if ( $post->ID == $do_not_duplicate ) continue; ?>
  <?php include(locate_template('partials/vf-summary--article.php', false, false)); ?>
  <?php endwhile; endif; ?>
</div>
<div class="vf-grid" style="margin: 4%"> <?php vf_pagination();
      ?>
</div>




<section class="vf-inlay">
  <div class="vf-inlay__content | vf-u-background-color-ui--off-white | vf-u-margin__bottom--xs">
    <main class="vf-inlay__content--full-width">
      <h3 class="vf-section-header__heading | vf-u-margin__bottom--md">Popular</h3>
      <div class="vf-grid vf-grid__col-3">
        <?php $popular = new WP_Query(array('posts_per_page'=>3, 'meta_key'=>'popular_posts', 'orderby'=>'meta_value_num', 'order'=>'DESC', 'author__in' => $user_id));
					while ($popular->have_posts()) : $popular->the_post(); 
					include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false));?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </main>
  </div>

  <?php include(locate_template('partials/embletc-container.php', false, false)); ?>

  <?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>
<?php get_template_part('partials/footer'); ?>