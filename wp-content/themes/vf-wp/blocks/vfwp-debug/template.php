<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$text = get_field('text');

?>
<div>
  <h2>Example Block </h2>
  <p>
    <b>Text field:</b> <?php echo esc_html($text); ?>
    <br><b>Preview:</b> <?php var_dump($is_preview); ?>
  </p>
  <h3>Inner Blocks</h3>
  <div class="vfwp-innerblocks">
    <InnerBlocks />
  </div>
  <p><b>End debug block</b></p>
</div>
