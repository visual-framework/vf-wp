<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $vf_plugin;

// TODO - move to admin config
$levels = array(
  'easy',
  'normal',
  'medium',
  'difficult',
  'extreme',
);
$level = $levels[0];

$classes = array('vf-hero');
$classes[] = "vf-hero--{$level}";

?>
<section class="<?php echo esc_attr(implode(' ', $classes)); ?>">
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
