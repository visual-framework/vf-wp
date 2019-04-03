<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Members) return;

$content = $vf_plugin->api_html();

if ( ! vf_html_empty($content)) {

  // Add grid layout classes to wrapping element
  $content = preg_replace(
    '#vf-content-hub-html#',
    '$1 vf-grid vf-grid__col-2',
    $content
  );
?>
<div <?php $vf_plugin->api_attr(); ?>>
  <?php echo $content; ?>
</div>
<?php } ?>
