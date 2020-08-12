<?php

$is_container = get_field('is_container');
// Fallback for undefined older block fields
if ($is_container === null) {
  $is_container = true;
}
$is_container = (bool) $is_container;

$limit = get_field('limit');
$sidebar = get_field('sidebar');

$heading_singular = get_field('heading_singular');
$heading_singular = trim($heading_singular);

$heading_text = get_field('heading_text');

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

  $excerpt = apply_filters(
    'get_the_excerpt',
    $post->post_content
  );

$grid = '';
if ($sidebar) {
  $grid = 'embl-grid--has-sidebar';
}
else {
  $grid = 'embl-grid--has-centered-content';
}

?>

<?php if ($is_container) { ?>
<div class="embl-grid <?php echo ($grid); ?> ">
  <?php if ( ! empty($heading_singular)) { ?>
  <div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>"> <?php echo ($heading_singular); ?> <svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
    <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
    </svg></a>
    <p class="vf-section-header__text"><?php echo ($heading_text); ?></p>
  </div>      
  <?php } ?>

<?php } ?>
  <div>
    <?php
    $mainloop = new WP_Query (array('posts_per_page' => $limit ));
    $ids = array();
    while ($mainloop->have_posts()) : $mainloop->the_post();
    $ids[] = get_the_ID(); ?>

      <article class="vf-summary vf-summary--article">
        <h2 class="vf-summary__title">
          <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
        </h2>
        <span class="vf-summary__meta">
          <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
          <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
        </span>
        <p class="vf-summary__text">
        <?php echo get_the_excerpt(); ?></p>
      </article>
      <!--/vf-summary-->


    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
  </div>


<?php if ($is_container) { ?>
<div>
<?php if ($sidebar) { ?>
  <h3 class="vf-links__heading">More posts</h3>
  <?php
  $sidebarloop = new WP_Query(array('post__not_in' => $ids, 'posts_per_page' => 2 ));
  while ($sidebarloop->have_posts()) : $sidebarloop->the_post();
				$ids[] = get_the_ID(); ?>
  <article class="vf-summary vf-summary--article | vf-u-margin__bottom--md">
    <h2 class="vf-summary__title">
      <a href="<?php the_permalink(); ?>" class="vf-summary__link" style="font-size: 19px;"><?php echo esc_html(get_the_title()); ?></a>
    </h2>
    <span class="vf-summary__meta">
      <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
      <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
    </span>
  </article>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
<?php } ?>
</div>
</div>
<?php } ?>



<?php
  // Reset post data back to plugin
  $post = $old_post;
  setup_postdata($post);
}
?>
