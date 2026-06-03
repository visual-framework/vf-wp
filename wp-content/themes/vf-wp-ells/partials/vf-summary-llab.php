<?php
$start_date = get_field('labs_start_date');
$end_date = get_field('labs_end_date');
$start = DateTime::createFromFormat('j M Y', $start_date);
$end = DateTime::createFromFormat('j M Y', $end_date);
$type = get_field('labs_type');
$format = get_field('labs_format');
$location = get_field('labs_location');

$term_names = function($value, $taxonomy = '') {
  if ( empty($value) ) {
    return array();
  }

  if ( ! is_array($value) ) {
    $value = array($value);
  }

  $names = array();
  foreach ( $value as $item ) {
    if ( is_numeric($item) ) {
      $item = get_term((int) $item, $taxonomy);
    }

    if ( is_wp_error($item) || empty($item) ) {
      continue;
    }

    if ( is_object($item) && ! empty($item->name) ) {
      $names[] = $item->name;
      continue;
    }

    if ( is_array($item) && ! empty($item['name']) ) {
      $names[] = $item['name'];
      continue;
    }

    if ( is_string($item) ) {
      $names[] = $item;
    }
  }

  return array_filter(array_unique($names));
};

$type_names = $term_names($type, 'llabs-type');
$format_names = $term_names($format, 'llabs-format');
$location_names = $term_names($location, 'llabs-location');

?>
<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>">

    <?php 
            if ( ! empty($start_date)) {
              if ($end_date) { 
                if ($start->format('M') == $end->format('M')) {
                  echo $start->format('j'); ?> - <?php echo $end->format('j M Y'); }
                else {
                  echo $start->format('j M'); ?> - <?php echo $end->format('j M Y'); }
                    ?>
    <?php } 
              else {
                echo $start->format('j M Y'); 
              } }
              ?>
  </time>
  <?php the_post_thumbnail( 'full', array( 
      'class' => 'vf-summary__image', 
      'style' => 'width: 180px; height: auto; border: 1px solid #d0d0ce',
      'loading'  => 'lazy',
      'itemprop' => 'image' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo get_the_title(); ?>
    </a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?>
  </p>
  <?php if ( ! empty($type_names) || ! empty($format_names) || ! empty($location_names) ) { ?>
  <p class="vf-u-margin__top--0 vf-u-margin__bottom--800">
    <?php foreach ( $type_names as $type_name ) { ?>
      <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue"><?php echo esc_html($type_name); ?></span>
    <?php } ?>
    <?php foreach ( $format_names as $format_name ) { ?>
      <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGreen"><?php echo esc_html($format_name); ?></span>
    <?php } ?>

  </p>
  <?php } ?>

</article>
