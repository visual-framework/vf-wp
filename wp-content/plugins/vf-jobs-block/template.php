<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Jobs) return;

$heading = trim(get_field('vf_jobs_heading', $post->ID));

$content = $vf_plugin->api_html();

if ( ! empty($content)) {
?>
<div <?php $vf_plugin->api_attr(); ?>>

  <?php
    $was_no_result_found = strpos($content,'Unfortunately no content was found for this query');
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
  ?>

</div>
<?php } ?>
