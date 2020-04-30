<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Data_resources) return;

$heading = trim(get_field('vf_data_resources_heading', $post->ID));

$content = $vf_plugin->api_html();
$hash = VF_Cache::hash(
  esc_url_raw($vf_plugin->api_url())
);

if (vf_cache_empty($content)) {
  return;
}

// Add hash attribute to opening tag
$content = preg_replace(
  '#^\s*<([^>]+?)>#',
  '<$1 data-cache="' . esc_attr($hash) . '">',
  $content
);

?>
<?php if ( ! empty($heading)) { ?>
  <div class="vf-section-header">
    <h2 class="vf-section-header__heading vf-u-margin__bottom--r"><?php echo esc_html($heading); ?></h2>
  </div>
<?php } ?>
<?php echo $content; ?>
