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
$organisers= get_field('vf_event_organisers_listing'); 

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
    <span class="jplist-event-summary">{{ event.field_event_summary }}
      {{ event.body | striptags | safe | truncate(900) }}</span>
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
        <p class="vf-summary__location"> <?php echo implode( ', ', $locations ); ?></p>
      </div>
      <?php /*
              <!-- Registration  -->
              <div class="">
                {% if event.field_event_registration_closing != '' %}
                {%- if event.field_event_registration_closing | dateMoment("unix") >= ('' | dateMoment("unix") ) -%}
                <span class="jplist-event-registration">
                  <span class="vf-text-body vf-text-body--2">Registration</span> <br />
                  <span class="vf-u-text-color--grey | vf-u-text--nowrap">{{ event.field_event_registration_closing | dateMoment('DD MMMM YYYY')}}</span>
                </span>
                {%- else -%}
                <span class="vf-text-body vf-text-body--2">Registration</span> <br />
                <span class="vf-u-text-color--grey | vf-u-text--nowrap">Closed</span>
                {%- endif -%}
                {%- else -%}
                <span class="jplist-event-open-shortly">
                  <span class="vf-text-body vf-text-body--2">Registration</span> <br />
                  <span class="vf-u-text-color--grey | vf-u-text--nowrap">Will open shortly</span>
                </span>
                {%- endif -%}
              </div>

              <!-- Abstract  -->
              {% if event.field_event_submission_closing != '' %}
              <div class="">
                {%- if event.field_event_submission_closing | dateMoment("unix") > ('' | dateMoment("unix") ) -%}
                <span class="jplist-event-submission">
                  <span class="vf-text-body vf-text-body--2">Abstract submission</span> <br />
                  <span class="vf-u-text-color--grey vf-u-text--nowrap">{{ event.field_event_submission_closing | dateMoment('DD MMMM YYYY')}}</span>
                </span>
                {%- else -%}
                <span class="vf-text-body vf-text-body--2">Abstract submission</span> <br />
                <span class="vf-u-text-color--grey vf-u-text--nowrap">Closed</span>
                {%- endif -%}
              </div>
              {%- endif -%}
            </div> {# / vf-cluster__inner #}
          </section> {# / vf-cluster #}
*/?>
</article>
