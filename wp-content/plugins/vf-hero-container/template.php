<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$image = get_field('vf_hero_image');
$hero_heading = get_field('vf_hero_heading');
$hero_text = get_field('vf_hero_text');
$hero_link = get_field('vf_hero_link');
$add_heading_1 = get_field('vf_hero_additional_heading_1');
$add_heading_2 = get_field('vf_hero_additional_heading_2');


?>


<section class="vf-hero vf-hero--primary vf-hero--block vf-hero--800 | vf-u-fullbleed | vf-u-margin__bottom--0">
  <style>
    .vf-hero {
      <?php 
        if ($image) { ?>
        --vf-hero--bg-image: url('<?php echo esc_url($image['url']); ?>');
        <?php } 
        else { ?>
        --vf-hero--bg-image-size: auto 28.5rem;
        <?php } ?>
      }
</style>
<div class="vf-hero__content | vf-stack vf-stack--400 ">

<h2 class="vf-hero__heading">
  <a href="<?php echo get_home_url(); ?>">
  <?php echo $hero_heading; ?>
  </a>

  <?php
  //Additional headings
  if (!empty ($add_heading_1)) { ?>
  <span class="vf-hero__heading--additional">
    <a href="<?php echo esc_url($add_heading_1['url']); ?>"><?php echo esc_html($add_heading_1['title']); ?>
    </a>

  <?php if (!empty ($add_heading_2)) { ?>
  | <a href="<?php echo esc_url($add_heading_2['url']); ?>"><?php echo esc_html($add_heading_2['title']); ?>
    </a>
  </span>
  <?php } }?>

</h2>

<p class="vf-hero__subheading"><?php echo $hero_text; ?></p>

<?php
// Hero link
if (!empty ($hero_link)) { ?>
<p class="vf-hero__text"> <a class="vf-hero__link | vf-link" href="<?php echo esc_url($hero_link['url']); ?>"><?php echo esc_html($hero_link['title']); ?><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
        </svg>
      </a>
    </p>
<?php } ?>
</div>
</section>
<!--/vf-hero-->
