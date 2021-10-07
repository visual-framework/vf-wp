<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');
$topic_terms = get_field('cb_topic');
$locations = get_field('cb_embl_location');

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
        <span style="color: #000">&nbsp;|&nbsp;</span>
        <?php if (($topic_terms)) { ?>
        <span class="vf-summary__category">
          <span>
            <?php 
            if( $topic_terms ) {
              $topics_list = array(); 
              foreach( $topic_terms as $term ) {
                $topics_list[] = '<span style="color: #707372;" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</span>'; }
                echo implode(', ', $topics_list); }?>
          </span>
          <?php } 
        if (($topic_terms))
        { ?>
          <span style="color: #000">&nbsp;|&nbsp;</span>
          <?php
        }
        if (($locations)) { ?>
          <span class="vf-u-text-color--grey | location" style="text-transform: none;">
            <?php $location_list = [];
            foreach( $locations as $location ) { 
              $location_list[] = $location->name; }
              echo implode(', ', $location_list); ?>
          </span>
        </span>
        <?php } ?>
      </p>
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
  <div></div>
</div>

<div class="vf-grid vf-grid__col-3 | vf-u-grid-gap--800 | vf-content">
  <div class="vf-grid__col--span-2">
    <?php the_content(); ?>
  </div>
</div>

<?php 

get_footer(); 

?>
