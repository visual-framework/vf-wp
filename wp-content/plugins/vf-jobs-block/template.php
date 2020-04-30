<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Jobs) return;

$heading = trim(get_field('vf_jobs_heading', $post->ID));

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

<?php
  $was_no_result_found = strpos($content,'Unfortunately no content was found for this query');
  // @todo: this method to detecting no results found no longer works due to how caching is handeled,
  //        but maybe that's ok and we should be fine with showing the header + more jobs link
  if ($was_no_result_found < 1) {
    if ( ! empty($heading)) {
    ?>
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading"><?php echo esc_html($heading); ?></h2>
    </div>
    <?php
    }
  } else {
    echo '<!-- As no content was found for this query, the jobs header has also been hidden. -->';
  }
  echo $content . PHP_EOL;
  if ($was_no_result_found < 1) {
    echo '<p><a href="vf-link" href="//www.embl.org/jobs">View all EMBL jobs</a></p>' . PHP_EOL;
  }
?>
