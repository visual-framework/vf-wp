<?php

if ( ! defined('ABSPATH')) exit;

$event_testimonials = get_field('vf_event_testimonials', get_the_ID());
if ( ! is_array($event_testimonials)) {
  return;
}

$event_testimonials = array_slice(array_filter($event_testimonials, function($testimonial) {
  return trim((string) ($testimonial['text'] ?? '')) !== '';
}), 0, 3);

if (empty($event_testimonials)) {
  return;
}
?>

<hr class="vf-divider vf-u-margin__top--800">
<section class="vf-u-margin__top--800 vf-u-margin__bottom--800" aria-labelledby="event-testimonials-heading">
  <h3 class="vf-text vf-text-heading--3 vf-u-margin__bottom--600" id="event-testimonials-heading"><?php esc_html_e('Testimonials', 'vfwp'); ?></h3>
  <div class="vf-grid vf-grid__col-<?php echo count($event_testimonials); ?>">
  <?php foreach ($event_testimonials as $testimonial) {
    $avatar = ! empty($testimonial['avatar'])
      ? wp_get_attachment_image((int) $testimonial['avatar'], 'thumbnail', false, array(
        'class' => 'vf-profile__image vf-profile--medium',
        'loading' => 'lazy',
      ))
      : '';
    $name = trim((string) ($testimonial['name'] ?? ''));
    $affiliation = trim((string) ($testimonial['affiliation'] ?? ''));
  ?>
    <div class="vf-box vf-event-testimonial">
      <blockquote class="vf-blockquote-small">
        <div>“<?php echo nl2br(esc_html(trim($testimonial['text']))); ?>”</div>
        <footer class="vf-event-testimonial__author vf-u-margin__top--600">
          <?php if ($avatar) {
            echo $avatar;
          } else { ?>
          <img class="vf-profile__image vf-profile--medium" src="<?php echo esc_url(get_template_directory_uri() . '/assets/assets/vf-profile/assets/vf-icon--avatar.svg'); ?>" alt="" loading="lazy" />
          <?php } ?>
          <?php if ($name !== '' || $affiliation !== '') { ?>
          <div class="vf-event-testimonial__details">
            <?php if ($name !== '') { ?>
            <div class="vf-blockquote_author"><?php echo esc_html($name); ?></div>
            <?php } ?>
            <?php if ($affiliation !== '') { ?>
            <div class="vf-blockquote_author__details"><?php echo esc_html($affiliation); ?></div>
            <?php } ?>
          </div>
          <?php } ?>
        </footer>
      </blockquote>
    </div>
  <?php } ?>
  </div>
</section>
