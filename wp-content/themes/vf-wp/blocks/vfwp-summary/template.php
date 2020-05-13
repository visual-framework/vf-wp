<?php

$image = get_field('image');
$title = get_field('title');
$text = get_field('text', false, false);
$page_cont = wpautop($text);
$vf_class = str_replace('<p>', '<p class="vf-summary__text">', $page_cont);

?>

<style>
  .vf-summary__image {
    width: 180px !important;
  }
</style>

<?php 
if ( get_field('select_type') == 'Custom' ) { ?>
<article class="vf-summary vf-summary--has-image">
  <img class="vf-summary__image vf-summary__image--thumbnail" src="<?php echo get_field('image'); ?>">
  <h3 class="vf-summary__title">
    <?php echo esc_html($title); ?>
    </a>
  </h3>
  <?php echo ($vf_class)?>
</article>
<?php    
}

else if ( get_field('select_type') == 'Post' ) {  
    $object_post = get_field('post');
    if ($object_post) {
        // Setup first post data so template tags work
        global $post;
        $old_post = $post;
        setup_postdata($post = $object_post);
      ?>
<article class="vf-summary vf-summary--news">
  <span class="vf-summary__date"><time title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
  <?php the_post_thumbnail('thumbnail', array('class' => 'vf-summary__image')); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink() ?>" class="vf-summary__link">
      <?php the_title() ?>
    </a>
  </h3>
  <p class="vf-summary__text"><?php echo get_the_excerpt() ?></p>
</article>
<?php wp_reset_postdata(); ?>
<?php 
} }

else if ( get_field('select_type') == 'Event' ) { 
    $object_event = get_field('event');
    if ($object_event) {
        // Setup first post data so template tags work
        global $post;
        $old_post = $post;
        setup_postdata($post = $object_event);
        $location = get_field('vf_event_location',$post->ID);
        $event_type = get_field('vf_event_event_type',$post->ID); 
      ?>
<a href="<?php the_permalink(); ?>"
  class="vf-summary vf-summary--event | vf-summary--is-link vf-summary--easy vf-summary-theme--primary">
  <p class="vf-summary__date"><time title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
  <h3 class="vf-summary__title"><?php the_title() ?>
  </h3>
  <p class="vf-summary__text"><?php echo $event_type ?></p>
  <p class="vf-summary__location"><?php echo $location ?></p>

  <svg class="vf-summary__icon | vf-icon vf-icon-arrow--right" width="24" height="24"
    xmlns="http://www.w3.org/2000/svg">
    <path
      d="M23.6 11.289l-9.793-9.7a2.607 2.607 0 00-3.679.075 2.638 2.638 0 00-.068 3.689l3.871 3.714a.25.25 0 01-.173.43H2.135A2.28 2.28 0 00.1 12c0 .815.448 2.51 2 2.51h11.679a.25.25 0 01.177.427l-3.731 3.733a2.66 2.66 0 003.758 3.754l9.625-9.72a1 1 0 00-.008-1.415z"
      fill="" fill-rule="nonzero" /></svg>
</a>
<?php wp_reset_postdata(); ?>
<?php 
} }
?>