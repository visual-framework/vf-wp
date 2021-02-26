<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$intro = get_field('article_intro');
$topic_terms = get_field('topic');
$show = get_field('show_featured_image');


get_header();

?>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <div class="vf-content">
    <?php
    $tags = get_the_tags($post->ID);
    if ($tags) {
    $tagslist = array();
    foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-badge | vf-u-margin__bottom--200">' . $tag->name . '</a>';
    }
    echo implode('  ', $tagslist);
    } 
    ?>
      <p class="vf-summary__date | vf-u-margin__bottom--0">
        <time title="<?php the_time('c'); ?>"
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time> in
        <?php 
      if( $topic_terms ) {
        $topics_list = array(); 
      foreach( $topic_terms as $term ) {
        $topics_list[] = '<a class="vf-link"  href="' . esc_url(get_term_link( $term )) . '" class="vf-link">' . esc_html( $term->name ) . '</a>'; }
      echo implode(', ', $topics_list); }?>
      </p>
      <h1><?php the_title(); ?></h1>
      <?php 
    if ($intro) { ?>
      <p class="vf-lede">
        <?php echo get_post_meta($post->ID, 'article_intro', true); ?>
      </p>
      <?php } ?>
    </div>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <?php
      if ( $show == '1' ) {

      } //not displaying
      else { ?>
   
    <figure class="vf-figure | vf-u-float__right--md | vf-u-padding__left--600">
      <?php the_post_thumbnail('full', array('class' => 'vf-figure__image', 'style' => 'width: 300px;')); ?>
      <figcaption class="vf-figure__caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
      </figcaption>
    </figure>
    <?php } ?>
    <?php the_content(); ?>
    <hr class="vf-divider">
    <div class="vf-content">
  <h3>Latest stories</h3>
    <?php
          $args = array(
            'post_type' => 'insites',
            'posts_per_page' => 3,
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
  <?php if (is_active_sidebar('sidebar-blog')) { ?>

    <?php vf_sidebar('sidebar-blog'); ?>

  <?php } ?>
</div>

<?php 

get_footer(); 

?>
