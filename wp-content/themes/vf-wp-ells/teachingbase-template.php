<?php
/**
* Template Name: TeachingBase template
*/
get_header();

$type = get_field('tb_type');
$start_date = get_field('tb_start_date');
$start = DateTime::createFromFormat('j F Y', $start_date);

$end_date = get_field('tb_end_date');
$end = DateTime::createFromFormat('j F Y', $end_date);

$type_of_resource = get_field('tb_type_of_resource');
$age_group = get_field('tb_age_group');
$topic_area = get_field('tb_topic_area');
$download = get_field('tb_download');
$image = get_field('tb_image');


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

<section class="vf-hero vf-hero--primary vf-hero--1200 | vf-u-fullbleed" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS.jpg');  ">

  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading" style="font-size: 24px;">
      <?php echo get_the_title(); ?>
      <span class="vf-hero__heading--additional">
      <?php echo ($type['label']); ?>
      </span>
    </h2>
  </div>
</section>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
    <?php 
        the_content();
      ?>
  </div>
  <div>
    <article class="vf-card vf-card--normal vf-card-theme--tertiary | vf-u-margin__bottom--xl">
      <?php
        if ($image) {
        echo $image;
        }
       ?>
      <div class="vf-card__content">
        <p class="vf-card__text | vf-u-margin__bottom--0" style="text-align: center;">
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

        </p>
      </div>
    </article>
    <div>
      <?php if ($type_of_resource) { ?>  
      <p class="vf-text-body vf-text-body--3" style="font-weight: 400;"> <span class="vf-badge">
            Application deadline:</span>&nbsp;<span
            class="vf-u-text-color--grey"><?php echo ($type_of_resource); ?></span></p>
      <?php } ?>    
      <?php if ($topic_area) { ?>    
      <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Topic area:&nbsp;<span
            class="vf-u-text-color--grey"><?php echo ($topic_area); ?></span></p>
      <?php } ?>          
      <?php if ($age_group) { ?>    
      <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Age group:&nbsp;<span
            class="vf-u-text-color--grey"><?php echo ($age_group); ?></span></p>
      <?php } ?>          
      <?php
            if( have_rows('tb_contact') ):?>
                <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Contact:<span class="vf-u-text-color--grey">
            <?php while( have_rows('tb_contact') ) : the_row();
                $emails = get_sub_field('tb_emails');
                echo ($emails);
            endwhile;
            else : ?>
            </span></p>
            <?php endif; ?>

            <?php
            if( have_rows('tb_organisers') ): ?>
                <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Organisers:<span class="vf-u-text-color--grey">
            <?php while( have_rows('tb_organisers') ) : the_row();
                $organisers = get_sub_field('tb_person', false, false);
                echo ($organisers);
            endwhile;
        else : ?>
        </span></p>
       <?php endif; ?>

      <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Share:</p>
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
  </div>
</section>

<?php

get_footer();

?>
