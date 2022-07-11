<?php

$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$languages = get_field('languages');

?>

<article class="vf-card vf-card--brand vf-card--bordered | vf-u-margin__bottom--500">

<?php the_post_thumbnail( 'large', array( 'class' => 'vf-card__image' ) ); ?>
  <div class="vf-card__content | vf-stack vf-stack--400">
    <h3 class="vf-card__heading">
      <a href="<?php the_permalink(); ?>" class="vf-card__link"><?php echo $title; ?>
        <svg aria-hidden="true" class="vf-card__heading__icon | vf-icon vf-icon-arrow--inline-end" width="1em"
          height="1em" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
            fill="currentColor" fill-rule="nonzero"></path>
        </svg>
      </a>
    </h3>
    <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;" title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
      <?php   if ( function_exists('icl_object_id') ) { ?>
      <?php wpml_post_languages_in_loop_card();?>
    <?php } ?>
    <?php
    // custom switcher
    if( $languages ):
      $all_fields_count = count(get_field('languages'));
      $fields_count = 1; ?>
        <p class="vf-card__text | language-switcher">Read in
        <?php foreach( $languages as $l ):
        include(locate_template('partials/language-switcher.php', false, false));        ?>
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
