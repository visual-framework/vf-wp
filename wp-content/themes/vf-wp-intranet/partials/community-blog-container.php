<section class="vf-summary-container | vf-u-padding__top--200">
  <div class="vf-section-header"><a class="vf-section-header__heading vf-section-header__heading--is-link"
      href="/internal-information/updates">Updates and announcements<svg aria-hidden="true"
        class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a>
    <p class="vf-section-header__text">Internal announcements and updates from departments and teams. You can filter by
      site and topic.</p>
  </div>
  <div class="vf-grid | vf-grid__col-2 | vf-u-margin__bottom--1200 vf-u-margin__top--800" id="announcements-container">
    <div>
      <div class="vf-section-content">
        <div class="vf-tabs">
          <ul class="vf-tabs__list | vf-u-margin__top--0" data-vf-js-tabs>
            <li class="vf-tabs__item">
              <a class="vf-tabs__link" href="#vf-tabs__section--all"
                data-vf-js-location-nearest-activation-target="all">All EMBL sites</a>
            </li>
          </ul>
        </div>

        <div class="vf-tabs-content | vf-u-padding__top--400" data-vf-js-tabs-content>
          <section class="vf-tabs__section" id="vf-tabs__section--all">
            <div class="vf-grid | vf-grid__col-1">
              <?php
    $communityBlogLoop = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'meta_key'		=> 'cb_featured',
    'meta_value'	=> true,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'slug',
            'terms'    => array('all'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop->have_posts()) : $communityBlogLoop->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
              <!--/vf-summary-->
              <?php endwhile;?>
              <?php wp_reset_postdata(); ?>
            </div>
            <div class="vf-u-margin__top--600"><a href="https://www.embl.org/internal-information/updates/#group=data-group-1&location-all=1&location2-barcelona=0&location3-embl-ebi=0&location4-grenoble=0&location5-hamburg=0&location6-heidelberg=0&location7-rome=0" class="vf-link">See more</a></div>
          </section>
        </div>
      </div>

    </div>

    <div class="vf-section-content | vf-u-margin__top--200 | vf-u-margin__bottom--400">
      <div class="vf-tabs">
        <ul class="vf-tabs__list | vf-u-margin__top--0" data-vf-js-tabs>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--barcelona"
              data-vf-js-location-nearest-activation-target="barcelona">Barcelona</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--grenoble"
              data-vf-js-location-nearest-activation-target="grenoble">Grenoble</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--hamburg"
              data-vf-js-location-nearest-activation-target="hamburg">Hamburg</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--heidelberg"
              data-vf-js-location-nearest-activation-target="default">Heidelberg</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--hinxton"
              data-vf-js-location-nearest-activation-target="hinxton">Hinxton</a>
          </li>
          <li class="vf-tabs__item">
            <a class="vf-tabs__link" href="#vf-tabs__section--rome"
              data-vf-js-location-nearest-activation-target="rome">Rome</a>
          </li>
        </ul>
      </div>

      <div class="vf-tabs-content | vf-u-padding__top--400" data-vf-js-tabs-content>
        <section class="vf-tabs__section" id="vf-tabs__section--barcelona">
          <div class="vf-grid | vf-grid__col-1">
            <?php
    $communityBlogLoop_1 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'name',
            'terms'    => array('barcelona'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_1->have_posts()) : $communityBlogLoop_1->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
            <!--/vf-summary-->
            <?php endwhile;?>
            <?php wp_reset_postdata(); ?>
          </div>
          <div class="vf-u-margin__top--600"><a href="https://www.embl.org/internal-information/updates/#group=data-group-1&location-all=0&location2-barcelona=1&location3-embl-ebi=0&location4-grenoble=0&location5-hamburg=0&location6-heidelberg=0&location7-rome=0" class="vf-link">See more</a></div>

        </section>
        <section class="vf-tabs__section" id="vf-tabs__section--grenoble">
          <div class="vf-grid | vf-grid__col-1">
            <?php
    $communityBlogLoop_2 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'name',
            'terms'    => array('grenoble'),
        ) ) )); 
    $ids = array();
    while ($communityBlogLoop_2->have_posts()) : $communityBlogLoop_2->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
            <!--/vf-summary-->
            <?php endwhile;?>
            <?php wp_reset_postdata(); ?>
          </div>
          <div class="vf-u-margin__top--600"><a href="https://www.embl.org/internal-information/updates/#group=data-group-1&location-all=0&location2-barcelona=0&location3-embl-ebi=0&location4-grenoble=1&location5-hamburg=0&location6-heidelberg=0&location7-rome=0" class="vf-link">See more</a></div>

        </section>
        <section class="vf-tabs__section" id="vf-tabs__section--hamburg">
          <div class="vf-grid | vf-grid__col-1">
            <?php
    $communityBlogLoop_3 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'name',
            'terms'    => array('hamburg'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_3->have_posts()) : $communityBlogLoop_3->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
            <!--/vf-summary-->
            <?php endwhile;?>
            <?php wp_reset_postdata(); ?>
          </div>
          <div class="vf-u-margin__top--600"><a href="https://www.embl.org/internal-information/updates/#group=data-group-1&location-all=0&location2-barcelona=0&location3-embl-ebi=0&location4-grenoble=0&location5-hamburg=1&location6-heidelberg=0&location7-rome=0" class="vf-link">See more</a></div>

        </section>
        <section class="vf-tabs__section" id="vf-tabs__section--heidelberg">
          <div class="vf-grid | vf-grid__col-1">
            <?php
    $communityBlogLoop_4 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'name',
            'terms'    => array('heidelberg'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_4->have_posts()) : $communityBlogLoop_4->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
            <!--/vf-summary-->
            <?php endwhile;?>
            <?php wp_reset_postdata(); ?>
          </div>
          <div class="vf-u-margin__top--600"><a href="https://www.embl.org/internal-information/updates/#group=data-group-1&location-all=0&location2-barcelona=0&location3-embl-ebi=0&location4-grenoble=0&location5-hamburg=0&location6-heidelberg=1&location7-rome=0" class="vf-link">See more</a></div>

        </section>
        <section class="vf-tabs__section" id="vf-tabs__section--hinxton">
          <div class="vf-grid | vf-grid__col-1">
            <?php
    $communityBlogLoop_6 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'name',
            'terms'    => array('embl-ebi'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_6->have_posts()) : $communityBlogLoop_6->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
            <!--/vf-summary-->
            <?php endwhile;?>
            <?php wp_reset_postdata(); ?>
          </div>
          <div class="vf-u-margin__top--600"><a href="https://www.embl.org/internal-information/updates/#group=data-group-1&location-all=0&location2-barcelona=0&location3-embl-ebi=1&location4-grenoble=0&location5-hamburg=0&location6-heidelberg=0&location7-rome=0" class="vf-link">See more</a></div>

        </section>
        <section class="vf-tabs__section" id="vf-tabs__section--rome">
          <div class="vf-grid | vf-grid__col-1">
            <?php
    $communityBlogLoop_5 = new WP_Query (array(
    'post_type' => 'community-blog', 
    'posts_per_page' => 3,
    'tax_query' => array(
        array(
            'taxonomy' => 'embl-location',
            'field' => 'name',
            'terms'    => array('rome'),
        ) ) ));
    $ids = array();
    while ($communityBlogLoop_5->have_posts()) : $communityBlogLoop_5->the_post();
    $ids[] = get_the_ID(); 
    include(locate_template('partials/vf-summary-community-blog.php', false, false)); ?>
            <!--/vf-summary-->
            <?php endwhile;?>
            <?php wp_reset_postdata(); ?>
          </div>
          <div class="vf-u-margin__top--600"><a href="https://www.embl.org/internal-information/updates/#group=data-group-1&location-all=0&location2-barcelona=0&location3-embl-ebi=0&location4-grenoble=0&location5-hamburg=0&location6-heidelberg=0&location7-rome=1" class="vf-link">See more</a></div>

        </section>
      </div>
    </div>
    <span id="border"></span>
  </div>
</section>
<style>
  @media only screen and (max-width: 767px) {
    #border {
      display: none;
    }
  }

  #announcements-container {
    position: relative;
  }

  #border {
    position: absolute;
    left: 50%;
    width: 1px;
    height: 100%;
    background: #d0d0ce;
    right: -9px
  }

  .vf-summary--has-image .vf-summary__image--thumbnail {
    width: 65px;
    /* border-radius: 50%; */
  }

  .vf-tabs__link {
    font-size: 18px;
  }

  .vf-summary__image--thumbnail {
    border: solid 1px #d0d0ce;
    height: 45px;
    object-fit: cover;
  }

</style>
