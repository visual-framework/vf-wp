<?php

global $vf_gutenberg;

$url = $vf_gutenberg->get_field('vf_button_url');
$label = $vf_gutenberg->get_field('vf_button_label');
$style = $vf_gutenberg->get_field('vf_button_style');
$size = $vf_gutenberg->get_field('vf_button_size');

$label = trim($label);
if (empty($label)) {
  $label = 'Button';
}

$classes = array('vf-button');

if (is_string($style)) {
  $classes[] = "vf-button--{$style}";
} else {
  $classes[] = "vf-button--primary";
}

if ($size === 'small') {
  $classes[] = 'vf-text-button--2';
}

if ($size === 'large') {
  $classes[] = 'vf-text-button--1';
}

?>
<a href="<?php echo esc_url($url); ?>" class="<?php echo implode(' ', $classes); ?>">
  <?php echo esc_html($label); ?>
</a>
