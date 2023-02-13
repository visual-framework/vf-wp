<?php

$is_container = get_field('is_container');
// Fallback for undefined older block fields
if ($is_container === null) {
  $is_container = true;
}
$is_container = (bool) $is_container;

$title = get_the_title();
$theme = wp_get_theme();
$limit = get_field('limit');
$post_type = get_field('post_type');
$show_topics = get_field('show_topics');
$show_excerpt = get_field('show_excerpt');
$show_heading = get_field('show_heading');
$heading_singular = get_field('heading_singular');
$heading_singular = trim($heading_singular);
$heading_text = get_field('heading_text');
$heading_url = get_field('heading_url');
if (empty($post_type)) {
  $post_type = 'post';
}

$heading_link =''; 
if (!empty($heading_url)) {
  $heading_link = $heading_url['url'];
}
else {
  if ($theme == 'VF-WP Intranet') {
    $heading_link = '/community-blog';
  }
  else {
  $heading_link = get_permalink( get_option( 'page_for_posts' ) );
  }
} 

$layout = get_field('layout');
$grid = get_field('grid');

$category = get_field('category');
$tag = get_field('tag');
$keyword = get_field('keyword');

$show_image = get_field('show_image');
$show_location = get_field('show_location');
$show_categories = get_field('show_categories');

if (empty($heading_singular)) {
  $heading_singular = __('Latest posts', 'vfwp');
}

$latest_posts = get_posts(array(
  'posts_per_page' => 3
));

if (count($latest_posts)) {
  // Setup first post data so template tags work
  global $post;
  $old_post = $post;
  $post = $latest_posts[0];
  setup_postdata($post);


$vf_grid = '';
$embl_grid = '';

if (get_field('layout') == 'columns') {
  $embl_grid = null;
  $vf_grid = 'class="vf-grid vf-grid__col-' . $limit . '"';
}
else {
  $embl_grid = 'embl-grid--has-centered-content';
}

?>

<?php if ($is_container) {
        if (((get_field('layout') == 'list') && ($show_heading)) || (get_field('grid') == 'embl-grid') ) { ?>

<div class="embl-grid <?php echo ($embl_grid); ?> ">
  <?php if ( ! empty($heading_singular)) { ?>
  <div class="vf-section-header"><h2 class="vf-section-header__heading"><a class="vf-section-header__heading vf-section-header__heading--is-link"
      href="<?php echo esc_url($heading_link); ?>"> <?php echo ($heading_singular); ?> <svg
        aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a></h2>
    <p class="vf-section-header__text"><?php echo ($heading_text); ?></p>
  </div>
  <?php } ?>
  <?php } }?>
<?php if ($is_container) {
        if ((get_field('grid') == 'vf-grid')) { ?>

  <?php if ( ! empty($heading_singular)) { ?>
  <div class="vf-section-header"><h2 class="vf-section-header__heading"><a class="vf-section-header__heading vf-section-header__heading--is-link"
      href="<?php echo esc_url($heading_link); ?>"> <?php echo ($heading_singular); ?> <svg
        aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a></h2>
    <p class="vf-section-header__text"><?php echo ($heading_text); ?></p>
  </div>
  <?php } ?>
  <?php } }?>

  <div <?php echo ($vf_grid); ?>>

    <?php if (get_field('layout') == 'list') { ?>
    <?php include(locate_template('blocks/vfwp-latest-posts/partials/embl-grid.php', false, false)); ?>
    <?php } ?>

    <?php if (get_field('layout') == 'columns') { ?>
    <?php include(locate_template('blocks/vfwp-latest-posts/partials/columns-grid.php', false, false)); ?>
    <?php } ?>

  </div>
  <?php if ($is_container) { ?>
</div>
<?php } ?>


<?php
  // Reset post data back to plugin
  $post = $old_post;
  setup_postdata($post);
}
?>
