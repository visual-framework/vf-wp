<div class="vf-summary-container">
  <div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link"
      href="/internal-information/updates">Important updates<svg aria-hidden="true"
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a>
    <p class="vf-section-header__text">Internal announcements and important updates from the EMBL community</p>
  </div>

  <div class="vf-section-content | vf-u-margin__top--200">
    <div class="vf-tabs">
      <ul class="vf-tabs__list | vf-u-margin__top--0" data-vf-js-tabs>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--6"
            data-vf-js-location-nearest-activation-target="barcelona">Barcelona</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--7"
            data-vf-js-location-nearest-activation-target="grenoble">Grenoble</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--8"
            data-vf-js-location-nearest-activation-target="hamburg">Hamburg</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--9"
            data-vf-js-location-nearest-activation-target="default">Heidelberg</a>
        </li>
        <li class="vf-tabs__item">
          <a class="vf-tabs__link" href="#vf-tabs__section--10"
            data-vf-js-location-nearest-activation-target="rome">Rome</a>
        </li>
      </ul>
    </div>

    <div class="vf-tabs-content" data-vf-js-tabs-content>
      <section class="vf-tabs__section" id="vf-tabs__section--6">
        <div class="vf-grid | vf-grid__col-4">
          <?php
    $communityBlogLoop_1 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 4,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('barcelona', 'all')
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_1->have_posts()) : $communityBlogLoop_1->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--7">
        <div class="vf-grid | vf-grid__col-4">
          <?php
    $communityBlogLoop_2 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 4,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('grenoble', 'all')
        ) ) )); 
    $ids = array();
    while ($communityBlogLoop_2->have_posts()) : $communityBlogLoop_2->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--8">
        <div class="vf-grid | vf-grid__col-4">
          <?php
    $communityBlogLoop_3 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 4,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('hamburg', 'all')
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_3->have_posts()) : $communityBlogLoop_3->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--9">
        <div class="vf-grid | vf-grid__col-4">
          <?php
    $communityBlogLoop_4 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 4,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('heidelberg', 'all')
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_4->have_posts()) : $communityBlogLoop_4->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
      <section class="vf-tabs__section" id="vf-tabs__section--10">
        <div class="vf-grid | vf-grid__col-4">
          <?php
    $communityBlogLoop_5 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 4,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('rome', 'all')
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_5->have_posts()) : $communityBlogLoop_5->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
          <!--/vf-summary-->
          <?php endwhile;?>
          <?php wp_reset_postdata(); ?>
        </div>
      </section>
    </div>
  </div>
</div>
