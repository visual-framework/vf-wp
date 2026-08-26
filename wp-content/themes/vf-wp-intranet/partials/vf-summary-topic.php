<?php
$locations_blog = get_field('cb_embl_location');
$locations_news = get_field('embl_location');
$locations_events = get_field('vf_event_internal_location');
$customDateSorting = get_the_time('Ymd');

?>
<article class="vf-summary vf-summary--news newsItem vf-u-padding__bottom--400 articleBottomBorder" data-jplist-item>
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
  <?php if (get_post_type() === 'insites' && $locations_news) { ?>
  <p class="vf-summary__meta vf-u-margin__bottom--600">EMBL site:
    <span class="vf-u-text-color--grey | vf-u-margin__right--600 location vf-u-margin__top--0 
    <?php foreach($locations_news as $location) { echo 'location-' . $location->slug . ' '; } ?>">
      <?php $location_list = [];
      foreach($locations_news as $location) { 
        $location_list[] = $location->name; 
      }
      echo implode(', ', $location_list); ?>
    </span>&nbsp;&nbsp;
  </p>
<?php } ?>

<?php 
if (get_post_type() === 'community-blog' && $locations_blog) { ?>
  <p class="vf-summary__meta vf-u-margin__bottom--600">EMBL site:
    <span class="vf-u-text-color--grey | vf-u-margin__right--600 location vf-u-margin__top--0 
    <?php foreach($locations_blog as $location) { echo 'location-' . $location->slug . ' '; } ?>">
      <?php $location_list = [];
      foreach($locations_blog as $location) { 
        $location_list[] = $location->name; 
      }
      echo implode(', ', $location_list); ?>
    </span>&nbsp;&nbsp;
  </p>
<?php } ?>

<?php 
if (get_post_type() === 'events' && $locations_events) { ?>
  <p class="vf-summary__meta vf-u-margin__bottom--600">Location:
    <span class="vf-u-text-color--grey | vf-u-margin__right--600 location vf-u-margin__top--0 
    <?php foreach($locations_events as $location) { echo 'location-' . $location->slug . ' '; } ?>">
      <?php $location_list = [];
      foreach($locations_events as $location) { 
        $location_list[] = $location->name; 
      }
      echo implode(', ', $location_list); ?>
    </span>&nbsp;&nbsp;
  </p>
<?php } ?>
</article>