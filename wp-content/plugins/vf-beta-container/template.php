<?php

global $vf_plugin;
if ( ! $vf_plugin instanceof VF_Beta) return;

$content = $vf_plugin->api_html();

if ( ! vf_html_empty($content)) {
?>
<div <?php $vf_plugin->api_attr(array('class' => 'vf-u-grid--reset')); ?>>
  <div class="vf-body">
    <section class="vf-grid vf-u-margin__top--lg" >
      <?php echo $content; ?>
    </section>
  </div>
</div>
<?php } ?>
