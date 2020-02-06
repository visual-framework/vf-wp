<?php

get_template_part('partials/header');

$category_science = get_cat_ID( 'science' );
$science_link = get_category_link( $category_science );

$category_alumni = get_cat_ID( 'alumni' );
$alumni_link = get_category_link( $category_alumni );

$category_events = get_cat_ID( 'events' );
$events_link = get_category_link( $category_events );

$category_lab = get_cat_ID( 'lab matters' );
$lab_link = get_category_link( $category_lab );

$category_pow = get_cat_ID( 'picture of the week' );
$pow_link = get_category_link( $category_pow );

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

<section class="vf-inlay | vf-u-background-color--white">
	<section class="vf-inlay__content | vf-u-background-color-ui--grey | hero-container">
		<main class="vf-inlay__content--main |  hero-left-column | vf-content vf-u-margin--0">
			<?php 
			$mainPostLoop = new WP_Query (array('posts_per_page' => 1, 'meta_key' => 'featured', 'meta_value' => '1' ));
$ids = array();
while ($mainPostLoop->have_posts()) : $mainPostLoop->the_post();
$ids[] = get_the_ID(); ?>
			<?php include(locate_template('partials/vf-summary--article--color.php', false, false)); ?>
			<?php endwhile;?>
			<?php wp_reset_postdata(); ?>
		</main>

		<main class="vf-inlay__content--additional | hero-right-column">
			<?php
$cardsPostLoop = new WP_Query(array('post__not_in' => $ids, 'posts_per_page' => 2, 'meta_key' => 'featured',
	'meta_value' => '1' ));
while ($cardsPostLoop->have_posts()) : $cardsPostLoop->the_post(); 
				$ids[] = get_the_ID(); ?>
			<?php include(locate_template('partials/vf-summary--article-no-excerpt.php', false, false)); ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</main>
	</section>

	<div class="vf-inlay__content vf-u-background-color-ui--white">
		<main class="vf-inlay__content--full-width | latest-posts-container">
			<div class="embl-grid latest">
			<div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php site_url(); ?>archive.php"> Latest <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a></div>
				<div class="vf-grid vf-grid__col-3">
					<?php 
					$latestPostLoop = new WP_Query(array('post__not_in' => $ids, 'posts_per_page' => 3, 'category__not_in' => 487));
					while ($latestPostLoop->have_posts()) : $latestPostLoop->the_post(); ?>
					<?php	$ids[] = get_the_ID(); ?>
					<?php include(locate_template('partials/vf-summary--article.php', false, false)); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>

				</div>
			</div>

			<div class="embl-grid latest">
				<div>
				</div>
				<div class="vf-grid vf-grid__col-3">
					<?php 
					$latestPostLoop2 = new WP_Query(array('post__not_in' => $ids, 'posts_per_page' => 3, 'category__not_in' => 487));
	while ($latestPostLoop2->have_posts()) : $latestPostLoop2->the_post(); ?>
					<?php	$ids[] = get_the_ID(); ?>

					<?php include(locate_template('partials/vf-summary--article.php', false, false)); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>

				</div>
			</div>
			<hr class="vf-divider">

			<div class="embl-grid latest">
			<div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php echo esc_url( $science_link ); ?>"> Science <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a></div>
				<div class="vf-grid vf-grid__col-3">
					<?php 
					$scienceLoop = new WP_Query(array('category_name' => 'science', 'post__not_in' => $ids, 'posts_per_page' => 3));
	while ($scienceLoop->have_posts()) : $scienceLoop->the_post(); ?>
					<?php	$ids[] = get_the_ID(); ?>

					<?php include(locate_template('partials/vf-summary--article-no-excerpt.php', false, false)); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>

				</div>
			</div>
			<hr class="vf-divider">

			<div class="embl-grid latest">
			<div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php echo esc_url( $lab_link ); ?>"> Lab Matters <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a></div>

				<div class="vf-grid vf-grid__col-3">
					<?php 
					$labMattersLoop = new WP_Query(array('category_name' => 'lab-matters','post__not_in' => $ids, 'posts_per_page' => 3));
	while ($labMattersLoop->have_posts()) : $labMattersLoop->the_post(); ?>
					<?php	$ids[] = get_the_ID(); ?>

					<?php include(locate_template('partials/vf-summary--article-no-excerpt.php', false, false)); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>

				</div>
			</div>
			<hr class="vf-divider">

			<div class="embl-grid latest">
			<div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php echo esc_url( $alumni_link ); ?>"> Alumni <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a></div>

				<div class="vf-grid vf-grid__col-3">
					<?php 
					$alumniLoop = new WP_Query(array('category_name' => 'alumni','post__not_in' => $ids, 'posts_per_page' => 3));
	while ($alumniLoop->have_posts()) : $alumniLoop->the_post(); ?>
					<?php	$ids[] = get_the_ID(); ?>

					<?php include(locate_template('partials/vf-summary--article-no-excerpt.php', false, false)); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>

				</div>
			</div>
			<hr class="vf-divider">

			<div class="embl-grid latest">
			<div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php echo esc_url( $events_link ); ?>"> Events <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a></div>

				<div class="vf-grid vf-grid__col-3">
					<?php 
					$eventsLoop = new WP_Query(array('category_name' => 'events','post__not_in' => $ids, 'posts_per_page' => 3));
	while ($eventsLoop->have_posts()) : $eventsLoop->the_post(); ?>
					<?php	$ids[] = get_the_ID(); ?>
					<?php include(locate_template('partials/vf-summary--article-no-excerpt.php', false, false)); ?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</main>
	</div>

	<?php include(locate_template('partials/pow-container.php', false, false)); ?>

	<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

	<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>

<?php get_template_part('partials/footer'); ?>

