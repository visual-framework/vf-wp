<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$social_url = get_the_permalink();
$languages = get_field('languages');
$in_this_issue = get_field('embletc_in_this_issue');
$issue_number = get_field('embletc_issue');


get_header();

the_post();

?>

<?php
$featured_posts = get_field('embletc_issue');
if ($featured_posts) { ?>
<?php foreach( $featured_posts as $post ): 
      setup_postdata($post);
      $main_hero = get_field('embletc_main_story_hero');
      $pdf = get_field('embletc_pdf_link'); 
      $preview = get_field('embletc_preview_image'); 
      ?>
<section class="vf-hero vf-hero--1200 | vf-u-fullbleed | vf-u-margin__top--200"
  style="--vf-hero--bg-image: url('<?php echo esc_url($main_hero['url']); ?>'); margin-bottom: 0; padding: 1rem 0;">
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--400 ">
    <h2 class="vf-hero__heading">
      <a class="vf-hero__heading_link" href="<?php the_permalink(); ?>">EMBLetc.</a>
    </h2>
    <p class="vf-hero__subheading" style="font-size: 26px;"><?php the_title(); ?></p>
  </div>
</section>

<?php endforeach; ?>
<?php wp_reset_postdata(); ?>
<?php } ?>




<section class="vf-grid vf-grid__col-1 | vf-content">
  <div>
    <h3 style="margin-top: 0px; font-weight: 300; font-size: 24px;">Online Magazine of the European Molecular Biology
      Laboratory
    </h3>
    <hr class="vf-divider" style="margin-top: 16px;">
  </div>
</section>
<?php if ( ! post_password_required() ) { ?>

<div class="vf-grid vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2">
    <div class="vf-content">
      <h1 style="font-weight: 500;"><?php the_title(); ?></h1>
    </div>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-content">
  <div class="vf-grid__col--span-2 | vf-u-padding__bottom--400">
    <p class="vf-lede | vf-u-padding__top--400 | vf-u-padding__bottom--800 | vf-u-margin__top--0" style="font-weight: 400;">
      <?php echo get_post_meta($post->ID, 'article_intro', true); ?> </p>
    <figure class="vf-figure">
      <?php
      if ( has_post_thumbnail() ) {
      the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>
    <?php } ?>
    <?php the_content(); ?>
    <?php
    // article source
    if( have_rows('source_article') ): ?>
    <hr class="vf-divider">
    <h4>Source article(s)</h4>
    <?php while( have_rows('source_article') ): the_row();
      $publication_title = get_sub_field('publication_title');
      $publication_link = get_sub_field('publication_link');
      $publication_authors = get_sub_field('publication_authors');
      $publication_source = get_sub_field('publication_source');
      $publication_date = get_sub_field('publication_date');
      $publication_doi = get_sub_field('publication_doi');
      ?>
    <article class="vf-summary vf-summary--publication">
      <h3 class="vf-summary__title">
        <a href="<?php echo esc_url( $publication_link['url']); ?>" class="vf-summary__link">
          <?php echo esc_html( $publication_title); ?>
        </a>
      </h3>
      <p class="vf-summary__author">
        <?php echo esc_html( $publication_authors); ?> </p>
      <p class="vf-summary__source">
        <?php echo esc_html( $publication_source); ?>
        <span class="vf-summary__date"><?php echo esc_html( $publication_date); ?></span>
      </p>
      <p class="vf-summary__text">
        <?php echo esc_html( $publication_doi); ?> </p>
    </article>
    <?php endwhile; ?>
    <?php endif; ?>

    <?php
    // article source (deprecated)
    if( have_rows('article_sources') ): ?>
    <div class="vf-u-margin__top--800 vf-u-margin__bottom--800 | vf-box vf-box--normal vf-box-theme--quinary">
      <h4 class="vf-box__heading">Source articles</h4>
      <div>
        <?php while( have_rows('article_sources') ): the_row();
      $source = get_sub_field('source_link_url');
      $description = get_sub_field('source_description', false, false);?>
        <p class="vf-box__text"><a href="<?php echo esc_url( $source ); ?>"><?php echo ($description) ?></a></p>
        <?php endwhile; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php 
    // related links
    if( ! have_rows('source_article') ): ?>
    <?php endif; 

if( have_rows('related_links') ): ?>
    <hr class="vf-divider">
    <div class="vf-links">
      <h3 class="vf-links__heading">Read more</h3>
      <ul class="vf-links__list | vf-list">
        <?php while( have_rows('related_links') ): the_row();
      $source = get_sub_field('link_url');
      $description = get_sub_field('link_description');?>
        <li class="vf-list__item">
          <a class="vf-list__link" href="<?php echo esc_url( $source ); ?>"><?php echo esc_html($description) ?></a>
        </li>
        <?php endwhile; ?>
      </ul>
    </div>
    <?php endif; ?>

    <?php
    $tags = get_the_tags($post->ID);
    if ($tags) { ?>
    <p class="vf-text-body vf-text-body--3 | tags-inline">Tags:
      <?php $tagslist = array();
   foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-link vf-link--secondary' . $tag->term_id . '">' . $tag->name . '</a>';
   }
   echo implode(', ', $tagslist);
} ?></p>

  </div>
  <div>
    <div class="social-box vf-u-padding__top--400">
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
            <p class="vf-meta__date"><time title="<?php the_time('c'); ?>"
                datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
            <p class="vf-meta__topics"><?php echo get_the_category_list(' '); ?></p>
          </div>
          <?php
  // custom switcher
    if( $languages ): ?>
          <div class="vf-meta__details">
            <p class="vf-text vf-text-heading--5">Available languages</p>
            <ul class="vf-list vf-list--default | vf-list--tight">
              <?php foreach( $languages as $l ):
          include(locate_template('partials/language-switcher.php', false, false)); ?>
              <li class="vf-list__item "><a class="vf-list__link"
                  href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
          <?php if( have_rows('in_this_article') ): ?>
          <div class="vf-meta__details">
            <div class="vf-links vf-links--tight vf-links__list--s">
              <p class="vf-links__heading">In this article</p>
              <ul class="vf-links__list vf-links__list--secondary | vf-list">
                <?php while( have_rows('in_this_article') ): the_row();
        $anchor = get_sub_field('anchor');
        $heading = get_sub_field('heading_description');?>
                <li class="vf-list__item">
                  <a href="<?php echo esc_url( $anchor ); ?>" class="vf-list__link"><?php echo esc_html($heading) ?></a>
                </li>
                <?php endwhile; ?>
              </ul>
            </div>
          </div>
          <?php endif; ?>
        </aside>
      </div>
      <?php include(locate_template('partials/social-icons.php', false, false)); ?>

      <div class="vf-social-links | vf-u-margin__bottom--800 vf-u-margin__top--400">
        <h3 class="vf-social-links__heading">
          Share
        </h3>
        <ul class="vf-social-links__list">
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
  </div>
  <div
    class="vf-grid vf-grid__col-3 | vf-content | vf-u-background-color-ui--off-white  vf-u-padding__bottom--400 vf-u-padding__top--400 vf-u-fullbleed">
    <div class="vf-grid__col--span-2">
      <div class="vf-news-container__content">
        <div class="vf-section-header vf-u-margin__bottom--400">
          <?php if( $issue_number ): ?>
          <?php foreach( $issue_number as $post ): ?>
          <?php setup_postdata($post); ?>
          <h3 class="vf-section-header__heading vf-section-header__heading--is-link" id="section-link"><a
              href="<?php echo the_permalink(); ?>">In this Issue</a><svg aria-hidden="true"
              class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
                fill="" fill-rule="nonzero"></path>
            </svg></h3>
          <?php endforeach; ?>
          <?php wp_reset_postdata(); ?>
          <?php endif; ?> </div>
        <?php if( $in_this_issue ): ?>
        <?php foreach( $in_this_issue as $post ): 

// Setup this post for WP functions (variable must be named $post).
setup_postdata($post); ?>
        <article class="vf-summary vf-summary--news">

          <?php the_post_thumbnail( 'large', array( 'class' => 'vf-summary__image' ) ); ?>
          <h3 class="vf-summary__title">
            <a href="<?php echo the_permalink(); ?>" class="vf-summary__link">
              <?php echo the_title(); ?>
            </a>
          </h3>
          <p class="vf-summary__text">
            <?php echo get_the_excerpt(); ?>
          </p>
        </article>
        <?php endforeach; ?>
        <?php wp_reset_postdata(); ?>
        <?php endif; ?>
      </div>
    </div>
    <div>
    <article class="vf-card vf-card--brand vf-card--bordered">

<img src="<?php echo esc_url($preview['url']); ?>" alt="Image alt text" class="vf-card__image" loading="lazy">
<div class="vf-card__content | vf-stack vf-stack--400">
  <h3 class="vf-card__heading"><a class="vf-card__link" href="<?php echo esc_url($pdf); ?>">Download printable preview <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="currentColor" fill-rule="nonzero"></path>
      </svg>
    </a></h3>
  <p class="vf-card__text">Click here to download a quick overview of all the articles included in this digital issue of EMBLetc.</p>
</div>
</article>    </div>
  </div>
</div>
<?php
    }else{
       echo get_the_password_form($post->ID);
    }
?>
<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>

<?php get_footer(); ?>
