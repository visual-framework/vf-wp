<?php

get_header();

global $post;

$title = get_the_title($post->post_parent);

$start_date = get_field(
  'vf_event_start_date', $post->post_parent
);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field(
  'vf_event_end_date', $post->post_parent
);
$end = DateTime::createFromFormat('j M Y', $end_date);

$location = get_field(
  'vf_event_location', $post->post_parent
);

$location = strtoupper($location);

$submission_closing = get_field(
  'vf_event_submission_closing', $post->post_parent
);

$registration_closing = get_field(
  'vf_event_registration_closing', $post->post_parent
);

$summary = get_field(
  'vf_event_summary'
);

$additional_info = get_field(
  'vf_event_additional_info'
);

$event_type = get_field(
  'vf_event_event_type', $post->post_parent
);

$registration_link = get_field(
  'vf_event_registration_link', $post->post_parent
);

$logo_image = get_field('vf_event_logo', $post->post_parent);
$logo_image = wp_get_attachment_image($logo_image['ID'], 'medium', false, array(
    'style'    => 'max-height: 95px; width: auto;',
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$hero_image = get_field('vf_event_hero', $post->post_parent);
$hero_image = wp_get_attachment_url($hero_image['ID'], 'medium', false, array(
    'loading'  => 'lazy',
    'itemprop' => 'image',
  ));

$info_box = get_field('vf_event_info_box', $post->post_parent);
$theme = get_field('vf_event_theme', $post->post_parent);
$cpp_container = get_field('vf_event_cpp_container', $post->post_parent);
$box_items = get_field('vf_event_box_items', $post->post_parent);
$accommodation = get_field('accommodation');
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