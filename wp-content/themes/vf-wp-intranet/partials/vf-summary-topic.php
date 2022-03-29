<?php
$locations_blog = get_field('cb_embl_location');
$locations_news = get_field('embl_location');

?>
<article class="vf-summary vf-summary--news" data-jplist-item>
  <span class="vf-summary__date"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
      title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
      <?php if (get_post_type() === 'insites') { ?>
      <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image', 'style' => 'height: auto;' ) ); ?>
        <?php } ?>
      <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
    <p class="vf-summary__text"><?php echo get_the_excerpt(); ?></p>
  <p class="vf-summary__text | vf-u-margin__top--200">
    <span class="vf-summary__category | type">
  <?php if (get_post_type() === 'insites') { 
    if (($locations_news)) { ?>
      <span class="vf-u-text-color--grey | location" style="text-transform: none;">
        <?php $location_list = [];
        foreach( $locations_news as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
      </span>
    <?php } ?>      
    </span>
  </p>
  <?php } ?>
  <?php if (get_post_type() === 'community-blog') { 
    if (($locations_blog)) { ?>
      <span class="vf-u-text-color--grey | location" style="text-transform: none;">
        <?php $location_list = [];
        foreach( $locations_blog as $location ) { 
          $location_list[] = $location->name; }
          echo implode(', ', $location_list); ?>
      </span>
    <?php } ?>      
    </span>
  </p>
  <?php } ?>
</article>