<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Publications) return;

$heading = trim(get_field('vf_publications_heading', $post->ID));

$content = $vf_plugin->api_html();

if ( ! vf_html_empty($content)) {
  // Add grid layout classes to wrapping element
  $content = preg_replace(
    '#vf-content-hub-html#',
    // '$1 vf-grid vf-grid__col-2',
    '$1',
    $content
  );
?>
<?php if ( ! empty($heading)) { ?>
  <div class="vf-section-header">
    <h2 class="vf-section-header__heading"><?php echo esc_html($heading); ?></h2>
  </div>
<?php } ?>
<div <?php $vf_plugin->api_attr(); ?>>
  <?php echo $content; ?>
</div>
<?php } ?>
