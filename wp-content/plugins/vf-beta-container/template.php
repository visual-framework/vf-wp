<?php

global $vf_plugin;
if ( ! $vf_plugin instanceof VF_Beta) return;

$content = $vf_plugin->api_html();

if ( ! vf_html_empty($content)) {
?>
<section <?php $vf_plugin->api_attr(array('class' => 'vf-grid')); ?>>
  <?php echo $content; ?>
</section>
<?php } ?>
