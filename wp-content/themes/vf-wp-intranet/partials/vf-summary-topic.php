<?php
$locations_blog = get_field('cb_embl_location');
$locations_news = get_field('embl_location');
$customDateSorting = get_the_time('Ymd');

?>
<article class="vf-summary vf-summary--news" data-jplist-item>
  <span class="vf-summary__date" data-eventtime="<?php echo $customDateSorting; ?>"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
  title="<?php the_time('c'); ?>"
  datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
  <?php 
  if ( has_post_thumbnail() ) {
  the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); 
  }
  else { ?>
  <img class="vf-summary__image"
    src="https://www.embl.org/internal-information/wp-content/uploads/Announcementes-and-updates.jpg" alt="Placeholder"
    loading="lazy">
  <?php } ?>  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <?php if (get_post_type() === 'insites') { ?>
      <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
      <?php } ?>
    <p class="vf-text-body vf-text-body--5
 location vf-u-margin__top--0">
  <?php if (get_post_type() === 'insites') { 
    if (($locations_news)) { ?>
        <?php $location_list = [];
        foreach( $locations_news as $location ) { 
            //         switch ($location->name) {
            // case "Heidelberg":
            //   $location->name = 'HD';
            //   break;
            // case "Hamburg":
            //   $location->name = 'HH';
            //   break;
            // case "Rome":
            //   $location->name = 'RM';
            //   break;
            // case "Grenoble":
            //   $location->name = 'GR';
            //   break;
            // case "Barcelona":
            //   $location->name = 'BCN';
            //   break;
            // case "EMBL-EBI":
            //   $location->name = 'EBI';
            //    break;
            // }
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
    <?php } ?>      
  </p>
  <?php } ?>
  <?php if (get_post_type() === 'community-blog') { 
    if (($locations_blog)) { ?>
        <?php $location_list = [];
        foreach( $locations_blog as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
    <?php } ?>      
  </p>
  <?php } ?>
</article>