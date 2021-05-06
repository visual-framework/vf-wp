<?php
$title = esc_html(get_the_title());
$topic_area = get_field('tb_topic_area');
$age_group = get_field('tb_age_group');
$post_id = get_the_ID();
?>

<article class="vf-summary vf-summary--news">
  <time class="vf-summary__date" style="margin-left: 0;" title="<?php the_time('c'); ?>"
    datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
  <?php the_post_thumbnail( 'full', array( 
      'class' => 'vf-summary__image', 
      'style' => 'width: 180px; height: auto; border: 1px solid #d0d0ce',
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
    <?php if (($topic_area) || ($age_group)) { ?>
    <p class="vf-summary__meta | vf-u-margin__bottom--200">
      <?php if ($topic_area) { ?>
      <span>Topic area:</span>&nbsp;<span
        class="vf-u-text-color--grey"><?php echo ($topic_area->name); ?></span>&nbsp;&nbsp;
      <?php } ?>
      <?php if ($age_group) { ?>
      <span>Age group:</span>&nbsp;<span class="vf-u-text-color--grey"><?php echo ($age_group->name); ?></span>
      <?php } }
      if (($topic_area) || ($age_group)) { ?>
    </p>
    <?php }
    if ( function_exists('icl_object_id') ) { ?>
    <p class="vf-summary__meta">
      Other language(s): &nbsp&nbsp<?php wpml_post_languages_in_loop(); ?>
    </p>
    <?php } ?>
  </div>
</article>
