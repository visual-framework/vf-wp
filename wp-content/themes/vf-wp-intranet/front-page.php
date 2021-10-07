

<?php

get_header();

global $post;
setup_postdata($post);

global $vf_theme;
$current_date = date('Ymd');
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

<?php include(locate_template('partials/insites-container.php', false, false)); ?>

<section class="vf-hero | vf-u-fullbleed" style="--vf-hero--bg-image: url('https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/20201116_Banners_EMBL.org_Option2-04-scaled.jpg');">
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
  <h2 class="vf-hero__heading">
    <a class="vf-hero__heading_link" href="https://www.embl.org/internal-information/coronavirus/">COVID-19 updates</a>
  </h2>
    <p class="vf-hero__text">
      <a class="vf-hero__link" href="https://www.embl.org/internal-information/coronavirus/">Latest updates on Coronavirus for EMBL staff
      <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
        </svg>
      </a>
    </p>
  </div>
</section>

<?php include(locate_template('partials/community-blog-container.php', false, false)); ?>

<hr class="vf-divider">

<?php include(locate_template('partials/seminars-container.php', false, false)); ?>

<hr class="vf-divider">

<?php include(locate_template('partials/events-container.php', false, false)); ?>

<?php 

$vf_theme->the_content();

?>



<?php 

get_footer();

?>

