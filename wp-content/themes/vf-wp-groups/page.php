<?php

get_header();

global $post;
setup_postdata($post);

global $vf_theme;
$title = get_the_title();
?>

<?php 

$layout = get_field('page_layout');

if ($layout == 'full') { 
  
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
<?php }

else { ?>

  <section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <h1 class="vf-text vf-text-heading--1">
        <?php the_title(); ?>
      </h1>
      <?php

      // the_content();
      $vf_theme->the_content();

      ?>
    </main>
    <?php if (is_active_sidebar('sidebar-page')) { ?>
    <aside class="vf-inlay__content--additional">
      <?php vf_sidebar('sidebar-page'); ?>
    </aside>
    <?php } ?>
  </div>
</section>

<?php }

get_footer();

?>