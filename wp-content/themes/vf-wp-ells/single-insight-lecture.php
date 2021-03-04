<?php

get_header();

$title=get_the_title();
$type = get_field('il_type');
$start_date = get_field('il_start_date');
$start = DateTime::createFromFormat('j F Y', $start_date);

$end_date = get_field('il_end_date');
$end = DateTime::createFromFormat('j F Y', $end_date);

$application_deadline = get_field('il_application_deadline');
$age_group = get_field('il_age_group');
$topic_area = get_field('il_topic_area');
$download = get_field('il_download');
$contact = get_field('il_contact');
$organisers = get_field('il_organisers');
$registration_link = get_field('il_registration_link');

?>



<section class="vf-hero vf-u-fullbleed | vf-u-margin__bottom--0" style="--vf-hero--bg-image-size: auto 28.5rem">
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
    <h2 class="vf-hero__heading">
      EMBL Insight Lectures </h2>
    <p class="vf-hero__text">Morbi dictum purus sit amet purus blandit, quis facilisis mauris semper</p>
  </div>
</section>

<?php
if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}
?>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
    <div>
      <h1><?php the_title(); ?></h1>
      <?php the_content(); ?>
    </div>
  </div>
  <div>
    <figure class="vf-figure">
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-figure__image' ) ); ?>
    </figure>
    <div>
      <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Date:</span>
        <span class="vf-u-text-color--grey">
          <?php 
            if ( ! empty($start_date)) {
              if ($end_date) { 
              if ($start->format('F') == $end->format('F')) {
                  echo $start->format('j'); ?> - <?php echo $end->format('j F Y'); }
              else {
                  echo $start->format('j F'); ?> - <?php echo $end->format('j F Y'); }
               } 
              else {
              echo $start->format('j F Y'); 
              } }
          ?>
        </span>
      </p>
      <?php if ( ! empty($application_deadline)) { ?>
      <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span style="font-weight: 600;">Registration
          deadline:</span> <span class="vf-u-text-color--grey"><?php echo esc_html($application_deadline); ?></span></p>
      <?php } ?>
      <?php if ($topic_area) { ?>
      <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Topic area:</span>&nbsp;<span
          class="vf-u-text-color--grey"><?php echo ($topic_area->name); ?></span></p>
      <?php } ?>
      <?php if ($age_group) { ?>
      <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Age group:</span>&nbsp;<span
          class="vf-u-text-color--grey"><?php echo ($age_group->name); ?></span></p>
      <?php } ?>

      <?php if ( ! empty($contact)) { ?>
      <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span style="font-weight: 600;">Contact: </span><a
          href="mailto:<?php echo esc_html($contact); ?>"><?php echo esc_html($contact); ?></a></p>
      <?php } ?>

      <?php if ( ! empty($organisers)) { ?>
      <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span style="font-weight: 600;">Organiser:</span>
        <span class="vf-u-text-color--grey"><?php echo esc_html($organisers); ?></span></p>
      <?php } ?>

      <div class="vf-u-margin__top--400 vf-u-margin__bottom--400">
        <?php if ( ! empty($registration_link)) { ?>
        <div style="display: inline-block;">
          <a href="<?php echo esc_url($registration_link); ?>"><button
              class="vf-button vf-button--primary vf-button--sm">Register</button></a>
        </div>
        <?php } ?>
      </div>

      <p class="vf-text-body vf-text-body--3" style="font-weight: 600;">Share:</p>
      <?php include(locate_template('partials/social-icons.php', false, false)); ?>
      <div class="vf-social-links social-media-block">
        <ul class="vf-social-links__list">
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="JavaScript:Void(0);">
              <span class="vf-u-sr-only">
                twitter
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--twitter" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--twitter">
                </use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="JavaScript:Void(0);">
              <span class="vf-u-sr-only">
                facebook
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--facebook" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--facebook">
                </use>
              </svg>
            </a>
          </li>
          <li class="vf-social-links__item">
            <a class="vf-social-links__link" href="JavaScript:Void(0);">
              <span class="vf-u-sr-only">
                instagram
              </span>
              <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--instagram" width="24" height="24"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                <use xlink:href="#vf-social--instagram">
                </use>
              </svg>
            </a>
          </li>
        </ul>
      </div>
      <?php
        if( $download ): ?>
      <p><a class="vf-link" href="<?php echo $download['url']; ?>">Download</a></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="vf-news-container vf-news-container--featured | vf-u-background-color-ui--off-white | vf-u-fullbleed | vf-u-margin__bottom--100 | vf-u-padding__top--600">
  <h3 class="vf-section-header__heading | vf-u-margin__bottom--400">Past Insight Lectures</h3>
  <div class="vf-news-container__content vf-grid vf-grid__col-4">
    <?php
    $insightMore = new WP_Query (array(
      'posts_per_page' => 4, 
      'post_type' => 'insight-lecture',
      'post__not_in'   => array( get_the_ID() ), ));
      while ($insightMore->have_posts()) : $insightMore->the_post(); ?>
    <?php include(locate_template('partials/vf-summary-lecture-more.php', false, false)); ?>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
  </div>
</section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
