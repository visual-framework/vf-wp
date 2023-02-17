<?php
/**
* Template Name: Publications with filter
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
<script>
</script>
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
            <option value="0" data-path="default" data-name="default" data-group="data-group-1">All</option>  
                  </select>
        </div>
      </fieldset>
    </form>
  </div>
  <div>


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
          No matching seminars found
        </p>
      </article>
    </div>

<!-- <nav class="vf-pagination" aria-label="Pagination" data-jplist-control="pagination" data-group="data-group-1"
     data-items-per-page="10" data-current-page="0" data-range="5" data-name="pagination1" id="paging-data">

  <ul class="vf-pagination__list" id="pagination_list">
    <li class="vf-pagination__item vf-pagination__item--previous-page" data-type="prev">
      <a class="vf-pagination__link" href="#">
        <span class="vf-pagination__label">Previous <span class="vf-u-sr-only"> page</span></span>
      </a>
    </li>
    <div data-type="pages" style="display: flex;">
      <li class="vf-pagination__item" data-type="page" data-current-page="{pageNumber}">
        <a href="#" class="vf-pagination__link">
          {pageNumber}
        </a>
      </li>
    </div>
    <li class="vf-pagination__item vf-pagination__item--next-page" data-type="next">
      <a href="#" class="vf-pagination__link">
        Next<span class="vf-u-sr-only"> page</span>
      </a>
    </li>
  </ul>
  <div class="vf-u-display-none totalrecords" data-type="info" id="totalrecords">{itemsNumber}</div>
</nav> -->

    </div>
    
  </div>
  
</div>

<script type="text/javascript">

              // getElementsByClassName only selects elements that have both given classes
              const pub = document.getElementsByClassName('publication-year');
            let result = [];
            for (let i = 0; i < pub.length; i++) {
              result += `  ${pub[i].textContent}`;
            }
            let myArray = result.split(" ");
            let uniqueChars = [...new Set(myArray)];
            var sel = document.querySelector('.vf-form__select');
            uniqueChars.forEach(unique => {
    let opt = document.createElement('option');
    opt.value = 'year-'+unique;
    opt.setAttribute('data-path', '.year-'+unique);
    opt.setAttribute('data-name', 'default');
    opt.setAttribute('data-group', 'data-group-1');
    opt.textContent += unique // or opt.innerHTML += user.name
    sel.appendChild(opt);
  });
  jplist.init({
  });

</script>

<?php

get_footer();

?>
