<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');
$tags = get_the_tags($post->ID);
$intro = get_field('article_intro');
$topic_terms = get_field('topic');

get_header();

?>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <div class="vf-content">
      <p class="vf-summary__date | vf-u-margin__bottom--0">
        <time title="<?php the_time('c'); ?>"
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
        <?php 
      if( $topic_terms ) { ?>
      in
      <?php
        $topics_list = array(); 
      foreach( $topic_terms as $term ) {
        $topics_list[] = '<a class="vf-link"  href="' . esc_url(get_term_link( $term )) . '" class="vf-link">' . esc_html( $term->name ) . '</a>'; }
      echo implode(', ', $topics_list); }?>
      </p>
      <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
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
    <?php the_content(); ?>
    
    <?php
    /*
     $tags = get_the_tags($post->ID);
     if ($tags) { ?>
    <p class="vf-text-body vf-text-body--3 | tags-inline">Tags:
      <?php $tagslist = array();
     foreach($tags as $tag) {
      $tagslist[] = '<a  href="' . get_tag_link($tag->term_id) . '" class="vf-link vf-link--secondary' . $tag->term_id . '">' . $tag->name . '</a>';
     }
     echo implode(', ', $tagslist); ?>
    </p>
    <?php } 
    */?>

  </div>
  <?php if (is_active_sidebar('sidebar-blog')) { ?>

    <?php vf_sidebar('sidebar-blog'); ?>

  <?php } ?>
</div>



<?php 

get_footer(); 

?>
