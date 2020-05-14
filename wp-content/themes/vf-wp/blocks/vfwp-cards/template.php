<?php

// Block preview in Gutenberg editor
$is_preview = isset($is_preview) && $is_preview;

$type = get_field('select_type');

$style = get_field('select_style');

// Function to output a banner message in the Gutenberg editor only
$admin_banner = function($message, $modifier = 'info') use ($is_preview) {
  if ( ! $is_preview) {
    return;
  }
?>

<div class="vf-banner vf-banner--alert vf-banner--<?php echo $modifier; ?>">
  <div class="vf-banner__content">
    <p class="vf-banner__text">
      <?php echo $message; ?>
    </p>
  </div>
</div>
<!--/vf-banner-->
<?php
};
?>


<?php 
if ( $type === 'single' ) { 

  $image = get_field('image');
  $title = get_field('title');
  $link = get_field('link');
  $text = get_field('text');

if (
    ! $image
    && vf_html_empty($title)
    && vf_html_empty($text)
    && vf_html_empty($link)
  ) {
    $admin_banner(__('Please enter custom content for this card.', 'vfwp'));
    return;
  } ?>

<a href="<?php echo esc_html($link); ?>"

  <?php
  switch($style) {
    case 'very_easy': echo 'class="vf-card vf-card--is-link vf-card--very-easy"'; break;
    case 'easy': echo 'class="vf-card vf-card--is-link vf-card--easy"'; break;
    case 'easy_primary': echo 'class="vf-card vf-card--is-link vf-card--easy vf-card-theme--primary"'; break;
    case 'easy_secondary': echo 'class="vf-card vf-card--is-link vf-card--easy vf-card-theme--secondary"'; break;
    case 'easy_tertiary': echo 'class="vf-card vf-card--is-link vf-card--easy vf-card-theme--tertiary"'; break;
    case 'easy_quaternary': echo 'class="vf-card vf-card--is-link vf-card--easy vf-card-theme--quaternary"'; break;
    case 'normal': echo 'class="vf-card vf-card--is-link vf-card--normal"'; break;
    case 'normal_primary': echo 'class="vf-card vf-card--is-link vf-card--normal vf-card-theme--primary"'; break;
    case 'normal_secondary': echo 'class="vf-card vf-card--is-link vf-card--normal vf-card-theme--secondary"'; break;
    case 'normal_tertiary': echo 'class="vf-card vf-card--is-link vf-card--normal vf-card-theme--tertiary"'; break;
    case 'normal_quaternary': echo 'class="vf-card vf-card--is-link vf-card--normal vf-card-theme--quaternary"'; break;
    } ?> >

<img src="<?php echo $image; ?>" alt="" class="vf-card__image">
<div class="vf-card__content">
  <h3 class="vf-card__title">
    <?php echo esc_html($title); ?>
  </h3>
  <p class="vf-card__text">
    <?php echo esc_html($text); ?>
  </p>
</div>
</a>
<?php } ?>

<?php 
if ( $type === 'container' ) { 
  
  $section_header = get_field('section_header');
  $section_text = get_field('section_text');
  $container_image = get_field('container_image');
  $container_title = get_field('container_title');
  $header_link = get_field('header_link');
  $container_text = get_field('container_text');
  $add_cards = get_field('add_cards');

  if (
    ! $container_image
    && vf_html_empty($container_title)
    && vf_html_empty($container_text)
    && vf_html_empty($section_text)
    && vf_html_empty($section_header)
    && vf_html_empty($header_link)
    && vf_html_empty($add_cards)
  ) {
    $admin_banner(__('Please enter custom content for this container.', 'vfwp'));
    return;
  } ?>
  
<section
  class="vf-card-container | vf-u-background-color--grey--lightest | vf-u-padding__top--xxl | vf-u-padding__bottom--xxl">
  <div class="vf-card-container__inner">
    <div class="vf-section-header">
      <?php
  if ( get_field('is_link') == '1' ): ?>
      <a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php echo $header_link; ?>">
        <?php echo ($section_header); ?> <svg aria-hidden="true"
          class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
            fill="" fill-rule="nonzero"></path>
        </svg></a>
      <?php else: ?>
      <h3 class="vf-section-header__heading" href="<?php echo $header_link; ?>"> <?php echo ($section_header); ?></h3>
      <?php endif; ?>
      <p class="vf-section-header__text"><?php echo $section_text; ?></p>
    </div>
      
<?php

if($add_cards) {

foreach($add_cards as $add_card){ ?>

    <a href="<?php echo $add_card['container_link'] ?>" 

    <?php
    switch($style) {
      case 'very_easy': echo 'class="vf-card vf-card--is-link vf-card--very-easy"'; break;
      case 'easy': echo 'class="vf-card vf-card--is-link vf-card--easy"'; break;
      case 'easy_primary': echo 'class="vf-card vf-card--is-link vf-card--easy vf-card-theme--primary"'; break;
      case 'easy_secondary': echo 'class="vf-card vf-card--is-link vf-card--easy vf-card-theme--secondary"'; break;
      case 'easy_tertiary': echo 'class="vf-card vf-card--is-link vf-card--easy vf-card-theme--tertiary"'; break;
      case 'easy_quaternary': echo 'class="vf-card vf-card--is-link vf-card--easy vf-card-theme--quaternary"'; break;
      case 'normal': echo 'class="vf-card vf-card--is-link vf-card--normal"'; break;
      case 'normal_primary': echo 'class="vf-card vf-card--is-link vf-card--normal vf-card-theme--primary"'; break;
      case 'normal_secondary': echo 'class="vf-card vf-card--is-link vf-card--normal vf-card-theme--secondary"'; break;
      case 'normal_tertiary': echo 'class="vf-card vf-card--is-link vf-card--normal vf-card-theme--tertiary"'; break;
      case 'normal_quaternary': echo 'class="vf-card vf-card--is-link vf-card--normal vf-card-theme--quaternary"'; break;
      } ?>>

    <img src="<?php echo $add_card['container_image'] ?>" alt="" class="vf-card__image">
    <div class="vf-card__content">
      <h3 class="vf-card__title">
        <?php echo $add_card['container_title'] ?>
      </h3>
      <p class="vf-card__text">
        <?php echo $add_card['container_text'] ?>
      </p>
    </div>
    </a>

<?php } } ?>

  </div>
</section>

<?php } ?>