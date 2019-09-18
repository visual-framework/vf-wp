<?php

global $vf_gutenberg;

$style = $vf_gutenberg->get_field('vf_box_style');
$heading = $vf_gutenberg->get_field('vf_box_heading');
$text = $vf_gutenberg->get_field('vf_box_text');

$classes = array('vf-box');

if (is_string($style)) {
  $classes[] = "vf-box--{$style}";
}

?>
<div class="<?php echo implode(' ', $classes); ?>">
  <h3 class="vf-box__heading"><?php echo $heading; ?></h3>
  <p class="vf-box__text"><?php echo $text; ?></p>
</div>
