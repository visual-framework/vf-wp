<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$image = get_field('vf_hero_image');
$hero_link = get_field('vf_hero_link');
$add_heading_1 = get_field('vf_hero_additional_heading_1');
$add_heading_2 = get_field('vf_hero_additional_heading_2');
$hero_text = get_field('vf_hero_text', false, false);
$hero_subheading = get_field('vf_hero_subheading');
$hero_heading = get_field('vf_hero_heading');
$search = get_field('vf_hero_search');
$theme = wp_get_theme();
$spacing = get_field('vf_hero_spacing');
$spacing_class = "| vf-hero--";

if ($spacing === 'default') {
  $spacing_class = "";
}
elseif ($spacing === '400') {
$spacing_class .= "{$spacing}";
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
        ?>--vf-hero--bg-image: url('<?php echo esc_url($image['url']); ?>');
        <?php
      }

      else {
        ?>--vf-hero--bg-image-size: auto 28.5rem;
        <?php
      }

      ?>
    }

  </style>
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--200">
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
        <?php echo ($hero_heading); ?>
      </a>
    </h1>

    <?php if ($hero_subheading) {?>
    <p class="vf-hero__subheading"><?php echo ($hero_subheading); ?></p>
    <?php } ?>

    <?php if ($hero_text) {?>
    <p class="vf-hero__text"><?php echo ($hero_text); ?></p>
    <?php } ?>

    <?php if ($search == 1) { 
      if ($theme == 'VF-WP Intranet') { ?>
    <form role="search" method="get" class="vf-form vf-form--search vf-form--search--mini | vf-sidebar vf-sidebar--end"
      action="<?php echo esc_url(home_url('/')); ?>">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item | vf-search__item" style="min-width: unset;">
          <input type="search" class="vf-form__input | vf-search__input" placeholder="Search term"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s">
        </div>
        <!-- <div class="vf-form__item | vf-search__item" style="width: 155px;">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
          <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
            <option value="any" selected="">Everything</option>
            <option value="page" name="post_type[]">Pages</option>
            <option value="insites" name="post_type[]">News</option>
            <option value="events" name="post_type[]">Events</option>
            <option value="people" name="post_type[]">People</option>
            <option value="documents" name="post_type[]">Documents</option>
          </select>
        </div> -->
        <button type="submit" value="<?php esc_attr_e('Search', 'vfwp'); ?>"
          class="vf-search__button | vf-button vf-button--primary">
          <span class="vf-button__text | vf-u-sr-only">Search</span>

          <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
            xmlns:svgjs="http://svgjs.com/svgjs" viewBox="0 0 140 140" width="140" height="140">
            <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
              <path
                d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z"
                fill="#FFFFFF" stroke="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="0"></path>
            </g>
          </svg>

        </button>
      </div>
    </form>
    <p class="vf-text-body vf-text-body--5">Directories: <span><a
          href="/internal-information/documents">Documents</a></span> | <span><a
          href="/internal-information/people">People</a></span> | <span><a
          href="/internal-information/seminars">Seminars</a></span></p>

    <?php }
       else { ?>
    <form role="search" method="get" class="vf-form vf-form--search vf-form--search--mini | vf-sidebar vf-sidebar--end"
      action="<?php echo esc_url(home_url('/')); ?>">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item | vf-search__item">
          <input type="search" class="vf-form__input | vf-search__input"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s" placeholder="Enter your search term">
        </div>
        <button type="submit" class="vf-search__button | vf-button vf-button--primary">
          <span class="vf-button__text | vf-u-sr-only">Search</span>

          <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
            xmlns:svgjs="http://svgjs.com/svgjs" viewBox="0 0 140 140" width="140" height="140">
            <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
              <path
                d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z"
                fill="#FFFFFF" stroke="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="0"></path>
            </g>
          </svg>

        </button>
      </div>
    </form>
    <?php } 
        
        } ?>

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
