<?php get_header(); 

$total_results = $wp_query->found_posts;

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
  class="embl-grid embl-grid--has-centered-content vf-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <div>
  <form role="search" method="get" class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end"
        action="<?php echo esc_url(home_url('/')); ?>">
        <div class="vf-sidebar__inner">
          <div class="vf-form__item | vf-search__item">
            <input type="search" class="vf-form__input | vf-search__input" placeholder="Enter your search term"
              value="<?php echo esc_attr(get_search_query()); ?>" name="s">
          </div>
          <div class="vf-form__item | vf-search__item" style="display: none">
            <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
            <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
              <option value="any" selected="">Everything</option>
              <option value="page" name="post_type[]">Pages</option>
              <option value="internal-news" name="post_type[]">Internal news</option>
              <option value="events" name="post_type[]">Events</option>
              <option value="people" name="post_type[]">People</option>
              <option value="documents" name="post_type[]">Documents</option>
            </select>
          </div>
          <input type="submit" class="vf-search__button | vf-button vf-button--primary"
            value="<?php esc_attr_e('Search', 'vfwp'); ?>">
        </div>
      </form>
</div>
</section>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
<?php include(locate_template('partials/search-filter.php', false, false)); 
 ?>
  </div>
  <div>
  <div class="vf-content">
  <p><?php echo $total_results; ?> result(s) found for "<?php echo esc_html(get_search_query()); ?>"</p>

    <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            if(isset($_GET['post_type'])) {
              $type = $_GET['post_type'];

              if($type == 'any') {
                include(locate_template('partials/search-results.php', false, false)); 
              } 
              elseif($type == 'people') {
                include(locate_template('partials/vf-profile.php', false, false)); 
              } 
              elseif($type == 'page') {
                include(locate_template('partials/vf-summary--page.php', false, false)); 
              }
              elseif($type == 'internal-news') {
                include(locate_template('partials/vf-summary-insites-latest.php', false, false)); 
              }
              elseif($type == 'documents') {
                include(locate_template('partials/vf-summary--document.php', false, false)); 
              }
              elseif($type == 'events') {
                include(locate_template('partials/vf-summary-events.php', false, false)); 
              }
          }
          if (($wp_query->current_post + 1) < ($wp_query->post_count)) {
            echo '<hr class="vf-divider">';
         }
          }
        } else {
          echo '<p>', __('No results found', 'vfwp'), '</p>';
        } ?>
  </div>
    <div class="vf-grid"> <?php vf_pagination();?></div>
  </div>
</section>
<?php get_footer(); ?>