<?php

global $post, $vf_plugin;
if ( ! $vf_plugin instanceof VF_Group_Header) return;

$heading = trim(get_field('vf_group_header_heading', $post->ID));

$heading = preg_replace(
  '#<h1[^>]*?>#',
  '<h1 class="vf-lede">',
  $heading
);

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
