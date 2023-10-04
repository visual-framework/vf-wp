<?php

get_header();

?>


<section class="vf-intro | vf-u-margin__bottom--0">
  <div>
    <!-- empty -->
  </div>
  <div class="vf-stack">
    <h1 class="vf-intro__heading">
      <?php wp_title(''); ?>
    </h1>
    <p class="vf-lede">The repository holds the digital copies of internal EMBL documents, reports, brochures
      and various other publications.</p>
    <p class="vf-intro__text"><?php
      printf(
        esc_html__('There are currently %1$d documents in the repository', 'vfwp'),
        get_all_documents_posts()
      ); ?></p>
    <br>
  </div>
</section>

<div
  class="embl-grid embl-grid--has-centered-content | vf-u-padding__top--500 vf-u-padding__bottom--500 | vf-u-margin__bottom--800">
  <div></div>
  <div class="vf-form__item">
    <input id="search" class="vf-form__input vf-form__input--filter" data-jplist-control="textbox-filter"
      data-group="data-group-1" data-name="my-filter-1" data-path=".vf-summary__title" type="text" value=""
      placeholder="Filter by document title" data-clear-btn-id="name-clear-btn">
  </div>
</div>

<section class="embl-grid embl-grid--has-centered-content">
  <div>
    <?php include(locate_template('partials/document-filter.php', false, false)); ?>
  </div>
  <div>
    <div data-jplist-group="data-group-1">
      <?php
  		$mainQuery = new WP_Query (array(
        'posts_per_page' => -1, 
        'post_type' => 'documents', 
        ));
    while ($mainQuery->have_posts()) : $mainQuery->the_post(); ?>
      <?php include(locate_template('partials/vf-summary--document.php', false, false)); ?>
      <?php endwhile;?>
      <?php wp_reset_postdata(); ?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event" data-jplist-control="no-results" data-group="data-group-1"
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

<script type="text/javascript">
    jplist.init({
      deepLinking: true
    });
</script>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {

    // this is a sort of callback on jplist to enforce showing newest events first.
    // Unfortunately, the jplist sorting does not combine reliably will multiple facets. 
    // This is an interim solution until we replace jplist.
    function sortEvents() {
      var eventsContainer = document.querySelectorAll("[data-jplist-group]")[0];
      var events = document.querySelectorAll("[data-jplist-item]");
      var eventsArr = [];

      // eventsContainer.innerHTML = "Rendering";

      for (var i in events) {
        if (events[i].nodeType == 1) { // get rid of the whitespace text nodes
          eventsArr.push(events[i]);
        }
      }

      eventsArr.sort(function(a, b) {
        // console.log(a.querySelectorAll("[data-eventtime]")[0]);
        return +b.querySelectorAll("[data-eventtime]")[0].dataset.eventtime - +a.querySelectorAll("[data-eventtime]")[0].dataset.eventtime;
      });

      // console.log('eventsArr',eventsArr)

      for (i = 0; i < eventsArr.length; ++i) {
        eventsContainer.appendChild(eventsArr[i]);
      }
    }

    var inputs = document.querySelectorAll('input');
    // brute force to refresh jplist to ensure date filtering is intact
    inputs.forEach(function(item) {
      item.addEventListener('keydown', function(e) {
        setTimeout(function(){ sortEvents() }, 300);
      });
      item.addEventListener("change", function(e) {
        // jplist.refresh();
        sortEvents();
        // setTimeout(function(){ sortEvents() }, 300);
      });
    });

    // sort on page load
    sortEvents();
    // setTimeout(function(){ sortEvents() }, 300);
  });
</script>

<?php

get_footer();

?>
