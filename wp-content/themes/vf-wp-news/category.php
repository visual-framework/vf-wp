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
?>

<section class="vf-inlay">
	<div class="vf-inlay__content | vf-u-background-color-ui--white">
		<main class="vf-inlay__content--full-width | category-container">
			<div>
				<h2 class="vf-text vf-text-heading--1 | vf-u-margin__bottom--xl"><?php wp_title(''); ?></h2>
			</div>
			<div class="vf-section-header vf-u-margin__bottom--md"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php site_url(); ?>/archive"> Latest <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a></div>
			<div class="vf-grid vf-grid__col-3 | category-latest">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
if ( $post->ID == $do_not_duplicate ) continue; ?>
				<?php include(locate_template('partials/vf-summary--article.php', false, false)); ?>
				<?php endwhile; endif; ?>
			</div>
			<?php vf_pagination(); ?>
		</main>
	</div>

	<div class="vf-inlay__content vf-u-background-color-ui--off-white">
		<main class="vf-inlay__content--full-width">
			<div class="embl-grid | category-latest | category-top-stories">
				<div style="min-width: 150px;">
					<h3 class="vf-section-header__heading ">Popular</h3>
				</div>
				<div class="vf-grid vf-grid__col-3">
					<?php $popular = new WP_Query(array('posts_per_page'=>3, 'meta_key'=>'popular_posts', 'orderby'=>'meta_value_num', 'order'=>'DESC', 'cat' => get_query_var('cat')));
	while ($popular->have_posts()) : $popular->the_post(); 
	include(locate_template('partials/vf-summary--article-no-excerpt.php', false, false)); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</main>
	</div>

	<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

	<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>
<?php get_template_part('partials/footer');?>