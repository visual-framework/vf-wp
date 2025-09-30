<?php

get_header();
global $vf_theme;
$today_date = date('Ymd');
?>
<style>
    .vf-card__heading {
        font-size: 21px;
    }
    .card {    flex-direction: column;
    display: flex
;
}
</style>
<section style="display: none;" class="vf-card-container vf-card-container__col-4 | vf-u-background-color-ui--white vf-u-fullbleed" style="--vf-card__image--aspect-ratio: 16 / 9;">
  <div class="vf-card-container__inner">
    <div class="vf-section-header">
      <h2 class="vf-section-header__heading">Featured courses</h2>
    </div>

<article class="vf-card vf-card--brand vf-card--bordered">
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--0"><span class="vf-badge vf-badge--primary customBadgeGreenDark | search-data-od">EMBL-EBI Training</span></p>
    <article class="">
      <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/EMBL_Services_EDIT_COVID-PORTAL_CMYK-m.jpg" alt="" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="JavaScript:Void(0);">A guide to sequence similarity search for biomolecular sequences 
          </a></h3>
        <p class="vf-card__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.  </p>
      </div>
    </article>
</article>

<article class="vf-card vf-card--brand vf-card--bordered">
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--0"><span class="vf-badge vf-badge--primary customBadgeGreenDark | search-data-od">EMBL External Training</span></p>
    <article class="">

      <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/Metabolomics_31-m.jpg" alt="" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="JavaScript:Void(0);">UniProt and Alzheimer’s Disease: Linking molecular defects to disease phenotype 
          </a></h3>
        <p class="vf-card__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
      </div>
    </article>
</article>

<article class="vf-card vf-card--brand vf-card--bordered">
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--0"><span class="vf-badge vf-badge--primary customBadgeGreenDark | search-data-od">EMBL Internal Training</span></p>
    <article class="">

      <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/EES18-01DSC_5740-l-1.jpg" alt="" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="JavaScript:Void(0);">Working with sensitive data 
          </a></h3>
        <p class="vf-card__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p>
      </div>
    </article>
    </article>
    <article class="vf-card vf-card--brand vf-card--bordered">
    <p class="vf-u-margin__top--0 vf-u-margin__bottom--0"><span class="vf-badge vf-badge--primary customBadgeGreenDark | search-data-od">SEPE</span></p>
    <article class="">

      <img src="https://acxngcvroo.cloudimg.io/v7/https://www.embl.org/files/wp-content/uploads/ELLS-container_training_150ppi.png" alt="" class="vf-card__image" loading="lazy">
      <div class="vf-card__content | vf-stack vf-stack--400">
        <h3 class="vf-card__heading"><a class="vf-card__link" href="JavaScript:Void(0);">Supercomputer-based modelling and simulation for advanced biomedical applications 
          </a></h3>
        <p class="vf-card__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p>
      </div>
    </article>
    </article>
  </div>
</section>

    <div class="embl-grid embl-grid--has-centered-content vf-u-padding__bottom--800 vf-u-padding__top--800 | vf-content">
      <div></div>
      <div class="vf-content">

        <form action="#onDemandFilter" onsubmit="return false;"
          class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
          <div class="vf-sidebar__inner">
            <div class="vf-form__item">
              <label class="vf-form__label vf-u-sr-only | vf-search__label" for="search">Search</label>
              <input id="search-od" class="vf-form__input vf-form__input--filter | inputOnDemand" data-jplist-control="textbox-filter"
                data-group="data-group-2" data-name="my-filter-2" data-path=".search-data-od" type="text" value=""
                placeholder="Enter your search term" data-clear-btn-id="name-clear-btn">
            </div>
            <button style="display: none;" type="button" id="name-clear-btn"
              class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
              <span class="vf-button__text">Reset</span>
            </button>
          </div>
        </form>
        <p class="vf-text-body vf-text-body--2 | vf-u-text-color--grey--darkest | vf-u-margin__bottom--0 vf-u-margin__top--600"
          id="total-results-info-od">Showing <span id="start-counter-od" class="counter-highlight"></span><span
            id="end-counter-od" class="counter-highlight"></span> results out of <span id="total-result-od"
            class="counter-highlight"></span></p>
      </div>
    </div>
    <div class="embl-grid embl-grid--has-centered-content | vf-u-margin__bottom--800">

      <div>
        <?php include(locate_template('partials/training/training-od-filter.php', false, false)); ?>
      </div>
      <main>
        <div id="on-demand-events" data-jplist-group="data-group-2">
          <?php
  $onDemandLoop = new WP_Query(array(
  'post_type'      => 'training',
  'posts_per_page' => -1,
  'post_status'    => 'publish',
  'orderby'        => 'title',   // order by post title
  'order'          => 'ASC'      // ascending = A → Z
));

        $temp_query = $wp_query;
        $wp_query   = NULL;
        $wp_query   = $onDemandLoop;
          $current_month = ""; ?>
          <?php while ($onDemandLoop->have_posts()) : $onDemandLoop->the_post();?>
          <?php
         include(locate_template('partials/training/vf-summary--training-on-demand.php', false, false)); ?>
          <?php endwhile;?>
          <!-- no results control -->
          <article class="vf-summary" data-jplist-control="no-results" data-group="data-group-2" data-name="no-results">
            <p class="vf-summary__text">
              No results found
            </p>
          </article>
        </div>
        <nav id="paging-data2" class="vf-pagination" aria-label="Pagination">
          <ul class="vf-pagination__list | paginationListOnDemand"></ul>
        </nav>
      </main>
      <div class="vf-content">
        <h3 class="vf-text vf-text-heading--5">Training providers</h3>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom--400"><a
        href="#">EMBL-EBI training</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
        href="#">EMBL external training</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
             href="#">EMBL internal training</a></p>
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
            href="#">Science Education and Public Engagement (SEPE)</a></p>
        <hr class="vf-divider | vf-u-margin__bottom--400">
        <p class="vf-text-body vf-text-body--3 | vf-u-margin__bottom-400"><a
        href="#">Provide your feedback</a></p>
      </div>

    </div>

<style>
  :root {
    --embl-grid-module--prime: 16.1rem !important;
  }

</style>

<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const onlineCourseCheckbox = document.getElementById('type-online_course');
  const subtypeFieldset = document.getElementById('checkbox-filter-subtype');
  const typeFieldset = document.getElementById('checkbox-filter-type');

  if (onlineCourseCheckbox && subtypeFieldset) {
    onlineCourseCheckbox.addEventListener('change', function () {
      if (this.checked) {
        subtypeFieldset.style.display = 'block';
        typeFieldset.style.marginBottom = '0';
      } else {
        subtypeFieldset.style.display = 'none';
        typeFieldset.style.marginBottom = '2rem';
      }
    });
  }
});
</script>



<script>
function resetInputs() {
  // to be added
}
</script>
<?php  include(locate_template('partials/training/pagination-on-demand.php', false, false)); ?>

<?php

get_footer();

?>
