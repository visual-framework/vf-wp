<?php
/**
* Template Name: Publications
*/

// The plugin is missing so use the default page template
if ( ! class_exists('VF_Publications')) {
  get_template_part('page');
  return;
}

$vf_publications = VF_Plugin::get_plugin('vf_publications');

get_header();

$keyword = $vf_publications->get_query_keyword();

global $vf_theme;

?>
<section class="vf-inlay">
  <div class="vf-inlay__content vf-u-background-color-ui--white">
    <main class="vf-inlay__content--main">
      <h1 class="vf-text vf-text-heading--1"><?php the_title(); ?></h1>
      <?php

      VF_Plugin::render($vf_publications);

      echo '<hr class="vf-divider">';

      // the_content();
      $vf_theme->the_content();

      ?>
    </main>
    <aside class="vf-inlay__content--additional">
      <form id="publications-filter" role="search" method="get" action="<?php the_permalink(); ?>">
        <div class="vf-search vf-search--inline">
          <div class="vf-form__item | vf-search__item">
            <label class="vf-form__label vf-sr-only | vf-search__label" for="filter_keyword"><?php _e('Search by keyword:', 'vfwp'); ?></label>
            <input class="vf-form__input | vf-search__input" value="<?php echo esc_attr($keyword); ?>" placeholder="<?php esc_attr_e('Search publications...', 'vfwp'); ?>" name="filter_keyword">
          </div>
          <input type="submit" class="vf-search__button | vf-button vf-button--primary" value="<?php esc_attr_e('Search', 'vfwp'); ?>">
        </div>
        <div class="vf-box vf-box--inlay">
          <h3 class="vf-box__heading"><?php _e('Publications by year', 'vfwp'); ?></h3>
          <div class="vf-form">
            <label class="vf-sr-only" for="filter_year">Categories</label>
            <select class="vf-form__select" name="filter_year" onchange="document.querySelector('#publications-filter').submit();">
              <option value=""><?php _e('Select year', 'vfwp'); ?></option>
              <?php
              foreach ($vf_publications->get_years() as $key => $value) {
                echo '<option';
                echo ' value="' . esc_attr($key) . '"';
                if ($key === $vf_publications->get_query_year()) {
                  echo ' selected';
                }
                echo '>';
                echo esc_html($value);
                echo '</option>';
              }
              ?>
            </select>
          </div>
        </div>
      </form>
    </aside>
  </div>
</section>
<?php

get_footer();

?>
