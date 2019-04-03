<?php

global $post;

$heading = trim(get_field('vf_publications_heading', $post->ID));

?>
<div>
<?php if ( ! empty($heading)) { ?>
  <div class="vf-section-header">
    <h2 class="vf-section-header__heading"><?php echo esc_html($heading); ?></h2>
  </div>
<?php } ?>
  <p>[publications plugin]</p>
</div>
