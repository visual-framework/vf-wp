<?php
$mainloop = new WP_Query (array('posts_per_page' => $limit ));
    $ids = array();
    while ($mainloop->have_posts()) : $mainloop->the_post();
    $ids[] = get_the_ID(); ?>

      <article class="vf-summary <?php echo ($class); ?>">
      <?php if ($show_image == 1) { ?>
        <span class="vf-summary__meta vf-u-margin__bottom--xs">
          <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
          <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
        </span>
        <?php the_post_thumbnail('full', array('class' => 'vf-summary__image', 'style' => 'height: auto;')); ?>
        <h2 class="vf-summary__title">
          <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
        </h2>
      <?php } 
      else { ?>
        <h2 class="vf-summary__title">
          <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
        </h2>

        <span class="vf-summary__meta">
          <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
          <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
        </span>

        <?php } ?>
        <p class="vf-summary__text">
        <?php echo get_the_excerpt(); ?></p>
      </article>
      <!--/vf-summary-->
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>