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
$contact = get_field('tb_contact');
$organisers = get_field('tb_organisers');

?>
<section class="vf-hero vf-hero--primary vf-hero--block vf-hero--800 | vf-u-fullbleed | vf-u-margin__bottom--0" style="--vf-hero--bg-image: url('https://wwwdev.embl.org/ells/wp-content/uploads/2020/09/20200909_Masthead_ELLS.jpg');">
  <div class="vf-hero__content | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading" style="font-size: 34px;">
    ELLS TeachingBase 
    </h2>
    <p class="vf-hero__subheading">Ut congue, sapien ut placerat hendrerit, lectus erat</p>
  </div>
</section>
              



<?php

if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}

?>

<div class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--200 | vf-u-margin__bottom--0 | vf-content">
  <div></div>
<h1><?php the_title(); ?></h1>
</div>
<section class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--200 | vf-u-margin__bottom--0">
 <div>
    <div class="vf-article-meta-information">
    <div class="vf-author | vf-article-meta-info__author">
    <p class="vf-author__name">
        <a class="vf-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
    </p>
        <a class="vf-author--avatar__link | vf-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
        <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array('class' => 'vf-author--avatar')); ?>
        </a>
    </div>
        <div class="vf-meta__details">
        <p class="vf-meta__date"><time title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
        </div>
    </div>

 </div>
 <div>

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
    <?php if ( ! empty($type_of_resource)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Type of resource:</span> <span class="vf-u-text-color--grey"><?php echo esc_html($type_of_resource); ?></span></p>
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
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Contact: </span><a href="mailto:<?php echo esc_html($contact); ?>"><?php echo esc_html($contact); ?></a></p>
    <?php } ?>

    <?php if ( ! empty($organisers)) { ?>
    <p class="vf-text-body vf-text-body--3 | vf-u-text--nowrap"><span
        style="font-weight: 600;">Organiser:</span> <span class="vf-u-text-color--grey"><?php echo esc_html($organisers); ?></span></p>
    <?php } ?>  

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

</section>
<section class="vf-u-background-color-ui--off-white | vf-u-margin__bottom--100 | vf-u-padding__top--600 | vf-u-padding__bottom--400 | vf-u-fullbleed">
      <h3 class="vf-section-header__heading | vf-u-margin__bottom--400">See also</h3>
      <div class="vf-grid vf-grid__col-3">
        <?php
    $teachingbaseMore = new WP_Query (array(
      'posts_per_page' => 3, 
      'post_type' => 'teachingbase', 
      'post__not_in'   => array( get_the_ID() ),  ));

while ($teachingbaseMore->have_posts()) : $teachingbaseMore->the_post(); ?>

        <?php include(locate_template('partials/vf-card--article-more.php', false, false)); ?>
        <?php endwhile;?>
      <?php wp_reset_postdata(); ?>
      </div>
  </section>

<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
