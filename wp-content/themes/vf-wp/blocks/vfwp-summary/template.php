<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$type = get_field('select_type');
if (empty($type)) {
  $type = 'custom';
}
if (is_array($type)) {
  $type = $type[0];
}

$title = get_field('title');
$title = trim($title);

$link = get_field('link');

$text = get_field('text', false, false);
$text = wpautop($text);
$text = str_replace('<p>', '<p class="vf-summary__text">', $text);

$image = get_field('image');
if ( ! is_array($image)) {
  $image = null;
} else {
  $image = wp_get_attachment_image($image['ID'], 'medium', false, array(
    'class'    => 'vf-summary__image',
    'style'    => 'width: 180px; height: auto; border: 1px solid #d0d0ce',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
}

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if ( ! $is_preview) {
    return;
  }
?>
<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
};

?>

<?php
if ( $type === 'custom' ) {
  if (
    ! $image
    && vf_html_empty($title)
    && vf_html_empty($text)
  ) {
    $admin_banner(__('Please enter custom content for this summary.', 'vfwp'));
    return;
  }
?>
<article class="vf-summary <?php if ($image) { echo 'vf-summary--has-image'; } ?>">
  <?php
  if ($image) {
    echo $image;
  }
  ?>
  <h3 class="vf-summary__title">
    <?php if ($link) { ?>
      <a href="<?php echo esc_url($link['url']); ?>" class="vf-summary__link">
    <?php } ?>

    <?php echo esc_html($title); ?>

  <?php if ($link) { ?>
  </a>
  <?php } ?>
  </h3>

  <?php echo $text; ?>
</article>
<?php
  return;
}

if ( $type === 'post' ) {
  $object_post = get_field('post');
  if ($object_post instanceof WP_Post === false) {
    $admin_banner(__('Please select a post for this summary.', 'vfwp'));
    return;
  }
  // Setup first post data so template tags work
  global $post;
  $old_post = $post;
  setup_postdata($post = $object_post);

  $excerpt = apply_filters(
    'get_the_excerpt',
    $post->post_content
  );
?>
<article class="vf-summary vf-summary--news">
  <span class="vf-summary__date">
    <time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>">
      <?php the_time(get_option('date_format')); ?>
    </time>
  </span>
  <?php
  the_post_thumbnail('thumbnail', array(
    'style'    => 'width: 180px;',
    'class'    => 'vf-summary__image',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
  ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink() ?>" class="vf-summary__link">
      <?php the_title() ?>
    </a>
  </h3>
  <p class="vf-summary__text"><?php echo $excerpt; ?></p>
</article>
<?php
  wp_reset_postdata();
  return;
}

if ( $type === 'event' ) {
  $object_event = get_field('event');
  if ($object_event instanceof WP_Post === false) {
    $admin_banner(__('Please select an event for this summary.', 'vfwp'));
    return;
  }
  $event_style = get_field('event_style');
  if (empty($event_style)) {
    $event_style = 'default';
  }
  if (is_array($event_style)) {
    $event_style = $event_style[0];
  }
  // Setup first post data so template tags work
  global $post;
  $old_post = $post;
  setup_postdata($post = $object_event);
  $location = get_field('vf_event_location',$post->ID);
  $event_type = get_field('vf_event_event_type',$post->ID);

  $excerpt = apply_filters(
    'get_the_excerpt',
    $post->post_content
  );
?>
<?php if ($event_style === 'alternate') { ?>
<a href="<?php the_permalink(); ?>"
  class="vf-summary vf-summary--event | vf-summary--is-link vf-summary--easy vf-summary-theme--primary">
<?php } else { ?>
  <article class="vf-summary vf-summary--event">
<?php } ?>
  <p class="vf-summary__date">
    <time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>">
      <?php the_time(get_option('date_format')); ?>
    </time>
  </p>
  <h3 class="vf-summary__title">
    <?php if ($event_style !== 'alternate') { ?>
      <a href="<?php the_permalink(); ?>" class="vf-summary__link">
    <?php } ?>
    <?php the_title() ?>
    <?php if ($event_style !== 'alternate') { ?>
      </a>
    <?php } ?>
  </h3>
  <?php if ($event_style !== 'alternate' && ! empty($excerpt)) { ?>
    <p class="vf-summary__text"><?php echo $excerpt; ?></p>
  <?php } ?>
  <?php if ( ! empty($event_type)) { ?>
  <p class="vf-summary__text"><?php echo $event_type; ?></p>
  <?php } ?>
  <?php if ( ! empty($location)) { ?>
  <p class="vf-summary__location"><?php echo $location; ?></p>
  <?php } ?>
<?php if ($event_style === 'alternate') { ?>
  <svg class="vf-summary__icon | vf-icon vf-icon-arrow--right" width="24" height="24"
    xmlns="http://www.w3.org/2000/svg">
    <path
      d="M23.6 11.289l-9.793-9.7a2.607 2.607 0 00-3.679.075 2.638 2.638 0 00-.068 3.689l3.871 3.714a.25.25 0 01-.173.43H2.135A2.28 2.28 0 00.1 12c0 .815.448 2.51 2 2.51h11.679a.25.25 0 01.177.427l-3.731 3.733a2.66 2.66 0 003.758 3.754l9.625-9.72a1 1 0 00-.008-1.415z"
      fill="" fill-rule="nonzero" />
  </svg>
</a>
<?php } else { ?>
</article>
<?php } ?>
<?php
  wp_reset_postdata();
  return;
}

if ( $type === 'publication' ) {

  $publication_title = get_field('publication_title');
  $publication_link = get_field('publication_link');
  $authors = get_field('authors');
  $year = get_field('year');
  $source = get_field('source');
  $doi = get_field('doi');
  
  if (
    
    vf_html_empty($title)
    && vf_html_empty($source)
    && vf_html_empty($authors)
  ) {
    $admin_banner(__('Please enter custom content for this publication.', 'vfwp'));
    return;
  }
?>
<article class="vf-summary vf-summary--publication">
    <h3 class="vf-summary__title">
        <a href="<?php echo esc_url($publication_link['url']); ?>" class="vf-summary__link">
        <?php echo $publication_title; ?>
    </a>
    </h3>
    <p class="vf-summary__author">
      <?php echo $authors; ?>    </p>
    <p class="vf-summary__source">
    <?php echo $source; ?>
        <span class="vf-summary__date"><?php echo $year; ?></span>
    </p>

    <p class="vf-summary__text">
    <?php echo $doi; ?>
    </p>
</article>
<?php
  return;
}
?>
