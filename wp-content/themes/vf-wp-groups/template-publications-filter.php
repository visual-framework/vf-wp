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
$years = array('2023', '2022', '2021', '2020', '2019', '2018', '2017', '2016', '2015');
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
          <select class="vf-form__select" id="vf-form__select" data-jplist-control="select-filter"
            data-group="data-group-1">
            <option value="0" data-path="default">All</option>
            <option data-path="default" value="0">2022</option>
            <?php
            foreach($years as $year) {
            ?>
            <option data-path=".<?php echo esc_attr($year); ?>" value="<?php echo esc_attr($year); ?>">
              <?php echo esc_html($year); ?> </option>
            <?php } ?>
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
    </div>
  </div>
</div>

<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>

<?php

get_footer();

?>
