<?php

get_header();

global $vf_theme;

$title = $vf_theme->get_title();

?>
<section
  class="vf-u-background-color-ui--off-white | vf-u-margin__bottom--100 | vf-u-padding__top--600 | vf-u-padding__bottom--400 | vf-u-fullbleed">
  <h3 class="vf-section-header__heading | vf-u-margin__bottom--400">Featured stories</h3>
  <div class="vf-grid vf-grid__col-3">
    <?php
			$featured = new WP_Query (array('posts_per_page' => 3, 'post_type' => 'insites', 'meta_key' => 'featured',
            'meta_value' => '1'  ));
$ids = array();
while ($featured->have_posts()) : $featured->the_post();
$ids[] = get_the_ID(); ?>
    <?php include(locate_template('partials/vf-card--featured-article.php', false, false)); ?>
    <?php endwhile;?>
    <?php wp_reset_postdata(); ?>
  </div>
</section>

<h3 class="vf-section-header__heading | vf-u-margin__bottom--400">Latest posts</h3>
<section class="vf-grid vf-grid__col-4">
  <div class="vf-grid__col--span-3">
    <h1 class="vf-text vf-text-heading--1">
    </h1>
    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--article.php', false, false)); 
            if ( ! $vf_theme->is_last_post()) {
                echo '<hr class="vf-divider">';
              }

          }
        } else {
          echo '<p>', __('No posts found', 'vfwp'), '</p>';
        } ?>
    <div class="vf-grid"> <?php vf_pagination();?></div>

  </div>

  <div>
    <?php include(locate_template('partials/insites-filter.php', false, false)); ?>

  </div>
</section>
<?php

get_footer();

?>
