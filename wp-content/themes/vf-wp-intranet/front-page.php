

<?php

get_header();

global $post;
setup_postdata($post);

global $vf_theme;
$title = get_the_title();
?>

<?php 


  
$open_wrap = function($html, $block_name) {
  $html = '
<div class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
' . $html;
return $html;
};

$close_wrap = function($html, $block_name) {
  $html .= '
  </div>
  <div></div>
</div>
<!--/embl-grid-->';
return $html;
};

add_filter(
'vf/__experimental__/theme/content/open_block_wrap',
$open_wrap,
10, 2
);

add_filter(
'vf/__experimental__/theme/content/close_block_wrap',
$close_wrap,
10, 2
);

?>

<?php include(locate_template('partials/explore-container.php', false, false)); ?>

<?php include(locate_template('partials/popular-container.php', false, false)); ?>

<hr class="vf-divider">

<section class="vf-summary-container | embl-grid">
  <div class="vf-section-header">
    <a href="/internal-information/insites" class="vf-section-header__heading vf-section-header__heading--is-link">INsites <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a>
    <p class="vf-section-header__text">EMBL's internal news</p>
  </div>

  <div class="vf-section-content">
    <div class="vf-grid vf-grid__col-3">
    <?php
			$newsLoop = new WP_Query (array('posts_per_page' => 3));
      while ($newsLoop->have_posts()) : $newsLoop->the_post();?>
      <?php include(locate_template('partials/vf-summary-insites.php', false, false)); ?>
      <?php endwhile;?>
      <?php wp_reset_postdata(); ?>

    </div>
  </div>
</section> 

<?php include(locate_template('partials/community-blog-container.php', false, false)); ?>

<section class="vf-summary-container | embl-grid">
  <div class="vf-section-header">
    <a href="/internal-information/events" class="vf-section-header__heading vf-section-header__heading--is-link">Events <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path></svg></a>
    <p class="vf-section-header__text">EMBL's internal events</p>
  </div>
  <div class="vf-grid vf-grid__col-3">
    <?php
			$eventsLoop = new WP_Query (array('posts_per_page' => 2, 'post_type' => 'vf_event'));
      while ($eventsLoop->have_posts()) : $eventsLoop->the_post();?>
      <?php include(locate_template('partials/vf-summary-events.php', false, false)); ?>
      <?php endwhile;?>
      <?php wp_reset_postdata(); ?>
  </div>
</section> 

<?php 

$vf_theme->the_content();

?>

<?php include(locate_template('partials/vf-nearest-location.php', false, false)); ?>

<?php 

get_footer();

?>

