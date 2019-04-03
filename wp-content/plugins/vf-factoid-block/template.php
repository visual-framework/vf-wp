<?php

global $vf_plugin;
if ( ! $vf_plugin instanceof VF_Factoid) return;

$content = $vf_plugin->api_html();

if ( ! empty($content)) {
?>
<div <?php $vf_plugin->api_attr(); ?>>
  <?php echo $content; ?>
</div>
<?php } ?>
