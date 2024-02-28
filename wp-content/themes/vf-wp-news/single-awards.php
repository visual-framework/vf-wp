<?php
get_header();

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$social_url = get_the_permalink();
$site = get_the_terms( $post->ID , 'award-site' );
$unit = get_the_terms( $post->ID , 'award-unit' );
$type = get_the_terms( $post->ID , 'award-type' );


?>
<div>
  <section class="vf-hero vf-hero--1200 | vf-u-fullbleed" style=" --vf-hero--bg-image-size: auto 28.5rem">
    <div class="vf-hero__content | vf-box | vf-stack vf-stack--400">
      <h1 class="vf-hero__heading"><a class="vf-hero__heading_link" href="https://www.embl.org/news/awards-honours">
          Awards & Honours</a></h1>
      <p class="vf-hero__subheading">Subheading</p>
    </div>
  </section>
</div>
<div
  class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--800 | vf-u-margin__bottom--0">
  <div class="article-left-col | vf-content">
    <aside class="vf-article-meta-information">
      <div class="vf-author | vf-article-meta-info__author">
        <p class="vf-author__name">
          <a class="vf-link"
            href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
        </p>
        <a class="vf-author--avatar__link | vf-link"
          href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
          <?php
           echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array('class' => 'vf-author--avatar')); ?>
        </a>
      </div>
      <div class="vf-meta__details">
        <p class="vf-meta__date" style="margin-top: 0.5rem;"><time title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
      </div>
      <div class="vf-meta__comments">
        <p class="vf-u-margin__bottom--0 vf-u-margin__top--200">
          <?php if (($unit)) { ?>
          <span class="vf-u-text-color--grey">
            <?php $uni_list = [];
            foreach( $unit as $uni ) { 
              $uni_list[] = $uni->name; }
              echo implode(', ', $uni_list); ?></span>
          <?php } ?>
          <?php if (($site)) { ?>
        </p>
        <p class="vf-u-margin__bottom--200 vf-u-margin__top--200">
          <span class="vf-u-text-color--grey">
            <?php $loc_list = [];
        foreach( $site as $loc ) { 
          $loc_list[] = $loc->name; }
          echo implode(', ', $loc_list); ?></span>
          <?php } ?>

        </p>
      </div>
    </aside>
  </div>

  <div class="vf-content | vf-u-padding__bottom--800">
    <?php if (($type)) { ?>
    <span class="vf-badge vf-badge--primary customBadgeGreen">
      <?php $typ_list = [];
        foreach( $type as $typ ) { 
          $typ_list[] = $typ->name; }
          echo implode(', ', $typ_list); ?></span>
    <?php } ?>

    <h1 style="margin-top: 0px;"><?php the_title(); ?></h1>
    <p class="vf-lede | vf-u-padding__top--400 | vf-u-padding__bottom--800 | vf-u-margin__top--0">
      <?php echo get_the_excerpt(); ?>
    </p>
    <?php
      if ( has_post_thumbnail() ) { ?>
    <figure class="vf-figure">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>
    <?php } ?>


    <?php the_content(); ?>
  </div>

  <div class="social-box">

    <?php include(locate_template('partials/social-icons.php', false, false)); ?>

    <div class="vf-social-links | vf-u-margin__bottom--800">
      <h3 class="vf-social-links__heading">
        Share this
      </h3>
      <ul class="vf-social-links__list">
        <li class="vf-social-links__item">
          <a class="vf-social-links__link"
            href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $social_url; ?>&title=<?php echo $title; ?>">
            <span class="vf-u-sr-only">linkedin</span>

            <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--linkedin" width="24" height="24"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
              <use xlink:href="#vf-social--linkedin"></use>
            </svg>
          </a>
        </li>
        <li class="vf-social-links__item">
          <a class="vf-social-links__link"
            href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $social_url; ?>&amp;via=embl">
            <span class="vf-u-sr-only">twitter</span>

            <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--twitter" width="24" height="24"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
              <use xlink:href="#vf-social--twitter"></use>
            </svg>
          </a>

        </li>
        <li class="vf-social-links__item">

          <a class="vf-social-links__link"
            href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $social_url; ?>">
            <span class="vf-u-sr-only">facebook</span>

            <svg aria-hidden="true" class="vf-icon vf-icon--social vf-icon--facebook" width="24" height="24"
              viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
              <use xlink:href="#vf-social--facebook"></use>
            </svg>
          </a>
        </li>

      </ul>
    </div>

  </div>

</div>

<div
  class="vf-news-container vf-news-container--featured | vf-u-background-color-ui--off-white | vf-u-margin__bottom--100 | vf-u-padding__top--400 | vf-u-fullbleed">
  <h2 class="vf-section-header__heading vf-u-margin__bottom--400">More from Awards & Honours</h2>
  <div class="vf-news-container__content vf-grid vf-grid__col-4">
    <?php
  $ids = array();
  $ids[] = get_the_ID();
		$moreAwards = new WP_Query(array(
      'post_type' => 'awards',
      'post__not_in' => array_merge($ids),        
      'posts_per_page' => 3, 
  ));
		while ($moreAwards->have_posts()) : $moreAwards->the_post(); ?>
    <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
  </div>

</div>

<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>


<?php get_footer(); ?>
