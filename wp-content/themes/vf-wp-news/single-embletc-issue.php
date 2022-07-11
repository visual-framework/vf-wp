<?php

/**
* Template Name: EMBLetc
*/

get_header();

$main_story = get_field('embletc_main_stories');
$other_stories = get_field('embletc_other_stories');
$teaser_text = get_field('embletc_main_story_teaser');
$main_image = get_field('embletc_main_story_image');
$thumb = $main_image['sizes'][ 'medium' ];
$issue_number = get_the_title();
?>

<section class="vf-hero vf-hero--1200 | vf-u-fullbleed"
  style="--vf-hero--bg-image: url('<?php echo $art_slider_image_url; ?>'); margin-bottom: 0;">
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading">
      <a class="vf-hero__heading_link" href="<?php echo get_the_permalink(); ?>">EMBL etc.</a>
    </h2>
    <p class="vf-hero__subheading"><?php echo $issue_number; ?></p>
  </div>
</section>
<div class="vf-grid vf-grid__col-6 | vf-content">
  <div></div>
  <div class="vf-grid__col--span-4">
  <?php
if( $main_story): ?>
    <?php foreach( $main_story as $post ): 

        // Setup this post for WP functions (variable must be named $post).
        setup_postdata($post); ?>
<?php the_post_thumbnail( 'large', array( 'class' => 'vf-card__image' ) ); ?>
<h1 style="margin-top: 12px;">
<?php echo the_title(); ?>
</h1>

<div>
  
  <figure class="vf-figure | vf-figure--align vf-figure--align-inline-end">
    <?php
    if( $main_image ) { ?>
      <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($main_image['alt']); ?>" /> <?php } ?>
  </figure>
<?php echo $teaser_text; ?>

</div>
<div class="vf-section-header">
   <h2 class="vf-section-header__heading vf-section-header__heading--is-link" id="section-link"><a href="JavaScript:Void(0);">Read more</a><svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
       <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
     </svg></h2>
 </div>


    <?php endforeach; ?>
    <?php 
    // Reset the global post object so that the rest of the page works correctly.
    wp_reset_postdata(); ?>
<?php endif; ?>  
</div>
  <div></div>
</div>
<?php


if( $other_stories ):
 ?>
 <section class="vf-card-container vf-card-container__col-3 | vf-u-background-color--grey--lightest vf-u-fullbleed vf-u-margin__top--800" style="--vf-card__image--aspect-ratio: 16 / 9;">
  <div class="vf-card-container__inner">
    <div class="vf-section-header">
    <h2 class="vf-card__heading">  
    Other stories
      </h2>
    </div>
<?php foreach( $other_stories as $post ): 

// Setup this post for WP functions (variable must be named $post).
setup_postdata($post); ?>
<article class="vf-card vf-card--brand vf-card--bordered">

<?php the_post_thumbnail( 'large', array( 'class' => 'vf-card__image' ) ); ?>
<div class="vf-card__content | vf-stack vf-stack--400">
  <h3 class="vf-card__heading"><a class="vf-card__link" href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?> <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path>
      </svg>
    </a></h3>
  <p class="vf-card__text"><?php echo get_the_excerpt(); ?></p>
</div>
</article>
<?php endforeach; ?>
</ul>
<?php 
// Reset the global post object so that the rest of the page works correctly.
wp_reset_postdata(); ?>
<?php endif; ?>
</div>
</section>

<?php get_footer(); ?>

