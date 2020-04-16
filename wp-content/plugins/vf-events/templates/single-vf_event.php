<?php

get_header();

global $post;

$start_date = get_field(
  'vf_event_start_date',
  $post->ID
);

$end_date = get_field(
  'vf_event_end_date',
  $post->ID
);

$location = get_field(
  'vf_event_location',
  $post->ID
);

?>
<main class="vf-intro | embl-grid embl-grid--has-centered-content">
  <div><!--empty--></div>
  <div>
    <h1 class="vf-intro__heading"><?php the_title(); ?></h1>
    <div class="vf-content">

      <?php if ( ! empty($start_date)) { ?>
      <h4 class="vf-text vf-text-heading--5">
        <?php esc_html_e('Start Date', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($start_date); ?></p>
      <?php } ?>

      <?php if ( ! empty($end_date)) { ?>
      <h4 class="vf-text vf-text-heading--5">
        <?php esc_html_e('End Date', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($end_date); ?></p>
      <?php } ?>

      <?php if ( ! empty($location)) { ?>
      <h4 class="vf-text vf-text-heading--5">
        <?php esc_html_e('Location', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($location); ?></p>
      <h2><?php esc_html_e('Event Details', 'vfwp'); ?></h2>
      <?php } ?>

      <?php the_content(); ?>

    </div>
    <!--/vf-content-->
  </div>
  <div><!--empty--></div>
</main>
<!--/vf-intro-->
<?php

get_footer();

?>
