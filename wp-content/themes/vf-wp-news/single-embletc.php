<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$social_url = get_the_permalink();
$languages = get_field('languages');

get_header();

the_post();

?>

<main
  class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--800 | vf-u-margin__bottom--0">
  <div class="article-left-col">
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
      <?php if( have_rows('in_this_article') ): ?>
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
      <?php endif; ?>

    </aside>
    <?php

  if( get_field('press_contact') == 'EMBL Generic' ) { ?>
    <article class="vf-card vf-card--brand vf-card--bordered | vf-u-margin__top--800">
    <div class="vf-card__content | vf-stack vf-stack--400">
    <h3 class="vf-card__heading" style="font-size: 21px;">EMBL Press Office</h3>
    <p class="vf-card__text">Meyerhofstraße 1</br> 69117 Heidelberg</br> Germany
      </p>
      <p class="vf-card__text"><a class="vf-card__link" href="mailto:media@embl.org">media@embl.org</a></br>+49 6221 387-8726</p>
    </div>
  </article>
    <?php }

else if( get_field('press_contact') == 'EMBL-EBI Generic' ) { ?>
    <article class="vf-card vf-card--brand vf-card--bordered | vf-u-margin__top--800">
    <div class="vf-card__content | vf-stack vf-stack--400">
    <h3 class="vf-card__heading" style="font-size: 21px;">EMBL-EBI Press Office</h3>
      <p class="vf-card__text">Wellcome Genome Campus</br> Hinxton,
        Cambridgeshire</br> CB10 1SD, UK</p>
      <p class="vf-card__text"><a class="vf-card__link" href="mailto:contactpress@ebi.ac.uk">contactpress@ebi.ac.uk</a></br>+44 1223 494369</p>
    </div>
    </article>
    <?php }

else {} ?>

  </div>
  <div class="vf-content | vf-u-padding__bottom--800">

  <?php
  
  // custom switcher
    if( $languages ):
      $all_fields_count = count(get_field('languages'));
      $fields_count = 1;
?>
    <div class="vf-banner vf-banner--alert vf-banner--info">
      <div class="vf-banner__content">
        <p class="vf-banner__text">This article is also available in
        <?php foreach( $languages as $l ):
         $related = get_field('select_translations', $l->ID);
         if ($related == 'german') {
           $title = 'Deutsch';
         }
         if ($related == 'french') {
           $title = 'Français';
         }
         if ($related == 'english') {
           $title = 'English';
         }
         if ($related == 'italian') {
           $title = 'Italiano';
         }
         if ($related == 'spanish') {
           $title = 'Español';
         }
         if ($related == 'catalan') {
           $title = 'Catalan';
         }
        $permalink = get_permalink( $l->ID );
        // $title = get_the_title( $l->ID );
        ?>
          <a class="vf-banner__link" href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a><?php
       if ($fields_count == $all_fields_count - 1) {
          echo " and"; }
         else if ($fields_count == $all_fields_count) {
          echo "."; }
        else {
          echo ","; }
        $fields_count++; ?>
        <?php endforeach; ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php
    // WPML
    if ( function_exists('icl_object_id') ) {
        languages_links_switcher(); }?>
    <h1><?php the_title(); ?></h1>
    <?php /*  languages_links_switcher(); */?>
    <?php  if( have_rows('translations') ):
        $all_fields_count = count(get_field('translations'));
        $fields_count = 1;
      ?>
    <div class="vf-banner vf-banner--alert vf-banner--info">
      <div class="vf-banner__content">
        <p class="vf-banner__text">This article is also available in
          <?php while( have_rows('translations') ): the_row();
        $anchor = get_sub_field('translation_anchor');
        $language = get_sub_field('translation_language', false, false);?>
          <a class="vf-banner__link" href="<?php echo esc_url( $anchor ); ?>"><?php echo ($language) ?></a><?php
       if ($fields_count == $all_fields_count - 1) {
          echo " and"; }
         else if ($fields_count == $all_fields_count) {
          echo "."; }
        else {
          echo ","; }
        $fields_count++; ?>
          <?php endwhile; ?></p>
      </div>
    </div>
    <?php endif;  ?>

    <p class="vf-lede | vf-u-padding__top--400 | vf-u-padding__bottom--800">
      <?php echo get_post_meta($post->ID, 'article_intro', true); ?>
    </p>
    <?php

if( get_field( 'youtube_url' ) ) {
    $videoid = get_field( 'youtube_url' );
    $caption = get_field('video_caption');
    echo '<div class="vf-u-margin__bottom--100 embed-container embed-padding-main"><iframe src="' . $videoid . '" frameborder="0" allowfullscreen class="vf-card__image"></iframe></div><figcaption class="vf-figure__caption">' . $caption . '</figcaption>';
}

else if ( get_field( 'mp4_url' ) ) {
  $mp4url = get_field( 'mp4_url' );
  $caption = get_field('video_caption');
  echo '<div><video muted="muted" class="vf-card__image" controls><source src="' . $mp4url . '" type="video/mp4"></video></div><figcaption class="vf-figure__caption">' . $caption . '</figcaption>';
}

else {
      $show = get_post_meta( get_the_ID(), 'show_featured_image', true );
       if ( $show == '1' ): //not displaying
      else:?>
    <figure class="vf-figure">
      <?php
      if ( has_post_thumbnail() ) {
      the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>
    <?php }
      endif;
}
?>

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
      <hr class="vf-divider">
    <?php endif; 

    if( have_rows('related_links') ): ?>
    <div class="vf-links">
      <h3 class="vf-links__heading">Related links</h3>
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

    <p class="vf-text-body vf-text-body--3 | tags-inline">Tags:
      <?php
    $tags = get_the_tags($post->ID);
if ($tags) {
   $tagslist = array();
   foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-link vf-link--secondary' . $tag->term_id . '">' . $tag->name . '</a>';
   }
   echo implode(', ', $tagslist);
} ?></p>
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
</main>

<?php include(locate_template('partials/embletc-container.php', false, false)); ?>

<?php include(locate_template('partials/newsletter-container.php', false, false)); ?>


<?php get_footer(); ?>
