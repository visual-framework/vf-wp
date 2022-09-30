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
);
$page_query = new WP_Query( $page_args );

// People search query
// $people_args = array(
//   'post_type' => 'people',
//   'posts_per_page' => -1,
//    's' => get_search_query(), 
//    'relevanssi' => true,
// );
// $people_query = new WP_Query( $people_args );

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

  </style>
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--200">
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
      Intranet search
    </h1>
    <div class="vf-banner vf-banner--alert vf-banner--info">
      <div class="vf-banner__content">
        <p class="vf-banner__text">Can't find what you need on the intranet? It may be on the public website <a
            class="vf-banner__link" href="https://www.embl.org/search">embl.org/search</a></p>
      </div>
    </div>
</section>
<!-- <div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
            placeholder="Filter by seminar title" data-clear-btn-id="name-clear-btn">
   </div>
</div> -->


<section
  class="embl-grid embl-grid--has-centered-content vf-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--0">
  <div></div>
  <div>
  <form role="search" method="get"
    class="vf-form vf-form--search vf-form--search--<?php echo esc_html($type); ?> | vf-sidebar vf-sidebar--end"
    action="<?php echo esc_url(home_url('/')); ?>">
    <div class="vf-sidebar__inner">
      <div class="vf-form__item | vf-search__item">
      <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".search" type="text"
            placeholder="Enter your search term" data-clear-btn-id="name-clear-btn"
            value="<?php echo esc_attr(get_search_query()); ?>" name="s">      </div>
      <button type="submit" class="vf-search__button | vf-button vf-button--primary"
        value="<?php esc_attr_e('Search', 'vfwp'); ?>">
        <span class="vf-button__text">Search</span>
      </button>
    </div>
  </form>






  </div>
</section>
<div id="search-content" class="vf-stack vf-stack--400">

  <section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--600">
    <div>

    <?php include(locate_template('partials/search-filter.php', false, false)); ?>



    </div>

    <div data-jplist-group="data-group-1">

      <!-- Pages -->
      <div >
      <?php
            
            $pages_json_feed_api_endpoint = 'https://www.embl.org/internal-information/wp-json/wp/v2/pages?per_page=100';
            $raw_content = file_get_contents($pages_json_feed_api_endpoint);
            $pages_data = json_decode($raw_content, true);
                
    
            if (!empty($pages_data) && is_array($pages_data)) {
              foreach ($pages_data as $page) {
              $title = $page['title']['rendered']; 
              $link = $page['link']; 
                                     ?>
<article class="vf-summary" data-jplist-item>
  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php echo $link; ?>" class="vf-summary__link"><?php echo $title; ?></a>
  </h2>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
</p>

  <div class="vf-summary__meta"><a href="<?php echo $link; ?>"
      class="vf-summary__author vf-summary__link"><?php echo $link; ?></a></div>
  <p class="page vf-u-display-none | used-for-filtering">Page</p>
</article>          <?php
                }
            }
            ?>

      </div>

      <!-- People -->
      <div class="vf-content">
        <div class="embl-content-hub-loader | vf-grid vf-grid__col-1" ">
          <?php
            
            $people_json_feed_api_endpoint = 'https://content.embl.org/api/v1/people-all-info?items_per_page=200';
            $raw_content = file_get_contents($people_json_feed_api_endpoint);
            $raw_content_decoded = json_decode($raw_content, true);
            $people_data = $raw_content_decoded['rows'];  
                
    
            if (!empty($people_data) && is_array($people_data)) {
              foreach ($people_data as $person) {
              $title = $person['full_name']; 
              $orcid = $person['orcid'];
              $photo = $person['photo'];
              $email = $person['email'];
              $biography = $person['biography'];
              $room = $person['room'];
              $bdr_id = $person['bdr_public_id'];
              $outstation = $person['outstation'];
              $telephones = $person['telephones'];
              $positions = $person['positions'];
      
              if (!empty($telephones[0])) {
                  $telephone = $person['telephones'][0]['telephone'];
              }
              
              if (!empty($positions[0])) {
                  $positions_name_1 = $person['positions'][0]['name'];
                  $team_name_1 = $person['positions'][0]['team_name'];
                  $team_url_1 = $person['positions'][0]['team_url'];
                  $is_primary_1 = $person['positions'][0]['is_primary'];
              }
              if (!empty($positions[1])) {
                  $positions_name_2 = $person['positions'][1]['name'];
                  $team_name_2 = $person['positions'][1]['team_name'];
                  $is_primary_2 = $person['positions'][1]['is_primary'];
              }
              if (!empty($positions[2])) {
                  $positions_name_3 = $person['positions'][2]['name'];
                  $team_name_3 = $person['positions'][2]['team_name'];
                  $is_primary_3 = $person['positions'][2]['is_primary'];
              }
              if (!empty($positions[3])) {
                  $positions_name_4 = $person['positions'][3]['name'];
                  $team_name_4 = $person['positions'][3]['team_name'];
                  $is_primary_4 = $person['positions'][3]['is_primary'];
              } 
                                     ?>
          <article class="vf-profile vf-profile--medium vf-profile--inline | vf-u-margin__bottom--600" data-jplist-item>

            <img class="vf-profile__image" src="<?php echo $photo; ?> " alt="" loading="lazy">
            <h3 class="vf-profile__title | search">
              <a href="https://www.embl.org/internal-information/people/<?php  echo $bdr_id; ?>"
                class="vf-profile__link"><?php  echo $title; ?></a>
            </h3>

            <?php if (!empty($positions)) { ?>
            <p class="vf-profile__job-title"><?php  echo $positions_name_1; ?></p>
            <p class="vf-profile__text | team-search" style="margin-bottom: 1rem;">
              <a class="vf-link" href="<?php  echo $team_url_1; ?>"><?php  echo $team_name_1; ?></a></p>
            <?php } ?>


            <p class="people vf-u-display-none | used-for-filtering">People</p>

          </article>
          <?php
                }
            }
            ?>
        </div>

        <!-- no results control -->
        <!-- <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
          data-name="no-results">
          <p class="vf-summary__text">
            No people found
          </p>
        </article> -->
      </div>

  <!-- Documents -->




  <!-- <section class="vf-tabs__section" id="vf-tabs__section--public">
      <?php // include(locate_template('partials/swiftype-search.php', false, false));  ?>
    </section> -->

  </div>
  </section>



<section id="load-container" class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--200">
  <div></div>
  <div id="load"></div>
</section>


<?php
if (class_exists('VF_Global_Footer')) {
  VF_Plugin::render(VF_Global_Footer::get_plugin('vf_global_footer'));
} ?>

<?php get_footer(); ?>

<script>
  function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 1000);

    function checkReady() {
      if (document.getElementsByTagName('body')[0] !== undefined) {
        window.clearInterval(intervalID);
        callback.call(this);
      }
    }
  }

  function show(id, value) {
    document.getElementById(id).style.display = value ? 'block' : 'none';
  }

  onReady(function () {
    show('search-content', true);
    show('load', false);
    show('load-container', false);
  });

</script>
<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>
