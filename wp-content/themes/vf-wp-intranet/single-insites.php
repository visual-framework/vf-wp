<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$intro = get_field('article_intro');
$topic_terms = get_field('topic');
$show = get_field('show_featured_image');
$locations = get_field('embl_location');

get_header();
display_latest_editor_for_admin(get_the_ID());

?>

<section class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--200 | vf-u-margin__bottom--0">
  <div>
  <div class="vf-article-meta-information">
      <div class="vf-author | vf-article-meta-info__author">
        <p class="vf-author__name">
         <?php the_author(); ?>
        </p>
        <div class="vf-author--avatar__link | vf-link">
          <?php echo get_avatar( get_the_author_meta( 'ID' ), 48, '', '', array('class' => 'vf-author--avatar')); ?>
        </div>
      </div>
      <div class="vf-meta__details">
        <p class="vf-summary__meta vf-u-margin__bottom--600"><time title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
            <p class="vf-summary__meta vf-u-margin__bottom--200">EMBL site:
    <?php 
if (($locations)) { ?>
    <span class="vf-u-text-color--grey
  location vf-u-margin__top--0 
  <?php foreach($locations as $location) { echo 'location-' . $location->slug . ' '; } ?>">
      <?php $location_list = [];
    foreach( $locations as $location ) { 
      $location_list[] = $location->name; }
      echo implode(', ', $location_list); ?>
    </span>&nbsp;&nbsp;
    <?php }?>
  </p>
        <p class="vf-meta__topics | vf-u-margin__top--600">
        <?php if (($topic_terms)) { ?>
    <span class="topic">
      <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<a class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue ' . esc_attr( $term->slug ) . '"style="color: #373a36; text-decoration: none;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a>'; }
            echo implode('', $topics_list); }?>
    </span>
  <?php } ?>
        </p>


      </div>
    </div>
  </div>

  <div class="vf-content | vf-u-padding__bottom--800">
    <h2 class="vf-text vf-text-heading--2 | vf-u-margin__bottom--200"><?php the_title(); ?></h2>
    <h3 class="vf-text vf-text-heading--4" style="font-weight: 400;">
      <?php echo get_post_meta($post->ID, 'article_intro', true); ?>
    </h3>
    <?php if ( $show == '1' ) {} //not displaying
      else { ?>
    <figure class="vf-figure">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>
    <?php } ?>
    <?php the_content(); ?>
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
            include(locate_template('partials/vf-summary-insites-featured.php', false, false)); ?>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
  </div>
</div>

<?php 

get_footer(); 

?>
