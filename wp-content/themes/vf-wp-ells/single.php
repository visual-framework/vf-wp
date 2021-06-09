<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');
$show_author = get_field('ells_show_author');
$social_url = get_the_permalink();

get_header();

?>

<section class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--200 | vf-u-margin__bottom--0">
  <div>
    <div class="vf-article-meta-information">
      <div class="vf-author | vf-article-meta-info__author">
        <p class="vf-author__name">
          <a class="vf-link"
            href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
        </p>
        <a class="vf-author--avatar__link | vf-link"
          href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
          <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array('class' => 'vf-author--avatar')); ?>
        </a>
      </div>
      <div class="vf-meta__details">
        <p class="vf-meta__date"><time title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
      </div>
    </div>

  </div>
  <div class="vf-content | vf-u-padding__bottom--800">
    <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
    <p class="vf-lede | vf-u-padding__top--md | vf-u-padding__bottom--xxl">
      <?php echo get_post_meta($post->ID, 'ells_article_intro', true); ?>
    </p>
    <figure class="vf-figure">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>

    <?php the_content(); ?>
  </div>
  <div class="social-media-block">
    <?php include(locate_template('partials/social-icons.php', false, false)); ?>

    <div class="vf-social-links | vf-u-margin__bottom--xxl">
      <h3 class="vf-social-links__heading">
        Share
      </h3>
      <ul class="vf-social-links__list">
        <li class="vf-social-links__item">
          <a class="vf-social-links__link"
            href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $social_url; ?>&amp;via=ELLS_Heidelberg">
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
      </ul>
    </div>
  </div>
</section>
<div class="vf-news-container vf-news-container--featured | vf-u-background-color-ui--off-white | vf-u-margin__bottom--100 | vf-u-padding__top--400 | vf-u-fullbleed">
  <h2 class="vf-section-header__heading vf-u-margin__bottom--400">Recent updates</h2>
  <div class="vf-news-container__content vf-grid vf-grid__col-4">
    <?php
          $args = array(
            'posts_per_page' => 3,
            'post__not_in'   => array( get_the_ID() ),
            'no_found_rows'  => true,
          );

          $cats = wp_get_post_terms( get_the_ID(), 'category' );
          $cats_ids = array();
          foreach( $cats as $related_cat ) {
            $cats_ids[] = $related_cat->term_id;
          }
          if ( ! empty( $cats_ids ) ) {
            $args['category__in'] = $cats_ids;
          }

          $query = new wp_query( $args );

          foreach( $query->posts as $post ) : setup_postdata( $post ); ?>

    <?php include(locate_template('partials/vf-summary--news.php', false, false)); ?>
    <?php endforeach; wp_reset_postdata(); ?>
  </div>
        </div>


<?php include(locate_template('partials/ells-footer.php', false, false)); ?>
