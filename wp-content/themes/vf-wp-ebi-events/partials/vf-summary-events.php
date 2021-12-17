<?php
$post_id = get_the_ID();
$start_date = get_field('vf_event_start_date',$post_id);
$start_time = get_field('vf_event_start_time',$post_id);
$start = DateTime::createFromFormat('j M Y', $start_date);
$start_time_format = DateTime::createFromFormat('H:i', $start_time);
$end_date = get_field('vf_event_end_date',$post_id);
$end_time = get_field('vf_event_end_time',$post_id);
$end_time_format = DateTime::createFromFormat('H:i', $end_time);
$end = DateTime::createFromFormat('j M Y', $end_date);
$end_date_format = DateTime::createFromFormat('j M Y', $end_date);
$locations = get_field('vf_event_location',$post_id);
$event_type = get_field('vf_event_event_type'); 
$public_type = get_field('vf_event_public_subtype'); 
$seminar_type = get_field('vf_event_seminar_subtype'); 
$venue = get_field('vf_event_venue'); 
$summary = get_field('vf_event_summary'); 
$organisers= get_field('vf_event_organisers_listing'); 
$abstract_closing = get_field('vf_event_submission_closing');
$application_closing = get_field('vf_event_application_deadline');
$registration_closing = get_field('vf_event_registration_closing');
$registration_opening_on_site = get_field('vf_event_registration_opening_on-site');
$registration_closing_on_site = get_field('vf_event_registration_closing_on-site');

$now = new DateTime();
$application_date = new DateTime($application_closing);
$registration_date = !empty($registration_closing) ? new DateTime($registration_closing) : '';
$registration_date_on_site = new DateTime($registration_closing_on_site);
$abstract_date = new DateTime($abstract_closing);
$current_date = $now->format('Y-m-d');
$registration_date_formated = !empty($registration_date) ? $registration_date->format('Y-m-d') : '';
$registration_date_formated_on_site = $registration_date_on_site->format('Y-m-d');
$application_date_formated = $application_date->format('Y-m-d');
$abstract_date_formated = $abstract_date->format('Y-m-d');
$public_type_class = $public_type['value'];

?>
<article class="vf-summary| jplist-text-area" data-jplist-item>
  <p class="vf-summary__date custom_font_date"><?php echo $start->format('j F Y'); ?></p>
  <h3 class="vf-summary__title | title">
    <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
      <?php the_title(); ?>
    </a>
  </h3>
  <div class="vf-u-display-none | used-for-filtering">
    <span class="jplist-event-time" data-eventtime="<?php echo $start->format('Ymdhis'); ?>"><?php echo $start->format('j F Y'); ?></span>
    <span class="jplist-event-year year_<?php echo $start->format('Y'); ?>"><?php echo $start->format('Y'); ?></span>
    <span class="jplist-event-type type_<?php echo $public_type_class; ?>"><?php echo $public_type_class; ?></span>
    <?php
    foreach($locations as $location) {
      ?>
      <span class="jplist-event-location location_<?php echo $location;?> | <?php echo $location; ?>"><?php echo $location; ?></span>
    <?php
    }
    ?>

  </div>
  <div class="vf-summary__date vf-text-body custom_font_text_size_extra">
    <span class="type_<?php echo $public_type_class; ?> | <?php echo $public_type_class; ?>">
          <?php
          if (!empty($public_type)){
            echo esc_html($public_type['label']); }
          ?>
        </span>  |
    <span class=""><?php echo split_location_text($locations); ?></span> |
    <span class="">Registration: </span>
    <?php if (!empty(($registration_closing_on_site) || ($registration_closing))) { ?>
    <?php if (($registration_date_formated_on_site >= $current_date) && (!empty($registration_date_formated_on_site)) ) { ?>
    <span class="jplist-event-registration">
          <span class="registration_open | Open">Open</span>

          <?php }

          else if (!empty($registration_date_formated)  && ($registration_date_formated >= $current_date)){
          ?>
            <span class="jplist-event-registration">
          <span class="registration_open | Open">Open</span>

            <?php
            } else if (($registration_date_formated_on_site <= $current_date) && (!empty($registration_date_formated) <= $current_date)) { ?>
              <span class="vf-u-text-color--grey | vf-u-text--nowrap | registration_closed | Closed">Closed</span>
            <?php } } ?>
  </div>
  <p></p>
  <hr class="vf-divider">
</article>


