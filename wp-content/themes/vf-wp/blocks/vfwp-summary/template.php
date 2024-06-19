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
$link_target = is_array($link) && isset($link['target']) ? $link['target'] : '_self';
$text = get_field('text', false, false);
$text = wpautop($text);
$text = str_replace('<p>', '<p class="vf-summary__text">', $text);

$image = get_field('image');
if (!is_array($image)) {
  $image = null;
} else {
  $image = wp_get_attachment_image($image['ID'], 'medium', false, array(
    'class'    => 'vf-summary__image',
    'style'    => 'width: 180px; height: auto; border: 1px solid #d0d0ce',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));
}
$date = get_field('date');

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if (! $is_preview) {
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
if ($type === 'custom') {
  if (! $image && vf_html_empty($title) && vf_html_empty($text)) {
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
      <a href="<?php echo esc_url($link['url']); ?>" class="vf-summary__link" target="<?php echo esc_attr($link_target); ?>">
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

if ($type === 'post') {
?>
<article class="vf-summary vf-summary--news">
  <span class="vf-summary__date">
      <?php echo $date; ?>
  </span>
  <?php
    if ($image) {
      echo $image;
    }

  ?>
  <h3 class="vf-summary__title">
  <a href="<?php echo esc_url($link['url']); ?>" class="vf-summary__link">
  <?php echo esc_html($title); ?>
    </a>
  </h3>
  <?php echo $text; ?>
</article>
<?php
}

if ($type === 'event') {
  $event_style = get_field('event_style');
  if (empty($event_style)) {
    $event_style = 'default';
  }
  if (is_array($event_style)) {
    $event_style = $event_style[0];
  }
  $location = get_field('event_location');
  $event_type = get_field('event_type');

?>
<?php if ($event_style === 'alternate') { ?>
<a href="<?php echo esc_url($link['url']); ?>"
  class="vf-summary vf-summary--event | vf-summary--is-link vf-summary--easy vf-summary-theme--primary">
<?php } else { ?>
  <article class="vf-summary vf-summary--event">
<?php } ?>
  <p class="vf-summary__date">
  <?php echo $date; ?>
  </p>
  <h3 class="vf-summary__title">
    <?php if ($event_style !== 'alternate') {
      if (!empty($link)) { ?>
      <a href="<?php echo esc_url($link['url']); ?>" class="vf-summary__link">
    <?php } else {echo '';} } ?>
    <?php echo esc_html($title); ?>
    <?php if ($event_style !== 'alternate') { ?>
      </a>
    <?php } ?>
  </h3>
  <?php if (! empty($text)) { ?>
    <?php echo $text; ?>
  <?php } ?>
  <?php if (! empty($event_type)) { ?>
  <p class="vf-summary__text"><?php echo $event_type; ?></p>
  <?php } ?>
  <?php if (! empty($location)) { ?>
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
}

if ($type === 'publication') {

  $publication_title = get_field('publication_title');
  $publication_link = get_field('publication_link');
  $authors = get_field('authors');
  $year = get_field('year');
  $source = get_field('source');
  $doi = get_field('doi');

  if (vf_html_empty($title) && vf_html_empty($source) && vf_html_empty($authors)) {
    $admin_banner(__('Please enter custom content for this publication.', 'vfwp'));
    return;
  }

  $publication_link_url = is_array($publication_link) && isset($publication_link['url']) ? $publication_link['url'] : '';
?>
<article class="vf-summary vf-summary--publication">
    <h3 class="vf-summary__title">
        <a href="<?php echo esc_url($publication_link_url); ?>" class="vf-summary__link">
        <?php echo $publication_title; ?>
        </a>
    </h3>
    <p class="vf-summary__author">
      <?php echo $authors; ?>
    </p>
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
