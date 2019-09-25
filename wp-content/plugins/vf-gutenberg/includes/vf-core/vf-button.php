<?php

global $vf_gutenberg;

$href = $vf_gutenberg->get_field('vf_button_href');
$text = $vf_gutenberg->get_field('vf_button_text');
$theme = $vf_gutenberg->get_field('vf_button_theme');
$size = $vf_gutenberg->get_field('vf_button_size');

$text = trim($text);
if (empty($text)) {
  $text = 'Button';
}

$classes = array('vf-button');

if (is_string($theme)) {
  $classes[] = "vf-button--{$theme}";
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
<a href="<?php echo esc_url($href); ?>" class="<?php echo implode(' ', $classes); ?>">
  <?php echo esc_html($text); ?>
</a>
