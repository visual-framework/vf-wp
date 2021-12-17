<?php 
// Global Header
?>
<?php include(locate_template('partials/ebi_header.php', false, false)); ?>
<?php


get_header();

global $post;

$event_organiser = get_field('vf_event_organiser');
$social_media_container = get_field('vf_event_social_media', $post->post_parent);
$cpp_container = get_field('vf_event_cpp_container', $post->post_parent);
$home = get_bloginfo('url');
$title = get_the_title();
$event_type = get_field('vf_event_event_type');
$event_type_label = $event_type['label'] . 's';
$event_type_value = $event_type['value'];
$breadcrumb_string = "";

if ($event_type_value == 'seminar') {
  $seminar_type = get_field('vf_event_seminar_subtype');
  $seminar_type_label = $seminar_type['label'];
  $seminar_type_value = $seminar_type['value'];
  $landing_page = $home . "/seminars";
  $members_link_html_string = "<li class=\"vf-breadcrumbs__item\" false=\"\"><a href=\"$landing_page\" class=\"vf-breadcrumbs__link\">$event_type_label</a></li>";
  $breadcrumb_string = $members_link_html_string . "<li class=\"vf-breadcrumbs__item\" aria-current=\"location\">$seminar_type_label</li>";
}

if ($event_type_value == 'public_event') {
  $public_type = get_field('vf_event_public_subtype');
  $public_type_label = $public_type['label'];
  $public_type_value = $public_type['value'];
  $landing_page = $home;
  $members_link_html_string = "<li class=\"vf-breadcrumbs__item\" false=\"\"><a href=\"$landing_page\" class=\"vf-breadcrumbs__link\">$event_type_label</a></li>";
  $breadcrumb_string = $members_link_html_string . "<li class=\"vf-breadcrumbs__item\" aria-current=\"location\">$public_type_label</li>";
}

if ($event_type_value == 'internal_event') {
  $internal_type = get_field('vf_event_internal_subtype');
  $internal_type_label = $internal_type['label'];
  $internal_type_value = $internal_type['value'];
  $landing_page = $home . "/internal-events";
  $members_link_html_string = "<li class=\"vf-breadcrumbs__item\" false=\"\"><a href=\"$landing_page\" class=\"vf-breadcrumbs__link\">$event_type_label</a></li>";
  $breadcrumb_string = $members_link_html_string . "<li class=\"vf-breadcrumbs__item\" aria-current=\"location\">$internal_type_label</li>";
}

$displayed = get_field('vf_event_displayed');
$location = get_field('vf_event_location');
$banner_text = get_field('vf_event_banner_text');
$canceled = get_field('vf_event_canceled');



?>
<nav class="vf-breadcrumbs" aria-label="Breadcrumb">
  <ul class="vf-breadcrumbs__list vf-list vf-list--inline">
    <li class="vf-breadcrumbs__item"  false=""><a href="<?php echo $home;?>" class="vf-breadcrumbs__link">Events</a></li>
    <?php echo $breadcrumb_string; ?>
  </ul><span class="vf-breadcrumbs__heading">Related:</span>
  <ul class="vf-breadcrumbs__list vf-breadcrumbs__list--related vf-list vf-list--inline">
    <li class="vf-breadcrumbs__item" false=""><a href="https://www.embl.org/events" class="vf-breadcrumbs__link">All EMBL events</a></li>
  </ul>
</nav>
<?php     
// vf-hero container
include( plugin_dir_path( __FILE__ ) . 'partials/event-info-hero.php');
?>

<section class="vf-grid vf-grid__col-4">
  <div class="vf-grid__col--span-3 | vf-content">
    <?php the_content(); ?>
  </div>
  <?php 
// info box
include( plugin_dir_path( __FILE__ ) . 'partials/event-info.php'); ?>
</section>

<?php include(locate_template('partials/ebi_footer.php', false, false)); ?>
<?php get_footer(); ?>
