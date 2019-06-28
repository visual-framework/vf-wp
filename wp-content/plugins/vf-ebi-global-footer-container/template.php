<?php

global $vf_plugin;
if ( ! $vf_plugin instanceof VF_EBI_Global_Footer) return;

$content = $vf_plugin->api_html();

if ( ! empty($content)) {
?>
<div <?php $vf_plugin->api_attr(array('class' => 'vfwp-column-reset')); ?>>
  <?php echo $content; ?>
</div>

<?php
// We load these scripts here as a short term solution to not over-architect
// something that shouldn't be permananent.
// This also means that for the EBI Header to function, the EBI Footer must
// also be used.
?>

<script defer="defer" src="https://dev.ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.3/js/script.js"></script>

<link rel="stylesheet" href="https://dev.ebi.emblstatic.net/web_guidelines/EBI-Framework/v1.3/css/ebi-global.css" type="text/css" media="all" />
<link rel="stylesheet" href="https://dev.ebi.emblstatic.net/web_guidelines/EBI-Icon-fonts/v1.3/fonts.css" type="text/css" media="all" />

<?php } ?>
