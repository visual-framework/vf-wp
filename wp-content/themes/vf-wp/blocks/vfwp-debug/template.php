<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$text = get_field('text');

?>
<div>
  <h2>Example Block</h2>
  <p><b>Text field:</b> <?php echo esc_html($text); ?></p>
  <div>
    <h3>Inner Blocks</h3>
    <?php if ($is_preview) { ?>
      <div class="vf-banner vf-banner--alert vf-banner--info">
        <div class="vf-banner__content">
          <p class="vf-banner__text">
            <?php esc_html_e('Content placeholder.', 'vfwp'); ?>
          </p>
        </div>
      </div>
      <!--/vf-banner-->
    <?php } else { ?>
    <div class="vfwp-innerblocks">
      <InnerBlocks />
    <div>
    <?php } ?>
  </div>
</div>
