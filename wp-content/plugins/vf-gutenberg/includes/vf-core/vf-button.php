<?php

global $vf_gutenberg;

$url = $vf_gutenberg->get_field('vf_button_url');
$label = $vf_gutenberg->get_field('vf_button_label');
$style = $vf_gutenberg->get_field('vf_button_style');

$label = trim($label);

if (empty($label)) {
  $label = 'Button';
}

$classes = array('vf-button');

if ($style) {
  $classes[] = "vf-button--{$style}";
} else {
  $classes[] = "vf-button--primary";
}

?>
<a href="<?php echo esc_url($url); ?>" class="<?php echo implode(' ', $classes); ?>">
  <?php echo esc_html($label); ?>
</a>
