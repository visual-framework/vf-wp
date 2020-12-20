<?php
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
<section class="vf-hero
   vf-hero--primary


 vf-hero--block

 vf-hero--800   | vf-u-fullbleed | vf-u-margin__bottom--0" style="
--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS.jpg');  ">
  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading" style="font-size: 34px;">
    ELLS TeachingBase 
    </h2>
    <p class="vf-hero__subheading">Chromosome structure and dynamics</p>
  </div>
</section>
              



<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>
<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <h1>
      <?php the_title(); ?>
    </h1>
    <div class="vf-meta__details">
      <p class="vf-author__name"><span class="vf-meta__date"><time title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>, by <a
          class="vf-link"
          href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> in
        <?php echo get_the_category_list(','); ?>
      </p>
    </div>
  </div>

  <div></div>
</div>


<section class="vf-grid vf-grid__col-3">
  <div class="vf-grid__col--span-2 | vf-content">
  <?php
      if (has_post_thumbnail()) {
        $caption = get_the_post_thumbnail_caption();
      ?>
    <figure class="vf-figure">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image | vf-u-margin__bottom--200', 'style' => 'border: 1px solid #d0d0ce')); ?>
      <?php if ( ! vf_html_empty($caption)) { ?>
      <figcaption class="vf-figure__caption">
        <?php echo esc_html($caption); ?>
      </figcaption>
      <?php } ?>
    </figure>
    <?php } ?>

    <?php

      // the_content();
      $vf_theme->the_content();

      ?>
  </div>
  <div>
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

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
