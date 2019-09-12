<?php

global $vf_gutenberg;

$url = $vf_gutenberg->get_field('vf_button_url');
$label = $vf_gutenberg->get_field('vf_button_label');

$label = trim($label);

if (empty($label)) {
  $label = 'Button';
}

?>
<a href="<?php echo esc_url($url); ?>" class="vf-button vf-button--primary">
  <?php echo esc_html($label); ?>
</a>
