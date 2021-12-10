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
$registration_closing_on_site = get_field('vf_event_registration_closing_on-site');

$now = new DateTime();
$application_date = new DateTime($application_closing);
$registration_date = new DateTime($registration_closing);
$registration_date_on_site = new DateTime($registration_closing_on_site);
$abstract_date = new DateTime($abstract_closing);
$current_date = $now->format('Y-m-d');
$registration_date_formated = $registration_date->format('Y-m-d');
$registration_date_formated_on_site = $registration_date_on_site->format('Y-m-d');
$application_date_formated = $application_date->format('Y-m-d');
$abstract_date_formated = $abstract_date->format('Y-m-d');
$seminar_type_class = $seminar_type['value'];
?>
<article class="vf-summary--event | jplist-text-area" data-jplist-item>
  <div class="vf-u-display-none | used-for-filtering">
    <span class="jplist-event-time" data-eventtime="<?php echo $start->format('Ymdhis'); ?>"><?php echo $start->format('j F Y'); ?></span>
    <span class="jplist-event-year year_<?php echo $start->format('Y'); ?>"><?php echo $start->format('Y'); ?></span>
    <span class="jplist-event-type type_<?php echo $seminar_type_class; ?>"><?php echo $seminar_type_class; ?></span>
    <span class="jplist-event-location location_<?php echo implode( ', ', $locations ); ?>"><?php echo implode( ', ', $locations ); ?></span>
  </div>
<div class="vf-grid vf-grid__col-3">
  <span class="vf-grid__col--span-2">
    <p class="vf-summary__date">
      <?php echo $start->format('j F Y'); ?>
    </p>
    <h3 class="vf-summary__title">
      <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
        <?php the_title(); ?>
      </a>
    </h3>
  </span>
  <span class="vf-grid__col--span-1">
    <p class="vf-summary__text vf-text-heading--5 | type_<?php echo $seminar_type_class; ?> | <?php echo $seminar_type_class; ?>">
      <?php
      if (!empty($public_type)){
        echo esc_html($public_type['label']); }
      else if (!empty($seminar_type)){
        echo esc_html($seminar_type['label']); } ?>
    </p>
    <p class="vf-summary__location | location | <?php echo implode( ', ', $locations ); ?> "><?php echo implode( ', ', $locations ); ?></p>
  </span>
</div>
  <hr class="vf-divider">
</article>

