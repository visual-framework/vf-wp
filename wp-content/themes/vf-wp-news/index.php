<?php

get_header();

$category_science = get_cat_ID( 'Science & Technology' );
$science_link = get_category_link( $category_science );

$category_people = get_cat_ID( 'People & Perspectives' );
$people_link = get_category_link( $category_people );

$category_connections = get_cat_ID( 'Connections' );
$connections_link = get_category_link( $category_connections );

$category_announcements = get_cat_ID( 'EMBL Announcements' );
$announcements_link = get_category_link( $category_announcements );

$category_lab = get_cat_ID( 'Lab Matters' );
$lab_link = get_category_link( $category_lab );

$awards_link = get_post_type_archive_link('awards');
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

<section class="vf-grid vf-grid__col-6 | vf-u-background-color-ui--off-white | vf-u-padding__bottom--400 | vf-u-padding__top--800 | vf-u-margin__bottom--800 | vf-u-fullbleed">
    <div class="vf-grid__col--span-4 | vf-u-margin__bottom--500 | vf-grid-featured-col-left">
      <?php
			$mainPostLoop = new WP_Query (array(
        'post__not_in' => $excluded_translations_array,        
        'posts_per_page' => 1,
        'meta_key' => 'featured', 
        'meta_value' => '1' ,
        'category_name' => $categories_embl,  ));
$ids = array();
while ($mainPostLoop->have_posts()) : $mainPostLoop->the_post();
$ids[] = get_the_ID(); ?>
      <?php include(locate_template('partials/vf-card--article-excerpt.php', false, false)); ?>
      <?php endwhile;?>
      <?php wp_reset_postdata(); ?>
    </div>

    <div class="vf-grid__col--span-2 | vf-grid-featured-col-right">
      <?php
$cardsPostLoop = new WP_Query(array(
  'post__not_in' => array_merge($ids, $excluded_translations_array),        
  'posts_per_page' => 2,
  'meta_key' => 'featured', 
  'meta_value' => '1' ,
  'category_name' => $categories_embl,
));

while ($cardsPostLoop->have_posts()) : $cardsPostLoop->the_post();
				$ids[] = get_the_ID(); ?>
      <?php include(locate_template('partials/vf-card--article-no-excerpt.php', false, false)); ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>

</section>
<!-- Latest -->
<section class="vf-news-container vf-news-container--featured | embl-grid">

  <div class="vf-section-header | vf-u-margin__bottom--400">
    <a href="<?php site_url(); ?>/news/archive" class="vf-section-header__heading vf-section-header__heading--is-link">Latest
      <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-news-container__content vf-grid vf-grid__col-3">
    <?php
		$latestPostLoop1 = new WP_Query(array(
      'post__not_in' => array_merge($ids, $excluded_translations_array),        
      'posts_per_page' => 3, 
      'category_name' => $categories_embl, 
  ));
		while ($latestPostLoop1->have_posts()) : $latestPostLoop1->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>

<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">

<!-- Science -->
<section class="vf-news-container vf-news-container--featured | embl-grid">

  <div class="vf-section-header | vf-u-margin__bottom--400">
    <a href="<?php echo esc_url( $science_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Science & Technology <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-news-container__content vf-grid vf-grid__col-3">
    <?php
		$latestPostLoop2 = new WP_Query(array(
      'category_name' => 'science-technology',
      'post__not_in' => array_merge($ids, $excluded_translations_array),        
      'posts_per_page' => 3, 
  ));
		while ($latestPostLoop2->have_posts()) : $latestPostLoop2->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>
<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">
<!-- Lab Matters -->
<section class="vf-news-container vf-news-container--featured | embl-grid">

  <div class="vf-section-header | vf-u-margin__bottom--400">
    <a href="<?php echo esc_url( $people_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">People & Perspectives <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-news-container__content vf-grid vf-grid__col-3">
    <?php
		$latestPostLoop3 = new WP_Query(array(
      'category_name' => 'people-perspectives',
      'post__not_in' => array_merge($ids, $excluded_translations_array),        
      'posts_per_page' => 3, 
  ));
		while ($latestPostLoop3->have_posts()) : $latestPostLoop3->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>
<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">

<!-- Alumni -->
<section class="vf-news-container vf-news-container--featured | embl-grid">

  <div class="vf-section-header | vf-u-margin__bottom--400">
    <a href="<?php echo esc_url( $connections_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Connections <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-news-container__content vf-grid vf-grid__col-3">
    <?php
		$latestPostLoop4 = new WP_Query(array(
      'category_name' => 'connections',
      'post__not_in' => array_merge($ids, $excluded_translations_array),        
      'posts_per_page' => 3, 
  ));
		while ($latestPostLoop4->have_posts()) : $latestPostLoop4->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>
<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">

<!-- Events -->
<section class="vf-news-container vf-news-container--featured | embl-grid">

  <div class="vf-section-header | vf-u-margin__bottom--400">
    <a href="<?php echo esc_url( $announcements_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">EMBL Announcements <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-news-container__content vf-grid vf-grid__col-3">
    <?php
		$latestPostLoop5 = new WP_Query(array(
      'category_name' => 'embl-announcements',
      'post__not_in' => array_merge($ids, $excluded_translations_array), 
      'posts_per_page' => 3, 
  ));
		while ($latestPostLoop5->have_posts()) : $latestPostLoop5->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>

<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">


<section class="vf-news-container vf-news-container--featured | embl-grid">

  <div class="vf-section-header | vf-u-margin__bottom--400">
    <a href="<?php echo esc_url( $lab_link ); ?>"
      class="vf-section-header__heading vf-section-header__heading--is-link">Lab Matters <svg
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero" /></svg></a></div>

  <div class="vf-news-container__content vf-grid vf-grid__col-3">
    <?php
		$latestPostLoop6 = new WP_Query(array(
      'category_name' => 'lab-matters',
      'post__not_in' => array_merge($ids, $excluded_translations_array), 
      'posts_per_page' => 3, 
  ));
		while ($latestPostLoop6->have_posts()) : $latestPostLoop6->the_post(); ?>
    <?php	$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>

  </div>

</section>

<!-- Awards -->
<?php
$awardsLoop = new WP_Query(array(
  'post_type' => 'awards',
  'posts_per_page' => 3, 
));

// Check if the loop has posts
if ($awardsLoop->have_posts()): ?>
<hr style="grid-column: 1 / -1; height: 1px; background: #d0d0ce; width: 100%; border: 0; margin-bottom: 48px;">
  <section class="vf-news-container vf-news-container--featured | embl-grid | vf-u-background-color-ui--off-white vf-u-fullbleed | vf-u-padding__top--600">

    <div class="vf-section-header | vf-u-margin__bottom--400">
      <a href="<?php echo esc_url( $awards_link ); ?>"
        class="vf-section-header__heading vf-section-header__heading--is-link">Awards & Honours <svg
          class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
            fill="" fill-rule="nonzero" /></svg></a></div>

    <div class="vf-news-container__content vf-grid vf-grid__col-3">
      <?php while ($awardsLoop->have_posts()) : $awardsLoop->the_post(); ?>
        <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>

  </section>
<?php endif; ?>



<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>


<?php get_footer(); ?>
