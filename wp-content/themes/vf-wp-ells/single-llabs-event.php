<?php

get_header();
$title = get_the_title();
$type = get_field('labs_type');
$start_date = get_field('labs_start_date');
$start = DateTime::createFromFormat('j F Y', $start_date);

$end_date = get_field('labs_end_date');
$end = DateTime::createFromFormat('j F Y', $end_date);

$application_deadline = get_field('labs_application_deadline');
$topic_area = get_field('labs_topic_area');
$download = get_field('labs_download');
$contact = get_field('labs_contact');
$organisers = get_field('labs_organisers');
$registration_link = get_field('labs_application_form_link');
$image = get_field('labs_image');


if ( ! is_array($image)) {
    $image = null;
  } else {
    $image = wp_get_attachment_image($image['ID'], 'medium', false, array(
      'class'    => 'vf-card__image',
      'loading'  => 'lazy',
      'itemprop' => 'image',
    ));
  }

?>

<section class="vf-hero
   vf-hero--primary


 vf-hero--block

 vf-hero--800   | vf-u-fullbleed | vf-u-margin__bottom--0" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS_2.jpg');  ">
  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading" style="font-size: 34px;">
    <?php echo esc_html($title); ?>
    </h2>
    <p class="vf-hero__subheading"><?php echo esc_html($type['label']); ?></p>
  </div>
</section>
              



<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>


<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
    <?php 
        the_content();
      ?>
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
                  ?>
              <?php } 
              else {
              echo $start->format('j F Y'); 
              } }
      ?>
     </span>   
    </p>
    <?php if ( ! empty($application_deadline)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Application deadline:</span> <span class="vf-u-text-color--grey"><?php echo esc_html($application_deadline); ?></span></p>
    <?php } ?>     
     <?php if ($topic_area) { ?>    
      <p class="vf-text-body vf-text-body--3"><span style="font-weight: 600;">Topic area:</span>&nbsp;<span
            class="vf-u-text-color--grey"><?php echo ($topic_area->name); ?></span></p>
      <?php } ?>                  


            <?php if ( ! empty($contact)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Contact: </span><a href="mailto:<?php echo esc_html($contact); ?>"><?php echo esc_html($contact); ?></a></p>
    <?php } ?>

    <?php if ( ! empty($organisers)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Organiser:</span> <span class="vf-u-text-color--grey"><?php echo esc_html($organisers); ?></span></p>
    <?php } ?>  

       <div class="vf-u-margin__top--400 vf-u-margin__bottom--400">
      <?php if ( ! empty($registration_link)) { ?>
        <div style="display: inline-block;">
          <a href="<?php echo esc_url($registration_link); ?>"><button
          class="vf-button vf-button--primary vf-button--sm">Application form</button></a>
        </div>
        <?php } ?>
            </div>


      <p class="vf-text-body vf-text-body--3" style="font-weight: 600;">Share:</p>
      <?php include(locate_template('partials/social-icons.php', false, false)); ?>
      <div class="vf-social-links">
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
  </div></section>

<?php

get_footer();

?>
