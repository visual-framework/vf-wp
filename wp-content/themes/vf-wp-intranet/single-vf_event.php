<?php

$title = esc_html(get_the_title());
$start_date = get_field('vf_event_start_date', $post->post_parent);
$start = DateTime::createFromFormat('j M Y', $start_date);

$end_date = get_field('vf_event_end_date', $post->post_parent);
$end = DateTime::createFromFormat('j M Y', $end_date);

get_header();

?>

<section class="embl-grid embl-grid--has-centered-content | vf-u-background-color-ui--white | vf-u-padding__top--800 | vf-u-margin__bottom--0">
  <div>
  </div>

  <div class="vf-content | vf-u-padding__bottom--800">
    <p class="vf-summary__date | vf-u-margin__bottom--0"> 
    <?php    
    if ( ! empty($start_date)) {
        if ($end_date) { 
          if ($start->format('M') == $end->format('M')) {
            echo $start->format('jS'); ?> - <?php echo $end->format('jS F Y'); }
          else {
            echo $start->format('jS F'); ?> - <?php echo $end->format('jS F Y'); }
            ?> 
    <?php } 
        else {
          echo $start->format('jS F Y'); 
        } }
      ?>
    </p>
    <h1><?php the_title(); ?></h1>
    <p class="vf-lede | vf-u-padding__top--400 | vf-u-padding__bottom--800">
      <?php echo get_post_meta($post->ID, 'article_intro', true); ?>
    </p>

    <?php the_content(); ?>

  </div>
</section>

<?php get_footer(); ?>
