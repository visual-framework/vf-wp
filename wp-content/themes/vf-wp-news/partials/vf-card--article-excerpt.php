<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$languages = get_field('languages');


?>
<article class="vf-card vf-card--brand vf-card--striped">
  <?php /*

if( get_field( 'youtube_url' ) ) {
    $videoid = get_field( 'youtube_url' );
    echo '<div class="embed-container embed-padding-hero vf-card__image"><iframe src="' . $videoid . '" frameborder="0" allowfullscreen></iframe></div>';
} 

else if ( get_field( 'mp4_url' ) ) { 
  $mp4url = get_field( 'mp4_url' );
  echo '<div><video muted="muted" class="vf-card__image" autoplay loop><source src="' . $mp4url . '" type="video/mp4"></video></div>';
}

else { ≈?>
  <?php
}
*/?>

  <?php the_post_thumbnail( 'large', array( 'class' => 'vf-card__image' ) ); ?>
  <div class="vf-card__content | vf-stack vf-stack--400" style="padding-top: 6px;">
    <h3 class="vf-card__heading">
      <a href="<?php the_permalink(); ?>" style="color: #fff;" class="vf-card__link"><?php echo $title; ?>
        <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
          height="1em" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
            fill="currentColor" fill-rule="nonzero"></path>
        </svg>
      </a>
    </h3>
    <p class="vf-card__text">
      <?php echo get_the_excerpt(); ?></p>
    <?php /*
      <span class="vf-card__text vf-summary__meta | vf-u-margin__bottom--100">
      <p class="vf-summary__meta vf-u-margin__bottom--100 vf-u-margin__top--100">By&nbsp;<a class="vf-card__link"
          href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a> in
    <?php echo get_the_category_list(','); ?></p>
    </span>
    */ ?>
    <p class="vf-summary__date vf-u-text-color--ui--grey"><?php the_time(get_option('date_format')); ?></p>
    <?php   if ( function_exists('icl_object_id') ) { ?>
      <?php wpml_post_languages_in_loop_card();?>
    <?php } ?>
    <?php
  
  // custom switcher
    if( $languages ):
      $all_fields_count = count(get_field('languages'));
      $fields_count = 1;
?>
        <p class="vf-card__text | language-switcher language-switcher-main-card">Read in
        <?php foreach( $languages as $l ):
         $related = get_field('field_article_language', $l->ID);
         include(locate_template('partials/language-switcher.php', false, false));
        ?>
          <a class="vf-card__link" href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a><?php
       if ($fields_count == $all_fields_count - 1) {
          echo " or"; }
         else if ($fields_count == $all_fields_count) {
          echo "."; }
        else {
          echo ","; }
        $fields_count++; ?>
        <?php endforeach; ?></p>
    <?php endif; ?>

  </div>
</article>
