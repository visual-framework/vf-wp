<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$level = get_field('vf_hero_level');


// Setup root HTML classes and attributes
$theme = get_field('vf_hero_theme');
$classes = array('vf-hero');
$classes[] = "vf-hero--{$level}";
if ($theme === 'default'){
  $classes[] = "";
}
else {
  $classes[] = "| vf-hero-theme--{$theme}";
}

$attr = array(
  'class' => implode(' ', $classes),
);

$heading = get_field('vf_hero_heading');
$text = get_field('vf_hero_text');

// Add background image for levels...
$image = get_field('vf_hero_image');

// Convert attributes array to string
$attr_str = array_map(
  function($k, $v) {
    return $k . '="' . esc_attr($v) . '"';
  },
  array_keys($attr),
  $attr
);

$link = get_field('vf_hero_link');

if ($link) {
  $text = '<a class="vf-link" href="'
    . esc_url($link['url'])
    . '">'
    . $text
    . '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
    <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
  </svg>'
    . '</a>';
}

?>

<section <?php echo implode(' ', $attr_str); ?>>
  <?php if ($image) { ?>
  <style>
  .vf-hero {
    --vf-hero-bg-image: url('<?php echo esc_url($image['sizes']['vf-hero']); ?>');
    --vf-hero-grid__row--initial: 16em;
  }

  </style>
  <?php } ?>
  <?php if (in_array($level, array(5))) { ?>
  <div class="vf-hero__image">
    <?php echo wp_get_attachment_image($image['ID'], 'vf-hero'); ?>
  </div>
  <?php } ?>
  <div class="vf-hero__content">
    <h2 class="vf-hero__heading">
      <?php echo $heading; ?>
    </h2>
    <p class="vf-hero__text">
      <?php echo $text; ?>
    </p>
  </div>
</section>
<!--/vf-hero-->