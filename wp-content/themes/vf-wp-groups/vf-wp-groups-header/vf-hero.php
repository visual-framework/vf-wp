<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$vf_plugin = VF_Plugin::get_plugin('vf_wp_groups_header');
if ( ! $vf_plugin instanceof VF_WP_Groups_Header) {
  return;
}

$theme = $vf_plugin->get_hero_theme();

$level = $vf_plugin->get_hero_level();

$levels = array(
  'very-easy',
  'easy',
  'normal',
  'hard',
  'extreme',
);
if (is_numeric($level)) {
  $level = $levels[intval($level) - 1];
}

// Setup root HTML classes and attributes
$classes = array('vf-hero');
$classes[] = 'vf-hero--inlay';
$classes[] = "vf-hero--{$level}";
$classes[] = " | vf-hero-theme--{$theme}";

$attr = array(
  'class' => implode(' ', $classes),
  'style' => ''
);

// Add background image for levels...
$image = $vf_plugin->get_hero_image();
if ($image && in_array($level, array(2, 3, 4))) {
  // $attr['style'] = 'background-image: var(--vf-hero-bg-image);';
}
// $attr['style'] .= ' grid-column: main;';

// Convert attributes array to string
$attr_str = array_map(
  function($k, $v) {
    return $k . '="' . esc_attr($v) . '"';
  },
  array_keys($attr),
  $attr
);

?>
<section <?php echo implode(' ', $attr_str); ?>>
  <?php if ($image) { ?>
  <style>
  .vf-hero {
    --vf-hero-bg-image: url('<?php echo esc_url($image['sizes']['vf-hero']); ?>');
    background: var(--vf-hero-bg-image);
  }

  </style>
  <?php } ?>
  <?php if ( ! $image || $level === 1) { ?>
  <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" class="vf-lens | vf-hero__lens">
<?php
/**
 * Awaiting assets from designer

    <path fill="" d="M1,1V199H199V1z"/>
*/ ?>
  </svg>
  <?php } ?>
  <?php if (in_array($level, array(5))) { ?>
  <div class="vf-hero__image">
    <?php echo wp_get_attachment_image($image['ID'], 'vf-hero'); ?>
  </div>
  <?php } ?>
  <div class="vf-hero__content">
    <h2 class="vf-hero__heading">
      <?php echo $vf_plugin->get_hero_heading(); ?>
    </h2>
    <p class="vf-hero__text">
      <?php echo $vf_plugin->get_hero_text(); ?>
    </p>
  </div>
</section>
<!--/vf-hero-->
