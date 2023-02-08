<?php
/**
* Template Name: Publications with filter
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

<div class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">
  <div></div>
  <div>
  <h1 class="vf-text vf-text-heading--1">
      <?php the_title(); ?>
    </h1>
    <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
            placeholder="Filter by title" data-clear-btn-id="name-clear-btn">
   </div>
  </div>
</div>

<div class="embl-grid embl-grid--has-centered-content">
  <div>
  <form class="vf-stack vf-stack-400 | vf-u-margin__bottom--400">
  

  <fieldset class="vf-form__fieldset vf-stack vf-stack--400">
  <legend class="vf-form__legend">Select year</legend>
    <div class="vf-form__item vf-stack">
  <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-filter" data-group="data-group-1">
  <option value="0" data-path="default" data-name="default" data-group="data-group-1">All</option>
          <option data-path=".vf-summary__date" value="2022" data-name="default" data-group="data-group-1">2022</option>
          </select>
    </div>
  </fieldset>
</form>
  </div>
  <div>


   <div data-jplist-group="data-group-1">

    <?php

      VF_Plugin::render($vf_publications);

      echo '<hr class="vf-divider">';

      // the_content();
      $vf_theme->the_content();

      ?>
    <?php /*
      <form id="publications-filter" role="search" method="get" action="<?php the_permalink(); ?>">
    <div class="vf-search vf-search--inline">
      <div class="vf-form__item | vf-search__item">
        <label class="vf-form__label vf-sr-only | vf-search__label"
          for="filter_keyword"><?php _e('Search by keyword:', 'vfwp'); ?></label>
        <input class="vf-form__input | vf-search__input" value="<?php echo esc_attr($keyword); ?>"
          placeholder="<?php esc_attr_e('Search publications...', 'vfwp'); ?>" name="filter_keyword">
      </div>
      <input type="submit" class="vf-search__button | vf-button vf-button--primary"
        value="<?php esc_attr_e('Search', 'vfwp'); ?>">
    </div>
    <div class="vf-box vf-box--inlay">
      <h3 class="vf-box__heading"><?php _e('Publications by year', 'vfwp'); ?></h3>
      <div class="vf-form">
        <label class="vf-sr-only" for="filter_year">Categories</label>
        <select class="vf-form__select" name="filter_year"
          onchange="document.querySelector('#publications-filter').submit();">
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
    */ ?>
  </div>
  </div>
</div>

<script type="text/javascript">
  jplist.init({deepLinking: true});

</script>


<?php

get_footer();

?>
