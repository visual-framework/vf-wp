<?php

$title = esc_html(get_the_title());
$user_id = get_the_author_meta('ID');
$topic_terms = get_field('cb_topic');
$locations = get_field('cb_embl_location');

get_header();

?>


<section class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--200 | vf-u-margin__bottom--0">
  <div>
    <div class="vf-article-meta-information">
      <div class="vf-meta__details | vf-stack vf-stack--400">
        <p class="vf-meta__date"><time title="<?php the_time('c'); ?>"
            datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
        <hr class="vf-divider">
        <?php if (($topic_terms)) { ?>
        <p class="vf-meta__topics | vf-u-margin__top--600">
          <?php 
        if( $topic_terms ) {
          $topics_list = array(); 
          foreach( $topic_terms as $term ) {
            $topics_list[] = '<span style="color: #707372;"><a class="vf-link" href="' . esc_url(get_term_link( $term )) . '">' . strtoupper(esc_html( $term->name )) . '</a></span>'; }
            echo implode(', ', $topics_list); } ?>
        </p>
        <?php }
   if (($locations)) { ?>
        <p class="vf-text-body vf-text-body--5
 location vf-u-margin__top--0">
          <?php $location_list = [];
        foreach( $locations as $location ) { 
          $location_list[] = $location->name; }
          echo implode(' | ', $location_list); ?>
        </p>
        <?php } ?>
      </div>
    </div>
  </div>

  <div class="vf-content | vf-u-padding__bottom--800">
    <h2 class="vf-text vf-text-heading--2"><?php the_title(); ?></h2>
    <?php the_content(); ?>
  </div>
  <div>
  </div>
</section>

<?php 

get_footer(); 

?>
