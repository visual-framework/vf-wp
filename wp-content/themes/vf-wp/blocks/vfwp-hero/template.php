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
      <?php echo $text; 
      if (! empty($link)) { ?>
          <a class="vf-link" href="<?php echo esc_url($link['url']) ?>"><?php echo $link['title'];?></a>
      <?php };
      
      ?>
    </p>
  </div>
</section>
<!--/vf-hero-->