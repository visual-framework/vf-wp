<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Group_Header) return;

$heading = $vf_plugin->heading_html();
$content = $vf_plugin->api_html();
$hash = VF_Cache::hash(
  esc_url_raw($vf_plugin->api_url())
);

?>
<?php if ( ! $vf_plugin->is_minimal()) { ?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
  <?php if ( ! vf_cache_empty($heading)) { ?>
    <main class="vf-inlay__content--main">
      <?php echo $heading; ?>
    </main>
  <?php } ?>
    <aside class="vf-inlay__content--additional">
<?php } // is_minimal ?>

    <?php
    if ( ! vf_cache_empty($content)) {
      $content = preg_replace(
        '#^\s*<([^>]+?)>#',
        '<$1 data-cache="' . esc_attr($hash) . '">',
        $content
      );
      echo $content;
    }
    ?>

<?php if ( ! $vf_plugin->is_minimal()) { ?>
    </aside>
  </div>
</section>
<?php } // is_minimal ?>
