<?php 
// Global Header
if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}

get_header();

global $post;

$summary = get_field('vf_event_summary');

$additional_info = get_field('vf_event_additional_info');

$navigation_bar = get_field('vf_event_navigation_bar');
$navigation = get_field('vf_event_navigation');
?>



<?php 
if ($navigation_bar == 1) { ?>
  <nav class="vf-navigation vf-navigation--main">
    <ul class="vf-navigation__list | vf-list--inline">
      <?php
  if( have_rows('vf_event_navigation') ):
    while( have_rows('vf_event_navigation') ) : the_row();
    $menu_item = get_sub_field('vf_event_menu_item');
     ?>
        <li class="vf-navigation__item">
            <a href="<?php echo esc_url($menu_item['url']); ?>" class="vf-navigation__link"><?php echo esc_html($menu_item['title']); ?></a>
        </li>

    <?php endwhile; ?>
    </ul>
</nav>
  <?php endif; }
?>

<?php     

// vf-hero container
include( plugin_dir_path( __FILE__ ) . 'partials/hero.php'); 
?>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
    <?php 
      if ( ! empty($summary)) { 
        echo ($summary);
       } 
      else {
        the_content();
      }
      ?>
    </div>

<?php 
// info box for EMBL CCO
include( plugin_dir_path( __FILE__ ) . 'partials/cco-info.php'); ?>
  
</section>

<section class="vf-content">
    <?php 
      if ($summary) {
        the_content();
      } ?>
    
    <?php if ( ! empty($additional_info)) { ?>
        <h2>
          <?php esc_html_e('Additional Information', 'vfwp'); ?>
        </h2>
        <p><?php echo ($additional_info); ?></p>
    <?php } ?>    
</section>

<?php 
// CPP container
include( plugin_dir_path( __FILE__ ) . 'partials/cpp-container.php'); ?>

<?php 
// Global Footer
if (class_exists('VF_Global_Footer')) {
  VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
}

get_footer();

?>