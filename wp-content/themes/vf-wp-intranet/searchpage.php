
<?php 
/**
 * Template Name: Search Page
 */
?> 
<?php get_header(); 

$total_results = $wp_query->found_posts;

?>

<section class="embl-grid embl-grid--has-centered-content">
  <div></div>
  <div class="vf-content">
    <h1 class="vf-text-heading--1">Search</h1>
    <div>
      <form role="search" method="get" class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar"
        action="<?php echo esc_url(home_url('/')); ?>">
        <div class="vf-sidebar__inner">
          <div class="vf-form__item | vf-search__item">
            <input type="search" class="vf-form__input | vf-search__input" placeholder="Enter your search term"
              value="<?php echo esc_attr(get_search_query()); ?>" name="s">
          </div>
          <div class="vf-form__item | vf-search__item">
            <label class="vf-form__label vf-u-sr-only | vf-search__label" for="vf-form__select">Category</label>
            <select class="vf-form__select" id="vf-form__select" name="post_type" value="post_type">
              <option value="any" selected="">Everything</option>
              <option value="page" name="post_type[]">Pages</option>
              <option value="insites" name="post_type[]">INsites</option>
              <option value="vevents" name="post_type[]">Events</option>
              <option value="people" name="post_type[]">People</option>
              <option value="documents" name="post_type[]">Documents</option>
            </select>
          </div>
          <input type="submit" class="vf-search__button | vf-button vf-button--primary vf-button--sm"
            value="<?php esc_attr_e('Search', 'vfwp'); ?>">
        </div>
      </form>
    </div>
  </div>
</section>

<?php get_footer(); ?>
