<section class="embl-grid | vf-u-background-color--grey--lightest vf-u-fullbleed vf-u-background-color-ui--off-white vf-u-padding__bottom--xl vf-u-padding__top--xl">
  <div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link" href="/internal-information/community-blog">Community blog<svg aria-hidden="true" class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
    <path d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z" fill="" fill-rule="nonzero"></path>
    </svg></a>
    <p class="vf-section-header__text">Internal announcements</p>
  </div>      


<div>
  <div class="vf-tabs">
    <ul class="vf-tabs__list" data-vf-js-tabs>
        <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--6">EMBL Barcelona</a>
        </li>
        <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--7">EMBL Grenoble</a>
        </li>
        <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--8">EMBL Hamburg</a>
        </li>
        <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--9">EMBL Heidelberg</a>
        </li>
        <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--10">EMBL Rome</a>
        </li>
    </ul>
</div>

<div class="vf-tabs-content" data-vf-js-tabs-content>
    <section class="vf-tabs__section" id="vf-tabs__section--6">
        <div class="vf-grid | vf-grid__col-3">
    <div class="vf-grid__col--span-2 | vf-u-margin__top--xl">
    <?php
    $mainloop = new WP_Query (array('post_type' => 'community-blog', 'posts_per_page' => 1 ));
    $ids = array();
    while ($mainloop->have_posts()) : $mainloop->the_post();
    $ids[] = get_the_ID(); ?>

      <article class="vf-summary vf-summary--article">
        <h2 class="vf-summary__title">
          <a href="<?php the_permalink(); ?>" class="vf-summary__link"><?php echo esc_html(get_the_title()); ?></a>
        </h2>
        <span class="vf-summary__meta">
          <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
          <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
        </span>
        <p class="vf-summary__text">
        <?php echo get_the_excerpt(); ?></p>
      </article>
      <!--/vf-summary-->


    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
  </div>

<div class="vf-u-margin__top--xl">
  <h3 class="vf-links__heading">More posts</h3>
  <?php
  $sidebarloop = new WP_Query(array('post_type' => 'community-blog', 'post__not_in' => $ids, 'posts_per_page' => 2 ));
  while ($sidebarloop->have_posts()) : $sidebarloop->the_post();
				$ids[] = get_the_ID(); ?>
  <article class="vf-summary vf-summary--article | vf-u-margin__bottom--md">
    <h2 class="vf-summary__title">
      <a href="<?php the_permalink(); ?>" class="vf-summary__link" style="font-size: 19px;"><?php echo esc_html(get_the_title()); ?></a>
    </h2>
    <span class="vf-summary__meta">
      <a class="vf-summary__author vf-summary__link" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
      <time class="vf-summary__date" title="<?php the_time('c'); ?>" datetime="<?php the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time>
    </span>
  </article>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
</div> 
</div>   
</section>
    <section class="vf-tabs__section" id="vf-tabs__section--7">
    </section>
    <section class="vf-tabs__section" id="vf-tabs__section--8">
    </section>
    <section class="vf-tabs__section" id="vf-tabs__section--9">
    </section>
    <section class="vf-tabs__section" id="vf-tabs__section--10">
    </section>

</div>
</div>






</section>

