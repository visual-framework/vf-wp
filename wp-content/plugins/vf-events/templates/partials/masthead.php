<?php
$location = get_field('vf_event_location', $post->post_parent);
$location = strtoupper($location);
$event_type = get_field('vf_event_event_type', $post->post_parent);

$masthead_image = get_field('vf_event_masthead', $post->post_parent);
$masthead_image = wp_get_attachment_url($masthead_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$theme = get_field('vf_event_theme', $post->post_parent);

?>

<style>
  .vf-masthead {
    --vf-masthead__bg-image: url(<?php echo $masthead_image; ?>);
    --global-theme-fg-color: #fff;
    --global-theme-bg-color: #18974C;
  }

  .vf-masthead--with-title-block .vf-masthead__title:before {
    --global-theme-bg-color: #18974C;
  }

</style>
<div class="vf-masthead  vf-masthead--with-title-block
  vf-masthead--has-image | vf-u-margin__bottom--800
" style="background-image: var(--vf-masthead__bg-image)" data-vf-js-masthead>
<div class="vf-masthead__inner">
<div class="vf-masthead__title">
<h1 class="vf-masthead__heading">
<a href="JavaScript:Void(0);" class="vf-masthead__heading__link">
<?php the_title(); ?></a>
<span class="vf-masthead__heading--additional">
<?php if ( ! empty($event_type)) {
      echo esc_html($event_type); }?></span>
</h1>
<h2 class="vf-masthead__subheading">
<span class="vf-masthead__location">
<?php 
      if ( ! empty($location)) {
      echo esc_html($location); }?></span>
</h2>
</div>
</div>
</div>