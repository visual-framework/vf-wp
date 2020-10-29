<?php
/**
 * VF-WP Hero Header template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$vf_plugin = VF_Plugin::get_plugin('vf_wp_hero');
if ( ! $vf_plugin instanceof VF_WP_Hero) {
  return;
}

// Plugin is rendered inside Gutenberg block
$is_render = $vf_plugin->__experimental__is_admin_render();

?>

<?php 

$theme = get_field('vf_hero_theme');

$level = get_field('vf_hero_level');


// Setup root HTML classes and attributes
$classes = array('vf-hero');
$classes[] = "vf-hero--{$level}";
if (get_field('vf_hero_theme') === 'default'){
  $classes[] = "";
}
else {
  $classes[] = "| vf-hero-theme--{$theme}";
}

$attr = array(
  'class' => implode(' ', $classes),
);

// Add background image for levels...
$image = $vf_plugin->get_hero_image();

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
    --vf-hero-grid__row--initial: 16em;
  }

  </style>
  <?php } 
/**
 * Awaiting assets from designer

    <path fill="" d="M1,1V199H199V1z"/>
*/ ?>
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
