<?php get_header(); 

if (class_exists('VF_Global_Header')) {
  VF_Plugin::render(VF_Global_Header::get_plugin('vf_global_header'));
}
if (class_exists('VF_Intranet_Breadcrumbs')) {
  VF_Plugin::render(VF_Intranet_Breadcrumbs::get_plugin('vf_wp_breadcrumbs_intranet'));
}

?>

<section class="vf-hero | vf-u-fullbleed | vf-hero--800 | vf-u-margin__bottom--0">
  <style>
    .vf-hero {
      --vf-hero--bg-image: url('https://www.embl.org/internal-information/wp-content/uploads/20220325_Intranet-hero-scaled.jpg');

            }
  </style>  
  <div class="vf-hero__content | vf-box | vf-stack vf-stack--200">
    <h1 class="vf-hero__heading">
      <a class="vf-hero__heading_link" href="https://www.embl.org/internal-information">
        EMBL Intranet </a>
    </h1>
    <p><span class="vf-text-body vf-text-body--3 | vf-u-margin__right--100">Directories:</span>  
      <span><a class="vf-badge vf-badge--tertiary | vf-u-margin__right--100 | intranet-directory-badge" href="/internal-information/people">People</a></span>    
      <span><a class="vf-badge vf-badge--tertiary | vf-u-margin__right--100 | intranet-directory-badge" href="/internal-information/documents">Documents</a></span>    
      <span><a class="vf-badge vf-badge--tertiary | intranet-directory-badge" href="/internal-information/seminars">Seminars</a></span></p>
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

<section
  class="embl-grid embl-grid--has-centered-content vf-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--0">
  <div></div>
  <div>
    <form role="search" method="get"
      class="vf-form vf-form--search vf-form--search--<?php echo esc_html($type); ?> | vf-sidebar vf-sidebar--end"
      action="<?php echo esc_url(home_url('/')); ?>">
      <div class="vf-sidebar__inner">
        <div class="vf-form__item | vf-search__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-group="data-group-1"
            data-name="my-filter-1" data-path=".search" type="text" placeholder="Enter your search term"
            data-clear-btn-id="name-clear-btn" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
        </div>
        <div class="vf-form__item | vf-search__item" style="display: none">
          <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
          <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
            <option value="any" selected="">Everything</option>
            <option value="page" name="post_type[]">Pages</option>
            <option value="insites" name="post_type[]">Internal news</option>
            <option value="events" name="post_type[]">Events</option>
            <option value="people" name="post_type[]">People</option>
            <option value="documents" name="post_type[]">Documents</option>
          </select>
        </div>
        <button type="submit" class="vf-search__button | vf-button vf-button--primary"
          value="<?php esc_attr_e('Search', 'vfwp'); ?>">
          <span class="vf-button__text">Search</span>
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
  </div>
</section>
<!-- spinner -->
<section id="load-container" class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--200">
  <div></div>
  <div id="load"></div>
</section>

<div id="search-content" class="vf-stack vf-stack--400">
  <section class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--600">
    <div>
      <?php include(locate_template('partials/search-filter.php', false, false)); ?>
    </div>
    <div data-jplist-group="data-group-1">
      <div>
        <?php
            
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--page.php', false, false)); 
          }
        } else {
          echo '<p>', __('No results found', 'vfwp'), '</p>';
        } ?>
      </div>
    </div>
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
