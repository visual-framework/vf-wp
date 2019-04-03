<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Jobs) return;

$heading = trim(get_field('vf_jobs_heading', $post->ID));

$content = $vf_plugin->api_html();

if ( ! empty($content)) {
?>
<div <?php $vf_plugin->api_attr(); ?>>
<?php if ( ! empty($heading)) { ?>
  <div class="vf-section-header">
    <h2 class="vf-section-header__heading"><?php echo esc_html($heading); ?></h2>
  </div>
<?php } ?>
  <?php echo $content; ?>
</div>
<?php } ?>
