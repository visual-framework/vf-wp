<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Group_Header) return;

$heading = trim(get_field('vf_group_header_heading', $post->ID));

$heading = preg_replace(
  '#<h1[^>]*?>#',
  '<h1 class="vf-lede">',
  $heading
);

// if heading is empty, use the contenthub description
if (vf_html_empty($heading)) {
  if ( class_exists('VF_Cache') ) {
    $uuid = vf__get_site_uuid();
    $heading = VF_Cache::get_post('https://dev.beta.embl.org/api/v1/pattern.html?filter-content-type=profiles&filter-uuid='.$uuid.'&pattern=node-teaser&source=contenthub');
    $heading = preg_replace(
      '#<p>#',
      '<h1 class="vf-lede">',
      $heading
    );
    $heading = preg_replace(
      '#</p>#',
      ' <a class="vf-link" href="'.get_site_url().'/about">Read more</a>.</h1>',
      $heading
    );
  }
}


$content = $vf_plugin->api_html();

?>
<?php if ( ! $vf_plugin->is_minimal()) { ?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-white">
  <?php if ( ! vf_html_empty($heading)) { ?>
    <main class="vf-inlay__content--main">
      <?php echo $heading; ?>
    </main>
  <?php } ?>
    <aside class="vf-inlay__content--additional">
<?php } // is_minimal ?>

    <?php if ( ! vf_html_empty($content)) { ?>
    <div <?php $vf_plugin->api_attr(); ?>>
      <?php echo $content; ?>
    </div>
    <?php } ?>

<?php if ( ! $vf_plugin->is_minimal()) { ?>
    </aside>
  </div>
</section>
<?php } // is_minimal ?>
