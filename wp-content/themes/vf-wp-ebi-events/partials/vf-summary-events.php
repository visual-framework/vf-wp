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

?>



<article class="vf-summary vf-summary--event | jplist-text-area" style="margin-bottom: 1rem; display: block;"
  data-jplist-item>
  <p class="vf-summary__date">
    <?php       // Event dates
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j F Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j F Y'); }
      ?>
    <?php } 
        else {
          echo $start->format('j F Y'); 
        } 
     ?>
  </p>

  <h3 class="vf-summary__title | vf-u-margin__bottom--100 | name ">
    <a href="<?php echo get_permalink(); ?>" class="vf-summary__link">
      <?php the_title(); ?>
    </a>
  </h3>

  <!-- Organisers  -->
  <p class="vf-summary__source">
    <span class="jplist-event-organisers"><?php echo esc_html($organisers); ?></span>
  </p>

  <!-- Summary  -->
  <p class="vf-summary__text | vf-u-display-none | used-for-search-index">
    <span class="jplist-event-summary"><?php echo esc_html($summary); ?></span>
  </p>


  <style>
    #event-information .vf-cluster__inner :first-child {
      --vf-cluster__item--flex: 40% 0 1 !important;
    }

  </style>
  <section class="vf-cluster vf-cluster--400 | vf-text-body vf-text-body--3" id="event-information"
    style="--vf-cluster-alignment: flex-start; --vf-cluster__item--flex: 25% 0 1;">
    <div class="vf-cluster__inner">
      <div>
        <!-- Type  -->
        <p class="vf-summary__text vf-text-heading--5 | type">
          <?php
                if (!empty($public_type)){
                echo esc_html($public_type['label']); }
                else if (!empty($seminar_type)){
                  echo esc_html($seminar_type['label']); } ?>
        </p>

        <!-- Location  -->
        <p class="vf-summary__location | location"> <?php echo implode( ', ', $locations ); ?></p>
      </div>

      <!-- Registration  -->

      <div class="">
        <?php if (!empty(($registration_closing_on_site) || ($registration_closing))) { ?>
        <?php if (($registration_date_formated_on_site >= $current_date) && (!empty($registration_date_formated_on_site)) ) { ?>
        <span class="jplist-event-registration">
          <span class="vf-text-body vf-text-body--2">Registration </span> <br />
          <span
            class="vf-u-text-color--grey | vf-u-text--nowrap"><?php echo esc_html($registration_closing_on_site); ?></span>
        </span>
        <?php } 
        else if ($registration_date_formated >= $current_date){ ?>
        <span class="jplist-event-registration">
          <span class="vf-text-body vf-text-body--2">Registration (Virtual)</span> <br />
          <span
            class="vf-u-text-color--grey | vf-u-text--nowrap"><?php echo esc_html($registration_closing); ?></span>
        </span>

        <?php
        } else if (($registration_date_formated_on_site <= $current_date) && ($registration_date_formated <= $current_date)) { ?>
        <span class="vf-text-body vf-text-body--2">Registration</span> <br />
        <span class="vf-u-text-color--grey | vf-u-text--nowrap">Closed</span>
        <?php } } ?>
      </div>


      <!-- Abstract  -->
      <div class="">
        <?php if (!empty($abstract_closing)) { ?>
        <?php if ($abstract_date_formated >= $current_date) { ?>
        <span class="jplist-event-abstract">
          <span class="vf-text-body vf-text-body--2">Abstract submission</span> <br />
          <span class="vf-u-text-color--grey | vf-u-text--nowrap"><?php echo esc_html($abstract_closing); ?></span>
        </span>
        <?php } else { ?>
        <span class="vf-text-body vf-text-body--2">Abstract submission</span> <br />
        <span class="vf-u-text-color--grey | vf-u-text--nowrap">Closed</span>
        <?php } } ?>
      </div>

    </div>
  </section>
  <hr class="vf-divider">
</article>
