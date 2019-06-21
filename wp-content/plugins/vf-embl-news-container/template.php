<?php

global $vf_plugin;
if ( ! $vf_plugin instanceof VF_EMBL_News) return;

$content = $vf_plugin->api_html();

if ( ! empty($content)) {
?>

<div class="vfwp-column-reset vf-body vf-body__additional-content vf-u-background-color-ui--white">
  <hr class="vf-divider">
  <section class="vf-news-container | embl-grid embl-grid--has-sidebar">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading"><?php the_title(); ?></h2>
    </div>
    <div <?php $vf_plugin->api_attr(array('class' => 'vf-news-container__content')); ?>>
      <?php echo $content; ?>
    </div>
    <div class="vf-news-container__sidebar">
      <?php
      if (class_exists('VF_Factoid')) {
        $vf_factoid = VF_Plugin::get_plugin('vf_factoid');
        $fields = get_field('vf_embl_news_factoid');
        if (is_array($fields)) {
          VF_Plugin::render($vf_factoid, $fields, $vf_plugin);
        }
      }
      ?>
    </div>
  </section>
</div>
<?php } ?>
