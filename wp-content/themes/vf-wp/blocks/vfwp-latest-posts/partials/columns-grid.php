<?php
$mainloop = new WP_Query (array(
  'posts_per_page' => $limit,
  'post_type' => 'post',
  'cat' => $category,
  'tag__in' => $tag,
  's' => $keyword
 ));
    $ids = array();
    while ($mainloop->have_posts()) : $mainloop->the_post();
    $ids[] = get_the_ID(); ?>
<article class="vf-summary vf-summary--news" style="display: block; display: unset;">
  <?php the_post_thumbnail( 'full', array( 'class' => 'vf-summary__image vf-u-margin__bottom--400', 'style' => 'max-width: 100%; height: auto;' ) ); ?>
  <h3 class="vf-summary__title">
    <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
  </h3>
  <p class="vf-summary__text">
    <?php echo get_the_excerpt(); ?>
  </p>
  <span class="vf-summary__date"><time class="vf-summary__date vf-u-text-color--grey" style="margin-left: 0;"
      title="<?php the_time('c'); ?>"
      datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
  <?php if ($show_categories == 1) { ?>
  <span class="vf-summary__category">
    <?php echo get_the_category_list(', '); ?>
  </span>
  <?php } ?>
</article>

<!--/vf-summary-->
<?php endwhile;?>
<?php wp_reset_postdata(); ?>
