<?php
$start_date = get_field('il_start_date');
$topic_area = get_field('il_topic_area');

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

$topic_area_names = $term_names($topic_area, 'topic-area');
?>
<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>"><?php echo ($start_date); ?></time>
  <?php the_post_thumbnail( 'full', array( 
      'class' => 'vf-summary__image', 
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
  <?php if ( ! empty($topic_area_names) ) { ?>
  <p class="vf-u-margin__top--0 vf-u-margin__bottom--200">
    <?php foreach ( $topic_area_names as $topic_area_name ) { ?>
      <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue"><?php echo esc_html($topic_area_name); ?></span>
    <?php } ?>
  </p>
  <?php } ?>
</article>
