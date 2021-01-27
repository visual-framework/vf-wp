<?php

$title = esc_html(get_the_title());
$tags = get_the_tags($post->ID);
$file = get_field('upload_file');

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
      <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
<p><?php echo get_the_excerpt(); ?></p>
<?php
if( $file ): ?>
    <a href="<?php echo $file['url']; ?>" class="vf-button vf-button--primary vf-button--sm">Download</a>
<?php endif; ?>
    </div>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <?php the_content(); ?>

  </div>
  <?php if (is_active_sidebar('sidebar-blog')) { ?>

    <?php vf_sidebar('sidebar-blog'); ?>

  <?php } ?>
</div>



<?php 

get_footer(); 

?>
