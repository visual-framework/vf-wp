<?php
/**
* Template Name: Publications
*/

get_header();
// The plugin is missing so use the default page template
if ( ! class_exists('VF_Publications')) {
  get_template_part('page');
  return;
}
$vf_publications = VF_Plugin::get_plugin('vf_publications');

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
        placeholder="Search by title" data-clear-btn-id="name-clear-btn">
    </div>
    <p class="vf-u-margin__bottom--0">Total number of publications: <span style="font-weight: 700;"
    data-jplist-control="counter"
    data-group="data-group-1"
    data-format="{count}"
    data-path=".vf-summary__title"
    data-mode="static"
    data-name="counter-barcelona-filter"
    data-filter-type="path"></span></p>
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
            <option value="0" data-path="default" data-name="default" data-group="data-group-1">All</option>
          </select>
        </div>
      </fieldset>
    </form>
  </div>
  <div>

    <div id="content">
      <div data-jplist-group="data-group-1">
        <?php

        VF_Plugin::render($vf_publications);

        // the_content();
        $vf_theme->the_content();

        ?>
        <!-- no results control -->
        <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
          data-name="no-results">
          <p class="vf-summary__text">
            No matching publications found
          </p>
        </article>
      </div>
      <?php include(locate_template('partials/paging-controls.php', false, false)); ?>

    </div>
  </div>
</div>
</div>

<script type="text/javascript">

// get all the values based on the class name
const publicationYear = document.getElementsByClassName('publication-year');
let allYears = [];
for (let i = 0; i < publicationYear.length; i++) {
  allYears += `  ${publicationYear[i].textContent}`;
  if (allYears[i] == 2) {}
}
// create an array
let YearsArray = allYears.split(" ");

// count the occurrences
const counts = {};
for (const num of YearsArray) {
  counts[num] = counts[num] ? counts[num] + 1 : 1;
}

// create an array without the duplicates and remove empty values
let uniqueYears = [...new Set(YearsArray)].filter(function (e) {
  return e
});

// loop thorugh the values and create option elements
var sel = document.querySelector('.vf-form__select');
uniqueYears.forEach(unique => {
  let opt = document.createElement('option');
  opt.value = 'year-' + unique;
  opt.setAttribute('data-path', '.year-' + unique);
  opt.setAttribute('data-name', 'default');
  opt.setAttribute('data-group', 'data-group-1');
  opt.textContent += unique + ' (' + counts[unique] + ')'; 
  sel.appendChild(opt);
});

// activate jplist library
jplist.init({});

</script>

<?php

get_footer();

?>
