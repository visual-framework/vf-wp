<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');

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
          datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time> 
      </p>
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">   
    <?php the_content(); ?>
    <hr class="vf-divider">
    <div class="vf-content">
  <h3>Latest posts</h3>
    <?php
          $args = array(
            'post_type' => 'community-blog',
            'posts_per_page' => 5,
            'post__not_in'   => array( get_the_ID() ),
            'no_found_rows'  => true,
          );
          $featured = new WP_Query ($args);
            while ($featured->have_posts()) : $featured->the_post(); 
            include(locate_template('partials/vf-summary--article.php', false, false)); ?>
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
