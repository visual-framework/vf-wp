<?php

if (
  is_tax('document_topic') ||
  is_tax('document_type')
) {
  get_template_part('archive-document');
  return;
}

get_header();

?>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800 | vf-content">
  <div></div>
  <div>
  <p><?php
      printf(
        esc_html__('There are currently %1$d documents in the repository', 'vfwp'),
        get_all_them_posts()
      ); ?></p>


  <form action="#eventsFilter" onsubmit="return false;"
    class="vf-form vf-form--search vf-form--search--responsive | vf-sidebar vf-sidebar--end">
    <div class="vf-sidebar__inner">
    <div class="vf-form__item">
          <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
            data-group="documents" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
            placeholder="Enter your search term" data-clear-btn-id="name-clear-btn">
        </div>
        <button style="display: none;" type="button" id="name-clear-btn"
          class="vf-search__button | vf-button vf-button--tertiary vf-button--sm">
          <span class="vf-button__text">Reset</span>
        </button>

    </div>
  </form>
  </div>

</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/document-filter.php', false, false)); ?>
  </div>
  <div>
    <div data-jplist-group="documents">
      <?php
        if ( have_posts() ) {
          while ( have_posts() ) {
            the_post();
            include(locate_template('partials/vf-summary--document.php', false, false)); 
          }
        } ?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="documents"
        data-name="no-results">
        <p class="vf-summary__text">
          No matching documents found
        </p>
      </article>
    </div>

    <?php include(locate_template('partials/paging-controls.php', false, false)); ?>

  </div>
  <div></div>
</section>
<!--/vf-grid-->


<script type="text/javascript">
  jplist.init({
    deepLinking: true
  });

</script>

<?php

get_footer();

?>
