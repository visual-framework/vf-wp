<?php

get_header();

global $post;

$summary = get_field('vf_event_summary');

$additional_info = get_field('vf_event_additional_info');

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

get_footer();

?>