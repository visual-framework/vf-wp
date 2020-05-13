<?php

$image = get_field('image');
$title = get_field('title');
$link = get_field('link');
$text = get_field('text');
$section_header = get_field('section_header');
$section_text = get_field('section_text');
$container_image = get_field('container_image');
$container_title = get_field('container_title');
$header_link = get_field('header_link');
$container_text = get_field('container_text');
$add_cards = get_field('add_cards');

?>

<!-- <style>
.vf-card {
  display: block !important;
}
</style> -->

<?php 
if ( get_field('select_type') == 'Single card' ) { ?>

<a href="<?php echo esc_html($link); ?>" 

<?php 
if ( get_field('select_style') == 'Very Easy' ) { ?>
class="vf-card vf-card--is-link vf-card--very-easy"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy' ) { ?>
class="vf-card vf-card--is-link vf-card--easy"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy (primary)' ) { ?>
class="vf-card vf-card--is-link vf-card--easy vf-card-theme--primary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy (secondary)' ) { ?>
class="vf-card vf-card--is-link vf-card--easy vf-card-theme--secondary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy (tertiary)' ) { ?>
class="vf-card vf-card--is-link vf-card--easy vf-card-theme--tertiary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy (quaternary)' ) { ?>
class="vf-card vf-card--is-link vf-card--easy vf-card-theme--quaternary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal' ) { ?>
class="vf-card vf-card--is-link vf-card--normal"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal (primary)' ) { ?>
class="vf-card vf-card--is-link vf-card--normal vf-card-theme--primary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal (secondary)' ) { ?>
class="vf-card vf-card--is-link vf-card--normal vf-card-theme--secondary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal (tertiary)' ) { ?>
class="vf-card vf-card--is-link vf-card--normal vf-card-theme--tertiary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal (quaternary)' ) { ?>
class="vf-card vf-card--is-link vf-card--normal vf-card-theme--quaternary"
<?php    
} ?>>
<img src="<?php echo ($image); ?>" alt="" class="vf-card__image">
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
if ( get_field('select_type') == 'Card container' ) { ?>
<section
  class="vf-card-container | vf-u-background-color--grey--lightest | vf-u-padding__top--xxl | vf-u-padding__bottom--xxl">
  <div class="vf-card-container__inner">
    <div class="vf-section-header">
      <?php
  if ( get_field('is_link') == '1' ): ?>
      <a class="vf-section-header__heading vf-section-header__heading--is-link" href="<?php echo ($header_link); ?>">
        <?php echo ($section_header); ?> <svg aria-hidden="true"
          class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
            fill="" fill-rule="nonzero"></path>
        </svg></a>
      <?php else: ?>
      <h3 class="vf-section-header__heading" href="<?php echo ($header_link); ?>"> <?php echo ($section_header); ?></h3>
      <?php endif; ?>
      <p class="vf-section-header__text"><?php echo ($section_text); ?></p>
    </div>
      
<?php

if($add_cards) {

foreach($add_cards as $add_card){ ?>

<a href="<?php echo $add_card['container_link'] ?>" 

<?php 
if ( get_field('select_style') == 'Very Easy' ) { ?>
class="vf-card vf-card--is-link vf-card--very-easy"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy' ) { ?>
class="vf-card vf-card--is-link vf-card--easy"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy (primary)' ) { ?>
class="vf-card vf-card--is-link vf-card--easy vf-card-theme--primary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy (secondary)' ) { ?>
class="vf-card vf-card--is-link vf-card--easy vf-card-theme--secondary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy (tertiary)' ) { ?>
class="vf-card vf-card--is-link vf-card--easy vf-card-theme--tertiary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Easy (quaternary)' ) { ?>
class="vf-card vf-card--is-link vf-card--easy vf-card-theme--quaternary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal' ) { ?>
class="vf-card vf-card--is-link vf-card--normal"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal (primary)' ) { ?>
class="vf-card vf-card--is-link vf-card--normal vf-card-theme--primary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal (secondary)' ) { ?>
class="vf-card vf-card--is-link vf-card--normal vf-card-theme--secondary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal (tertiary)' ) { ?>
class="vf-card vf-card--is-link vf-card--normal vf-card-theme--tertiary"
<?php    
} ?>

<?php 
if ( get_field('select_style') == 'Normal (quaternary)' ) { ?>
class="vf-card vf-card--is-link vf-card--normal vf-card-theme--quaternary"
<?php    
} ?> >

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