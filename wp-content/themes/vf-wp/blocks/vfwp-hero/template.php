<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$image = get_field('vf_hero_image');
$hero_link = get_field('vf_hero_link');
$add_heading_1 = get_field('vf_hero_additional_heading_1');
$add_heading_2 = get_field('vf_hero_additional_heading_2');
$hero_text = get_field('vf_hero_text', false, false);
$hero_subheading = get_field('vf_hero_subheading');
$hero_heading = get_field('vf_hero_heading');
$hero_url = get_field('vf_hero_url');
$hero_id = get_field('vf_hero_id');
$hero_navigation = get_field('vf_hero_navigation');

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

$headink_link =''; 
if (!empty($hero_url)) {
  $headink_link = $hero_url['url'];
}


?>


  <style>
    <?php if($hero_id) { echo '#' . ($hero_id); }?>.vf-hero {
      <?php if ($image) { ?>
        --vf-hero--bg-image: url('<?php echo esc_url($image['url']); ?>');
        <?php
      }
      else {
        ?>--vf-hero--bg-image-size: auto 28.5rem;
        <?php
      }
      ?>
    }

  </style>
<section <?php echo 'id="' . $hero_id . '"'; ?> class="vf-hero | vf-u-fullbleed <?php echo esc_attr($spacing_class); ?> | vf-u-margin__bottom--0">
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
      <?php if ($headink_link) { ?>
      <a class="vf-hero__heading_link | heading_link" href="<?php echo esc_url($headink_link); ?>"> <?php } ?>
        <?php echo ($hero_heading); ?>
      <?php if ($headink_link) { ?>  
      </a>
      <?php } ?>
    </h1>

    <?php if ($hero_subheading) {?>
    <p class="vf-hero__subheading"><?php echo ($hero_subheading); ?></p>
    <?php } ?>

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
<?php
if ($hero_navigation) {
if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
} }
else {
  echo '';
}
?>

