<?php
$title = esc_html(get_the_title());
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$user_id = get_the_author_meta('ID');
$languages = get_field('languages');

?>

<article class="vf-summary vf-summary--news newsItem vf-u-margin__bottom--800 vf-u-padding__bottom--400 articleBottomBorder" data-jplist-item>
  <span class="vf-summary__date vf-u-text-color--grey" title="<?php the_time('c'); ?>"datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></span>

  <?php the_post_thumbnail( 'medium', array( 'class' => 'vf-summary__image', 'loading' => 'lazy', 'style' => 'border: 1px solid #d0d0ce;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?></p>
    <span class="vf-summary__category">
    <?php
     if( get_post_type() == 'embletc' ) {
      echo 'EMBLetc';
      } else {
        $allowed_categories = array(501, 488, 511, 514, 910, 485); // Array of allowed category IDs
  
        foreach (get_the_category() as $category) {
            if (in_array($category->term_id, $allowed_categories)) { // Check if category ID is in allowed list
                echo '<a class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue ' . esc_attr( $category->slug ) . '"style="color: #373a36; text-decoration: none;" href="' . esc_url(get_category_link( $category )) . '">' . strtoupper(esc_html( $category->name )) . '</a>';
            }
        }
      } ?>
      </span>
  <div class="vf-u-display-none">
    <p class="year a<?php the_time('Y'); ?>"><?php the_time('Y'); ?></p>
    <p class="category">
    <?php 
    foreach((get_the_category()) as $category){
        echo $category->slug;
        } 

    ?>
    </p>
  </div>
  <?php
  if ( function_exists('icl_object_id') ) {
  wpml_post_languages_in_loop(); } ?>
      <?php
    // custom switcher
    if( $languages ):
      $all_fields_count = count(get_field('languages'));
      $fields_count = 1; ?>
        <p class="vf-summary__text | language-switcher language-switcher-archive">Read in
        <?php foreach( $languages as $l ):
          include(locate_template('partials/language-switcher.php', false, false)); ?>
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
