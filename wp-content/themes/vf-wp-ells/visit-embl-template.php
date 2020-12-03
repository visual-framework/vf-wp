<?php
/**
* Template Name: Visit EMBL template
*/
get_header();

$type = get_field('visit_type');
$start_date = get_field('visit_start_date');
$start = DateTime::createFromFormat('j F Y', $start_date);

$end_date = get_field('visit_end_date');
$end = DateTime::createFromFormat('j F Y', $end_date);

$download = get_field('visit_download');
$image = get_field('visit_image');

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

<style>
  .vf-masthead {
    --vf-masthead__bg-image: url(https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS_2.jpg);
    --global-theme-fg-color: #fff;
    --global-theme-bg-color: #3b6fb6;
  }

  .vf-masthead--with-title-block .vf-masthead__title:before {
    --global-theme-bg-color: #3b6fb6;
  }

</style>
<div class="vf-masthead vf-masthead--with-title-block | vf-u-margin__bottom--800
" style="background-image: url(https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS_2.jpg);
         --local-theme-fg-color:#FFFFFF;" data-vf-js-masthead>
  <div class="vf-masthead__inner">
    <div class="vf-masthead__title">
      <h1 class="vf-masthead__heading">
        <a href="JavaScript:Void(0);" class="vf-masthead__heading__link">
          <?php echo get_the_title(); ?>
        </a>
      </h1>
      <h2 class="vf-masthead__subheading">
        <span class="vf-masthead__location">
          <?php echo ($type['label']); ?>
        </span>
      </h2>
    </div>
  </div>
</div>

<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
    <?php 
        the_content();
      ?>
  </div>
  <div>
    <article class="vf-card vf-card-theme--secondary vf-card--normal | vf-u-margin__bottom--600">
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
      <?php
          if( have_rows('visit_contact') ): ?>
            <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Contact:<span class="vf-u-text-color--grey">
            <?php while( have_rows('visit_contact') ) : the_row();
                $emails = get_sub_field('visit_emails');
                echo ($emails);
            endwhile;
        else : ?>
        </span></p>
        <?php endif; ?>

      <?php
            if( have_rows('visit_organisers') ): ?>
              <p class="vf-text-body vf-text-body--3" style="font-weight: 400;">Organisers:<span class="vf-u-text-color--grey">
            <?php while( have_rows('visit_organisers') ) : the_row();
                $organisers = get_sub_field('visit_person', false, false);
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
