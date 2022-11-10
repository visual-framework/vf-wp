<?php

/*
Template Name: Newcomers
Template Post Type: insites
*/


$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$intro = get_field('article_intro');
$topic_terms = get_field('topic');
$show = get_field('show_featured_image');
$locations = get_field('embl_location');

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
      <div class="vf-meta__details | vf-stack vf-stack--400">
        <p class="vf-meta__date"><time title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
        <?php if (($topic_terms)) { ?>
        <p class="vf-meta__topics | vf-u-margin__top--600"><span style="color: #000;">Topic:</span>
          <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<span style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</span>'; }
            echo implode(', ', $topics_list); } ?>
        </p>
        <?php }
        if (($locations)) { ?>
        <p class="vf-meta__topics"><span style="color: #000;">EMBL site:</span>
          <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
        </p>
        <?php } ?>
      </div>
    </div>
  </div>

  <div class="vf-content | vf-u-padding__bottom--800">
    <h1><?php the_title(); ?></h1>
    <p class="vf-lede | vf-u-padding__top--400 | vf-u-padding__bottom--800">
      <?php echo get_post_meta($post->ID, 'article_intro', true); ?>
    </p>
    <?php if ( $show == '1' ) {} //not displaying
      else { ?>
    <figure class="vf-figure">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>
    <?php } ?>


    <?php
    $newcomers_start = get_field('newcomers_start_date');
    $newcomers_end = get_field('newcomers_end_date');
    $request = wp_remote_get( 'https://xs-db.embl.de/v2/newcomers/' . $newcomers_start . '/' . $newcomers_end );
    
    if( is_wp_error( $request ) ) {
      return false; // Bail early
    }
    
    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );
    
    if( ! empty( $data ) ) {
      foreach ($data as $person) {
        $title = $person->displayName; ?>

      <p><?php  echo $title; ?></p>
      <?php }} ?>
  </div>
  <div>
    <?php if (is_active_sidebar('sidebar-blog')) { ?>
    <?php vf_sidebar('sidebar-blog'); ?>
    <?php } ?>
  </div>
</section>

<hr class="vf-divider">

<div
  class="vf-news-container vf-news-container--featured | vf-u-margin__bottom--100 | vf-u-padding__top--400 | vf-u-fullbleed">
  <h2 class="vf-section-header__heading vf-u-margin__bottom--400">Latest stories</h2>
  <div class="vf-news-container__content vf-grid vf-grid__col-4">
    <?php
          $args = array(
            'post_type' => 'insites',
            'posts_per_page' => 4,
            'post__not_in'   => array( get_the_ID() ),
            'no_found_rows'  => true,
          );
          $featured = new WP_Query ($args);
            while ($featured->have_posts()) : $featured->the_post(); 
            include(locate_template('partials/vf-summary-insites-latest.php', false, false)); ?>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
  </div>
</div>

<?php 

get_footer(); 

?>
