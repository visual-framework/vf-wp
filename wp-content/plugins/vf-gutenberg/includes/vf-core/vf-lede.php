<?php

global $vf_gutenberg;

$text = $vf_gutenberg->get_field('vf_lede_text');

$classes = array('vf-lede');

?>
<p class="<?php echo implode(' ', $classes); ?>">
  <?php echo $text; ?>
</p>
