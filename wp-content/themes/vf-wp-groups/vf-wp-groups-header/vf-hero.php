<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$vf_plugin = VF_Plugin::get_plugin('vf_wp_groups_header');
if ( ! $vf_plugin instanceof VF_WP_Groups_Header) {
  return;
}

$image = $vf_plugin->get_hero_image();
$hero_link = get_field('vf_hero_link');
$add_heading_1 = get_field('vf_hero_additional_heading_1');
$add_heading_2 = get_field('vf_hero_additional_heading_2');
$hero_text = get_field('vf_hero_text', false, false);

$spacing = get_field('vf_hero_spacing');
$spacing_class = "| vf-hero--";

if ($spacing === 'default') {
  $spacing_class = "";
}
elseif ($spacing === '800') {
$spacing_class .= "{$spacing}";
 }
elseif ($spacing === '1200') {
$spacing_class .= "{$spacing}";
 }
elseif ($spacing === '1600') {
$spacing_class .= "{$spacing}";
 }

?>

<section class="vf-hero | vf-u-fullbleed <?php echo esc_attr($spacing_class); ?> | vf-u-margin__bottom--0">
  <style>
    .vf-hero {
      <?php if ($image) {
        ?>--vf-hero--bg-image: url('<?php echo esc_url($image['sizes']['vf-hero']); ?>');
        <?php
      }
      else {
        ?>--vf-hero--bg-image-size: auto 28.5rem;
        <?php
      }
      ?>
    }

  </style>
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
    <?php
  //Additional headings
  if (!empty ($add_heading_1)) { ?>
    <p class="vf-hero__kicker">
      <a href="<?php echo esc_url($add_heading_1['url']); ?>"><?php echo esc_html($add_heading_1['title']); ?></a>
      <?php if (!empty ($add_heading_2)) { ?>
      | <a href="<?php echo esc_url($add_heading_2['url']); ?>"><?php echo esc_html($add_heading_2['title']); ?></a>
    </p>
    <?php } }?>
    <h1 class="vf-hero__heading">
      <a class="vf-hero__heading_link" href="<?php echo get_home_url(); ?>">
        <?php echo $vf_plugin->get_hero_heading(); ?>
      </a>
    </h1>

    <p class="vf-hero__subheading"><?php echo $vf_plugin->get_hero_text(); ?></p>

    <?php if ($hero_text) {?>
    <p class="vf-hero__text"><?php echo ($hero_text); ?></p>
    <?php } ?>

    <?php
 // Hero link
 if (!empty ($hero_link)) { ?>
    <a class="vf-hero__link"
      href="<?php echo esc_url($hero_link['url']); ?>"><?php echo esc_html($hero_link['title']); ?><svg width="24"
        height="24" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg>
    </a>
    <?php } ?>
  </div>
</section>
<!--/vf-hero-->
