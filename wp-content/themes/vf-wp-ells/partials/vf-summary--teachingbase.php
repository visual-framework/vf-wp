<?php
$title = esc_html(get_the_title());
$topic_area = get_field('tb_topic_area');
$age_group = get_field('tb_age_group');
$post_id = get_the_ID();

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

$term_slugs = function($value, $taxonomy = '') {
  if ( empty($value) ) {
    return array();
  }

  if ( ! is_array($value) ) {
    $value = array($value);
  }

  $slugs = array();
  foreach ( $value as $item ) {
    if ( is_numeric($item) ) {
      $item = get_term((int) $item, $taxonomy);
    }

    if ( is_wp_error($item) || empty($item) ) {
      continue;
    }

    if ( is_object($item) && ! empty($item->slug) ) {
      $slugs[] = $item->slug;
      continue;
    }

    if ( is_array($item) && ! empty($item['slug']) ) {
      $slugs[] = $item['slug'];
      continue;
    }

    if ( is_string($item) ) {
      $slugs[] = $item;
    }
  }

  return array_filter(array_unique($slugs));
};

$topic_area_names = $term_names($topic_area, 'topic-area');
$age_group_names = $term_names($age_group, 'age-group');
$age_group_slugs = $term_slugs($age_group, 'age-group');
$all_age_group_slugs = wp_list_pluck(vf_wp_ells_term_options('age-group'), 'value');

if ( ! empty($all_age_group_slugs) && empty(array_diff($all_age_group_slugs, $age_group_slugs)) ) {
  $age_group_names = array('All');
}
?>

<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  <?php the_post_thumbnail( 'full', array( 
      'class' => 'vf-summary__image', 
      'loading'  => 'lazy',
      'itemprop' => 'image' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link">
      <?php echo $title; ?>
    </a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?>
  </p>
  <div>
    <?php if ( ! empty($topic_area_names) || ! empty($age_group_names) ) { ?>
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--200">
      <?php foreach ( $topic_area_names as $topic_area_name ) { ?>
        <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeBlue"><?php echo esc_html($topic_area_name); ?></span>
      <?php } ?>
      <?php foreach ( $age_group_names as $age_group_name ) { ?>
        <span class="vf-badge vf-badge--primary vf-u-margin__right--200 customBadgeGreen"><?php echo esc_html($age_group_name); ?></span>
      <?php } ?>
    </p>
    <?php }?>
    <?php wpml_post_languages_in_loop(); ?>
  </div>
</article>
