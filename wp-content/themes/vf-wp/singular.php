<?php

get_header();

global $post;
setup_postdata($post);

global $vf_theme;

$title = $vf_theme->get_title();

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

<?php
if ( has_block( 'acf/vfwp-intro', $post ) ) {  

parse_blocks( 'acf/vfwp-intro' ); } 

else if ( has_block( 'acf/vfwp-page-header', $post ) ) {

parse_blocks( 'acf/vfwp-page-header' ); }

else { ?>

<section class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
  <h1 class="vf-text vf-text-heading--1">
        <?php echo $title;?>
      </h1>
  </div>
  <div></div>
</section>

<?php }

$vf_theme->the_content();

?>

<?php if (comments_open() || get_comments_number()) { ?>
<section class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div>
    <?php comments_template(); ?>
  </div>
  <div></div>
</section>
<!--/embl-grid-->
<?php } ?>

<?php

get_footer();

?>
