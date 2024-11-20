<?php
get_header();
$title = get_the_title();
$start_date = get_field('vf_event_internal_start_date');
$start_time = get_field('vf_event_internal_start_time');
$end_date = get_field('vf_event_internal_end_date');
$end_time = get_field('vf_event_internal_end_time');
$locations = get_field('vf_event_internal_location');
$other_location = get_field('vf_event_internal_other_location');
$venue = get_field('vf_event_internal_venue');
$application_closing = get_field('vf_event_internal_application_deadline');
$registration_closing = get_field('vf_event_internal_registration_closing');
$registration_link = get_field('vf_event_internal_registration_link');
$contact = get_field('vf_event_internal_contact');
$contact_name = get_field('vf_event_internal_contact_name');
$register_button = get_field('vf_event_internal_registration_button_text');
$register_button = ucfirst(strtolower($register_button));
$info_text = get_field('vf_event_internal_info_text');
$registration_type = get_field('vf_event_internal_registration_type');
$now = new DateTime();
$topic_terms = get_field('vf_event_internal_events_topic');

// Safely parse dates and times
$start = $start_date ? DateTime::createFromFormat('j M Y', $start_date) : null;
$start_time_format = $start_time ? DateTime::createFromFormat('H:i', $start_time) : null;
$end = $end_date ? DateTime::createFromFormat('j M Y', $end_date) : null;
$end_time_format = $end_time ? DateTime::createFromFormat('H:i', $end_time) : null;
$application_date = $application_closing ? new DateTime($application_closing) : null;
$registration_date = $registration_closing ? new DateTime($registration_closing) : null;

// Custom sorting date check
$customDateSorting = $start_date ? DateTime::createFromFormat('Ymd', $start_date) : null;

// Check for null values where necessary
if ($start === false || $end === false) {
    error_log("Invalid date format detected for start or end dates.");
}
if (!empty($start_time)) {
  $calendar_start_time = 'T' . $start_time_format->format('Hi') . '00';
}
else {
  $calendar_start_time = '';
}

if (!empty($end_time)) {
  $calendar_end_time = 'T' . $end_time_format->format('Hi') . '00';
}
elseif (empty($end_time) && !empty($start_time)) {
  $calendar_end_time = 'T' . $start_time_format->format('Hi') . '00';
}
else {
  $calendar_end_time = '';
}

if (!empty($end_date)) {
  $calendar_end_date = '/' . $end->format('Ymd');
}
else {
  $calendar_end_date = '/' . $start->format('Ymd');
}
display_latest_editor_for_admin(get_the_ID());

?>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <div>
    <?php if (($topic_terms)) { ?>
      <p class="vf-meta__topics | vf-u-margin__bottom--0">
    <span class="topic">
      <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<a class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue ' . esc_attr( $term->slug ) . '"style="color: #373a36; text-decoration: none;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
            echo implode('', $topics_list); }?>
    </span>
  </p>
  <?php } ?>
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <?php the_content(); ?>
  </div>
  <div>
    <figure class="vf-figure | vf-u-margin__bottom--400">
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-figure__image' ) ); ?>
    </figure>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Date:</span>
      <span class="vf-u-text-color--grey">
        <?php 
      // Event dates
      if ( ! empty($start_date)) {
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('j'); ?> - <?php echo $end->format('j M Y'); }
          else {
            echo $start->format('j M'); ?> - <?php echo $end->format('j M Y'); }
              ?>
        <?php } 
        else {
          echo $start->format('j M Y'); 
        } }
        ?> 
      </span> |
      <span style="text-transform: none;">
     <a href="http://www.google.com/calendar/render?action=TEMPLATE&text=<?php the_title(); ?>&dates=<?php echo $start->format('Ymd') . $calendar_start_time; ?><?php echo $calendar_end_date . $calendar_end_time; ?>&sprop=name:" target="_blank" rel="nofollow">Add to calendar</a>
    </span>

    </p>
    <?php 
      // time
      if ( ! empty($start_time)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Time:</span> <span
        class="vf-u-text-color--grey"><?php echo esc_html($start_time); ?> CET</span></p>
    <?php } ?>
    <?php 
      // Location
      if (($locations)) { ?>
        <p class="vf-text-body vf-text-body--3
       location"><span style="font-weight: 600;">Location:</span>
          <?php $location_list = [];
              foreach( $locations as $location ) { 
                $location_list[] = $location->name; }
                echo implode(', ', $location_list); ?>
        </p>
    <?php } ?>
    <?php 
      // Other location
      if ( ! empty($other_location)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Location:</span> <span
        class="vf-u-text-color--grey"><?php echo esc_html($other_location); ?></span></p>
    <?php } ?>
    <?php 
      // venue
      if ( ! empty($venue)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Venue:</span> <span
        class="vf-u-text-color--grey"><?php echo esc_html($venue); ?></span></p>
    <?php } ?>

    <?php 
    // Registration dates
    if ( ! empty($registration_closing)) { ?>
    <p class="vf-text-body vf-text-body--3"><span><span style="font-weight: 600;">
      Registration deadline:</span>
      </span> <span class="vf-u-text-color--grey">
        <?php if ($registration_date < $now) {
          echo 'Closed';
        }  
        else if ($registration_closing) {
          echo esc_html($registration_closing);
        } ?>
      </span></p>
    <?php } 
    // Show info text
    else if (!empty($info_text)) { ?>
    <p class="vf-text-body vf-text-body--3"><span>
        <?php if ($registration_type == 'registration') { ?>
        Registration:
        <?php } else if ($registration_type == 'application'){ ?>
        Application:
        <?php  } ?>
        <span class="vf-u-text-color--grey">
          <?php echo esc_html($info_text); ?>
        </span></p>
    <?php  }
    ?>
    <div class="vf-u-margin__top--400 vf-u-margin__bottom--400">
      <?php 
      // Buttons
      if ( !empty($registration_link)) { 
            if (
              (($registration_date > $now)) 
              || 
              (($application_closing) && ($application_date > $now))) { ?>
      <div style="display: inline-block;">
        <a href="<?php echo esc_url($registration_link); ?>" target="_blank"><button
            class="vf-button vf-button--primary vf-button--sm"><?php echo($register_button); ?></button></a>
      </div>
      <?php }} ?>
    </div>
    <?php if ( ! empty(($application_closing) || ($registration_closing) || ($info_text))) { ?>
    <hr class="vf-divider | vf-u-margin__bottom--400">
    <?php }
    // Organisers
    if( have_rows('vf_event_organisers_event_template') ): ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Organisers: </span></p>
    <?php
      while( have_rows('vf_event_organisers_event_template') ) : the_row();
        $organiser_name = get_sub_field('vf_event_organisers_name');
        $organiser_affiliation = get_sub_field('vf_event_organisers_affiliation'); ?>
    <ul class="vf-list vf-u-margin__bottom--sm">
      <li class="vf-list__item vf-u-margin--0 vf-u-margin__top--md">
        <?php echo esc_html($organiser_name); ?>
        <br>
        <span class="vf-text-body vf-text-body--5 vf-u-text-color--grey"
          style="text-transform: uppercase;"><?php echo esc_html($organiser_affiliation); ?>
        </span>
      </li>
      <?php
      endwhile; ?>
    </ul>
    <hr class="vf-divider | vf-u-margin__bottom--400">
    <?php
  else :
  endif;
    // Contact
    if ( ! empty($contact)) { ?>
    <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Contact: </span><a
        href="mailto:<?php echo esc_attr($contact); ?>"><?php echo esc_html($contact_name); ?></a></p>
    <?php } ?>
  </div>
</div>


<?php get_footer(); ?>
