<?php
get_header();

// Global Header
if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}
if (class_exists('VF_Breadcrumbs')) {
  VF_Plugin::render(VF_Breadcrumbs::get_plugin('vf_breadcrumbs'));
}

global $post;
$event_post_id = VF_Events_Thank_You::get_parent_event_id();
$event_organiser = get_field('vf_event_organiser', $event_post_id);
$thank_you_content = VF_Events_Thank_You::get_event_thank_you_content($event_post_id);
$event_start_date = get_field('vf_event_start_date', $event_post_id);
$event_end_date = get_field('vf_event_end_date', $event_post_id);
$event_location = get_field('vf_event_location', $event_post_id);
$event_other_location = get_field('vf_event_other_location', $event_post_id);
$event_type = get_field('vf_event_event_type', $event_post_id);
$displayed = get_field('vf_event_displayed', $event_post_id);
$embo_event_name = get_field('vf_event_embo_subtype', $event_post_id);
$ss_event_name = get_field('vf_event_ss_subtype', $event_post_id);
$event_date_label = '';
$event_location_label = '';
$event_type_label = '';

if (!empty($event_start_date)) {
  $start = DateTime::createFromFormat('j M Y', $event_start_date);
  $end = !empty($event_end_date)
    ? DateTime::createFromFormat('j M Y', $event_end_date)
    : false;

  if ($start instanceof DateTime) {
    if ($end instanceof DateTime) {
      if ($start->format('M Y') === $end->format('M Y')) {
        $event_date_label = $start->format('j') . ' - ' . $end->format('j M Y');
      } elseif ($start->format('Y') === $end->format('Y')) {
        $event_date_label = $start->format('j M') . ' - ' . $end->format('j M Y');
      } else {
        $event_date_label = $start->format('j M Y') . ' - ' . $end->format('j M Y');
      }
    } else {
      $event_date_label = $start->format('j M Y');
    }
  }
}

if (!empty($event_other_location)) {
  $event_location_label = $event_other_location;
} elseif (!empty($event_location)) {
  $event_location_label = is_array($event_location)
    ? implode(' and ', $event_location)
    : $event_location;
}

if (!empty($displayed)) {
  $event_type_label = $displayed;
} elseif (is_array($embo_event_name) && !empty($embo_event_name['label'])) {
  $event_type_label = $embo_event_name['label'];
} elseif (
  $event_organiser === 'cco_hd' &&
  is_array($event_type) &&
  !empty($event_type['label']) &&
  in_array($event_type['label'], array('Conference', 'Course', 'Webinar'), true)
) {
  $event_type_label = 'EMBL ' . $event_type['label'];
} elseif (
  $event_organiser === 'science_society' &&
  is_array($ss_event_name) &&
  !empty($ss_event_name['label'])
) {
  $event_type_label = $ss_event_name['label'];
} elseif (is_array($event_type) && !empty($event_type['label'])) {
  $event_type_label = $event_type['label'];
}

$hero_kicker_parts = array_filter(array(
  $event_date_label,
  $event_location_label,
  $event_type_label,
));
$hero_kicker_override = implode(' | ', $hero_kicker_parts);

// vf-hero container
include(plugin_dir_path(__FILE__) . 'partials/hero.php');
?>

<section class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800" style="margin-bottom: 4rem;">
  <div class="vf-grid__col--span-2 | vf-content">
    <h2><?php esc_html_e('Thank you for registering!', 'vfwp'); ?></h2>
    <?php if ( ! empty($thank_you_content)) { ?>
      <?php echo wp_kses_post($thank_you_content); ?>
    <?php } ?>
  </div>
  <div class="vf-content">
    <article class="vf-card vf-card--brand vf-card--bordered">
      <img src="https://www.embl.org/about/info/course-and-conference-office/wp-content/uploads/DSC_4334.jpeg" alt="people at an EMBL event" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading">
          <a class="vf-card__link" href="/events/">
            EMBL Events
            <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg"><path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path></svg>
          </a>
        </h3>
        <p class="vf-card__text">Browse upcoming EMBL courses, conferences and workshops.</p>
      </div>
    </article>
  </div>
</section>

<?php
// Global Footer
if (class_exists('VF_Global_Footer')) {
  VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
}

get_footer();

?>
