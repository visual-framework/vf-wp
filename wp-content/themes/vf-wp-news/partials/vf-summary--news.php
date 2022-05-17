<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$languages = get_field('languages');


?>

<article class="vf-summary vf-summary--news" <style>
  <time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0; margin-top: 6px;" title="<?php the_time('c'); ?>"datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>

  <?php the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'loading' => 'lazy', 'style' => 'border: 1px solid #d0d0ce;' ) ); ?>
  <h3 class="vf-summary__title | vf-u-margin__bottom--0">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
  <?php   if ( function_exists('icl_object_id') ) {
  wpml_post_languages_in_loop(); } ?>
      <?php
    // custom switcher
    if( $languages ):
      $all_fields_count = count(get_field('languages'));
      $fields_count = 1; ?>
        <p class="vf-summary__text | language-switcher | language-switcher-summary">Read in
        <?php foreach( $languages as $l ):
         $related = get_field('select_translations', $l->ID);
         if ($related == 'german') {
           $title = 'Deutsch';
         }
         if ($related == 'french') {
           $title = 'Français';
         }
         if ($related == 'english') {
           $title = 'English';
         }
         if ($related == 'italian') {
           $title = 'Italiano';
         }
         if ($related == 'spanish') {
           $title = 'Español';
         }
         if ($related == 'catalan') {
           $title = 'Catalan';
         }
        $permalink = get_permalink( $l->ID );
        // $title = get_the_title( $l->ID );
        ?>
          <a class="vf-link" href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a><?php
       if ($fields_count == $all_fields_count - 1) {
          echo " or"; }
         else if ($fields_count == $all_fields_count) {
          echo "."; }
        else {
          echo ","; }
        $fields_count++; ?>
        <?php endforeach; ?></p>
    <?php endif; ?>
</article>
