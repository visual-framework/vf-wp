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
$author_url = get_author_posts_url(get_the_author_meta('ID'));

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

<section class="vf-inlay | vf-u-background-color--white vf-u-margin__bottom--xxl">

  <section class="vf-inlay__content | vf-u-background-color-ui--grey | hero-container">
    <main class="vf-inlay__content--main |  hero-left-column | vf-content | vf-u-margin__bottom--md">
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
      <?php include(locate_template('partials/vf-card--article-no-excerpt.php', false, false)); ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </main>
  </section>

</section>
<!-- Latest -->
<section class="vf-summary-container | embl-grid" style="grid-column-gap: 0;">

  <div class="vf-section-header | vf-u-margin__bottom--md">
    <a href="<?php site_url(); ?>/news/archive" class="vf-section-header__heading vf-section-header__heading--is-link">Latest
      <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-section-content | vf-grid vf-grid__col-3">
    <?php 
		$latestPostLoop = new WP_Query(array('post__not_in' => $ids, 'posts_per_page' => 6, 'category__not_in' => 6));
		while ($latestPostLoop->have_posts()) : $latestPostLoop->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>

<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">

<!-- Science -->
<section class="vf-summary-container | embl-grid" style="grid-column-gap: 0;">

  <div class="vf-section-header | vf-u-margin__bottom--md">
    <a href="<?php echo esc_url( $science_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Science <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-section-content | vf-grid vf-grid__col-3">
    <?php 
		$latestPostLoop = new WP_Query(array('category_name' => 'science','post__not_in' => $ids, 'posts_per_page' => 3));
		while ($latestPostLoop->have_posts()) : $latestPostLoop->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>
<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">
<!-- Lab Matters -->
<section class="vf-summary-container | embl-grid" style="grid-column-gap: 0;">

  <div class="vf-section-header | vf-u-margin__bottom--md">
    <a href="<?php echo esc_url( $lab_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Lab Matters <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-section-content | vf-grid vf-grid__col-3">
    <?php 
		$latestPostLoop = new WP_Query(array('category_name' => 'lab-matters','post__not_in' => $ids, 'posts_per_page' => 3));
		while ($latestPostLoop->have_posts()) : $latestPostLoop->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>
<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">

<!-- Alumni -->
<section class="vf-summary-container | embl-grid" style="grid-column-gap: 0;">

  <div class="vf-section-header | vf-u-margin__bottom--md">
    <a href="<?php echo esc_url( $alumni_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Alumni <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-section-content | vf-grid vf-grid__col-3">
    <?php 
		$latestPostLoop = new WP_Query(array('category_name' => 'alumni','post__not_in' => $ids, 'posts_per_page' => 3));
		while ($latestPostLoop->have_posts()) : $latestPostLoop->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>
<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">

<!-- Events -->
<section class="vf-summary-container | embl-grid" style="grid-column-gap: 0;">

  <div class="vf-section-header | vf-u-margin__bottom--md">
    <a href="<?php echo esc_url( $events_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Events <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-section-content | vf-grid vf-grid__col-3">
    <?php 
		$latestPostLoop = new WP_Query(array('category_name' => 'events','post__not_in' => $ids, 'posts_per_page' => 3));
		while ($latestPostLoop->have_posts()) : $latestPostLoop->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-card--article-no-excerpt-no-border.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>

<section class="vf-inlay">

<?php include(locate_template('partials/pow-container.php', false, false)); ?>

<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

</section>

<?php get_template_part('partials/footer'); ?>
