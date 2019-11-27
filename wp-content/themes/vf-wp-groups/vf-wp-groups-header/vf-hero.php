<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $vf_plugin;

$theme = $vf_plugin->get_hero_theme();

$level = $vf_plugin->get_hero_level();

$levels = array(
  'easy',
  'normal',
  'medium',
  'difficult',
  'extreme',
);

// Setup root HTML classes and attributes
$classes = array('vf-hero');
$classes[] = "vf-hero--{$levels[$level - 1]}";
$classes[] = " | vf-hero-theme--{$theme}";

$attr = array(
  'class' => implode(' ', $classes)
);

// Add background image for levels...
$image = $vf_plugin->get_hero_image();
if ($image && in_array($level, array(2, 3, 4))) {
  $attr['style'] = 'background-image: var(--vf-hero-bg-image);';
}

// Convert attributes array to string
$attr_str = array_map(
  function($k, $v) {
    return $k . '="' . esc_attr($v) . '"';
  },
  array_keys($attr),
  $attr
);

?>
<?php if ($image) { ?>
<style>
:root {
  --vf-hero-bg-image: url('<?php echo esc_url($image['sizes']['vf-hero']); ?>');
}
</style>
<?php } ?>
<section <?php echo implode(' ', $attr_str); ?>>
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
