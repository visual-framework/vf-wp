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

$submission_closing = get_field(
  'vf_event_submission_closing',
  $post->ID
);

$submission_opening = get_field(
  'vf_event_submission_opening',
  $post->ID
);

$registration_opening = get_field(
  'vf_event_registration_opening',
  $post->ID
);

$registration_closing = get_field(
  'vf_event_registration_closing',
  $post->ID
);

$summary = get_field(
  'vf_event_summary',
  $post->ID
);

$additional_info = get_field(
  'vf_event_additional_info',
  $post->ID
);

$registration_fee = get_field(
  'vf_event_registration_fee',
  $post->ID
);

$canceled = get_field(
  'vf_event_canceled',
  $post->ID
);

$venue = get_field(
  'vf_event_venue',
  $post->ID
);

$unique_identifier = get_field(
  'vf_event_unique_identifier',
  $post->ID
);

$event_type = get_field(
  'vf_event_event_type',
  $post->ID
);

$more_information_link = get_field(
  'vf_event_more_information_link',
  $post->ID
);

$registration_link = get_field(
  'vf_event_registration_link',
  $post->ID
);

$start_time = get_field(
  'vf_event_start_time',
  $post->ID
);

$end_time= get_field(
  'vf_event_end_time',
  $post->ID
);

?>
<main class="vf-intro | embl-grid embl-grid--has-centered-content">
  <div><!--empty--></div>
  <div>
    <h1 class="vf-intro__heading"><?php the_title(); ?></h1>
    <div class="vf-content">

      <?php if ( ! empty($start_date)) { ?>
      <h4>
        <?php esc_html_e('Start Date', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($start_date); ?></p>
      <?php } ?>

      <?php if ( ! empty($end_date)) { ?>
      <h4>
        <?php esc_html_e('End Date', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($end_date); ?></p>
      <?php } ?>

      <?php if ( ! empty($start_time)) { ?>
      <h4>
        <?php esc_html_e('Start Time', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($start_time); ?></p>
      <?php } ?>

      <?php if ( ! empty($end_time)) { ?>
      <h4>
        <?php esc_html_e('End Time', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($end_time); ?></p>
      <?php } ?>

      <?php if ( ! empty($location)) { ?>
      <h4>
        <?php esc_html_e('Location', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($location); ?></p>
      <?php } ?>

      <?php if ( ! empty($venue)) { ?>
      <h4>
        <?php esc_html_e('Venue', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($venue); ?></p>
      <?php } ?>

      <h4><?php esc_html_e('Description', 'vfwp'); ?></h4>
      <?php the_content(); ?>

      <?php if ( ! empty($summary)) { ?>
      <h4>
        <?php esc_html_e('Summary', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($summary); ?></p>
      <?php } ?>

      <?php if ( ! empty($additional_info)) { ?>
      <h4>
        <?php esc_html_e('Additional Information', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($additional_info); ?></p>
      <?php } ?>

      <?php if ( ! empty($event_type)) { ?>
      <h4>
        <?php esc_html_e('Event Type', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($event_type); ?></p>
      <?php } ?>

      <?php if ( ! empty($registration_opening)) { ?>
      <h4>
        <?php esc_html_e('Registration Opening', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($registration_opening); ?></p>
      <?php } ?>

      <?php if ( ! empty($registration_closing)) { ?>
      <h4>
        <?php esc_html_e('Registration Closing', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($registration_closing); ?></p>
      <?php } ?>

      <?php if ( ! empty($submission_opening)) { ?>
      <h4>
        <?php esc_html_e('Submission Opening', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($submission_opening); ?></p>
      <?php } ?>

      <?php if ( ! empty($submission_closing)) { ?>
      <h4>
        <?php esc_html_e('Submission Closing', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($submission_closing); ?></p>
      <?php } ?>

      <?php if ( ! empty($registration_fee)) { ?>
      <h4>
        <?php esc_html_e('Registration Fee', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($registration_fee); ?></p>
      <?php } ?>

      <?php if ( ! empty($registration_link)) { ?>
      <h4>
        <?php esc_html_e('Registration Link', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($registration_link); ?></p>
      <?php } ?>

      <?php if ( ! empty($more_information_link)) { ?>
      <h4>
        <?php esc_html_e('More Information Link', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($more_information_link); ?></p>
      <?php } ?>

      <?php if ( ! empty($canceled)) { ?>
      <h4>
        <?php esc_html_e('Canceled', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($canceled); ?></p>
      <?php } ?>

      <?php if ( ! empty($unique_identifier)) { ?>
      <h4>
        <?php esc_html_e('Unique Identifier', 'vfwp'); ?>
      </h4>
      <p><?php echo esc_html($unique_identifier); ?></p>
      <?php } ?>

    </div>
    <!--/vf-content-->
  </div>
  <div><!--empty--></div>
</main>
<!--/vf-intro-->
<?php

get_footer();

?>

