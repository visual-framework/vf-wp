<?php get_header(); 

if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}
if (class_exists('VF_Intranet_Breadcrumbs')) {
  VF_Plugin::render(VF_Intranet_Breadcrumbs::get_plugin('vf_wp_breadcrumbs_intranet'));
}

// Pages search query
$page_args = array(
  'post_type' => array('page', 'teams'),
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$page_query = new WP_Query( $page_args );

// People search query
$people_args = array(
  'post_type' => 'people',
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$people_query = new WP_Query( $people_args );

// documents search query
$documents_args = array(
  'post_type' => 'documents',
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$documents_query = new WP_Query( $documents_args );

// People search query
$insites_args = array(
  'post_type' => 'insites',
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$insites_query = new WP_Query( $insites_args );

// People search query
$events_args = array(
  'post_type' => 'events',
  'posts_per_page' => -1,
   's' => get_search_query(), 
   'relevanssi' => true,
);
$events_query = new WP_Query( $events_args );

?>

<section class="vf-hero | vf-u-fullbleed | vf-hero--800 | vf-u-margin__bottom--0">
  <style>
    .vf-hero {
      --vf-hero--bg-image: url('https://www.embl.org/internal-information/wp-content/uploads/20220325_Intranet-hero-scaled.jpg');
            }
  </style>  <div class="vf-hero__content | vf-box | vf-stack vf-stack--200">
    <h2 class="vf-hero__heading">
      <a class="vf-hero__heading_link" href="https://www.embl.org/internal-information">
        EMBL Intranet </a>
    </h2>
  </div>
</section>

<?php
if (class_exists('VF_Navigation')) {
  VF_Plugin::render(VF_Navigation::get_plugin('vf_navigation'));
}
?>

<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      Search
    </h1>
  </div>
</section>

<section
  class="embl-grid embl-grid--has-centered-content vf-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--0">
  <div></div>
  <div>
    <form role="search" method="get"
      class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end"
      action="<?php echo esc_url(home_url('/')); ?>">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item | vf-search__item">
          <input autofocus data-embl-search-input data-vf-search-client-side-input type="search"
            class="vf-form__input | vf-search__input" placeholder="Enter your search term"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s">
        </div>
        <div class="vf-form__item | vf-search__item" style="display: none">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
          <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
            <option value="any" selected="">Everything</option>
            <option value="page" name="post_type[]">Pages</option>
            <option value="insites" name="post_type[]">INsites</option>
            <option value="events" name="post_type[]">Events</option>
            <option value="people" name="post_type[]">People</option>
            <option value="documents" name="post_type[]">Documents</option>
          </select>
        </div>
        <button type="submit" class="vf-search__button | vf-button vf-button--primary" data-embl-search-submit>
          <span class="vf-button__text">Search</span>
          <svg class="vf-icon vf-icon--search-btn | vf-button__icon" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
            xmlns:svgjs="http://svgjs.com/svgjs" viewBox="0 0 140 140" width="140" height="140">
            <g transform="matrix(5.833333333333333,0,0,5.833333333333333,0,0)">
              <path
                d="M23.414,20.591l-4.645-4.645a10.256,10.256,0,1,0-2.828,2.829l4.645,4.644a2.025,2.025,0,0,0,2.828,0A2,2,0,0,0,23.414,20.591ZM10.25,3.005A7.25,7.25,0,1,1,3,10.255,7.258,7.258,0,0,1,10.25,3.005Z"
                fill="#FFFFFF" stroke="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="0"></path>
            </g>
          </svg>
        </button>
        <?php
          if ( function_exists( 'relevanssi_didyoumean' ) ) {
            relevanssi_didyoumean(
              get_search_query(false),
                '<p>Did you mean: ',
                '</p>',
                5
            );
        }
        ?>
      </div>
    </form>
    <!-- <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--0"><a
        href="https://www.embl.org/search/?searchQuery=&activeFacet=Alumni#stq=<?php echo esc_attr(get_search_query()); ?>"
        target="_blank">Search for Alumni</a></p> -->
  </div>
</section>

<section class="embl-grid | vf-u-margin__bottom--0">
  <div>
  </div>
  <div class="vf-content">
  <div class="vf-tabs">
    <ul class="vf-tabs__list" data-vf-js-tabs>
      <li class="vf-tabs__item">
        <a class="vf-tabs__link" href="#vf-tabs__section--pages">Pages (<?php echo $page_query->post_count; ?>)
        </a>
      </li>
      <li class="vf-tabs__item">
        <a class="vf-tabs__link" href="#vf-tabs__section--people">People (<?php echo $people_query->post_count; ?>)</a>
      </li>
      <li class="vf-tabs__item">
        <a class="vf-tabs__link" href="#vf-tabs__section--documents">Documents
          (<?php echo $documents_query->post_count; ?>)</a>
      </li>
      <li class="vf-tabs__item">
        <a class="vf-tabs__link" href="#vf-tabs__section--news">News (<?php echo $insites_query->post_count; ?>)</a>
      </li>
      <li class="vf-tabs__item">
        <a class="vf-tabs__link" href="#vf-tabs__section--events">Events (<?php echo $events_query->post_count; ?>)</a>
      </li>
      <li class="vf-tabs__item">
        <a class="vf-tabs__link" href="#vf-tabs__section--public">Public search <span class="st-info-container-count"></span></a>
      </li>
    </ul>
  </div>
  </div>
</section>
<section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--600">
  <div>
  </div>

  <div class="vf-tabs-content" data-vf-js-tabs-content>

    <!-- Pages -->
    <section class="vf-tabs__section" id="vf-tabs__section--pages">
      <?php if ( $page_query->have_posts() ): ?>
      <?php while ( $page_query->have_posts() ) : $page_query->the_post(); ?>
      <?php include(locate_template('partials/vf-summary--page.php', false, false)); ?>
      <?php endwhile; ?>
      <?php else : ?>
      <?php _e( 'No pages found. Please check the other content types.' ); ?>
      <?php endif; ?>
    </section>
    <?php rewind_posts(); ?>

    <!-- People -->
    <section class="vf-tabs__section" id="vf-tabs__section--people">
      <?php if ( $people_query->have_posts() ): ?>
      <?php while ( $people_query->have_posts() ) : $people_query->the_post(); ?>
      <?php include(locate_template('partials/vf-profile.php', false, false)); ?>
      <?php endwhile; ?>
      <?php else : ?>
      <?php _e( 'No people found. Please check the other content types.' ); ?>
      <?php endif; ?>
    </section>
    <?php rewind_posts(); ?>

    <!-- Documents -->
    <section class="vf-tabs__section" id="vf-tabs__section--documents">
      <?php if ( $documents_query->have_posts() ): ?>
      <?php while ( $documents_query->have_posts() ) : $documents_query->the_post(); ?>
      <?php include(locate_template('partials/vf-summary--document.php', false, false)); ?>
      <?php endwhile; ?>
      <?php else : ?>
      <?php _e( 'No documents found. Please check the other content types.' ); ?>
      <?php endif; ?>
    </section>
    <?php rewind_posts(); ?>

    <!-- News -->
    <section class="vf-tabs__section" id="vf-tabs__section--news">
      <?php if ( $insites_query->have_posts() ): ?>
      <?php while ( $insites_query->have_posts() ) : $insites_query->the_post(); ?>
      <?php include(locate_template('partials/vf-summary-insites-latest.php', false, false)); ?>
      <?php endwhile; ?>
      <?php else : ?>
      <?php _e( 'No articles found. Please check the other content types.' ); ?>
      <?php endif; ?>
    </section>
    <?php rewind_posts(); ?>

    <!-- Events -->
    <section class="vf-tabs__section" id="vf-tabs__section--events">
      <?php if ( $events_query->have_posts() ): ?>
      <?php while ( $events_query->have_posts() ) : $events_query->the_post(); ?>
      <?php include(locate_template('partials/vf-summary-events.php', false, false)); ?>
      <?php endwhile; ?>
      <?php else : ?>
      <?php _e( 'No events found. Please check the other content types.' ); ?>
      <?php endif; ?>
    </section>
    <?php rewind_posts(); ?>

    <section class="vf-tabs__section" id="vf-tabs__section--public">
      <?php include(locate_template('partials/swiftype-search.php', false, false));  ?>
    </section>
  </div>
</section>

<?php
if (class_exists('VF_Global_Footer')) {
  VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
} ?>

<?php get_footer(); ?>
