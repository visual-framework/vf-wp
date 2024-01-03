<section class="vf-summary-container">
  <div class="vf-section-header">
    <a href="/internal-information/events" class="vf-section-header__heading vf-section-header__heading--is-link">Events
      <svg class="vf-section-header__icon | vf-icon vf-icon-arrow--inline-end" width="24" height="24"
        xmlns="http://www.w3.org/2000/svg">
        <path
          d="M0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0C5.376.008.008 5.376 0 12zm13.707-5.209l4.5 4.5a1 1 0 010 1.414l-4.5 4.5a1 1 0 01-1.414-1.414l2.366-2.367a.25.25 0 00-.177-.424H6a1 1 0 010-2h8.482a.25.25 0 00.177-.427l-2.366-2.368a1 1 0 011.414-1.414z"
          fill="" fill-rule="nonzero"></path>
      </svg></a>
      <p class="vf-section-header__text">Events organised by EMBL exclusively for EMBL staff or external events in which EMBL has some form of involvement.

      </p>
  </div>
  <div class="vf-section-content | vf-u-margin__top--600">
  <div class="vf-grid vf-grid__col-3 | vf-content">
      <?php $mainEventloop = new WP_Query (array(
        'post_type' => 'events', 
        'posts_per_page' => 3,             
        'order' => 'ASC', 
        'orderby' => 'meta_value_num',
        'meta_key' => 'vf_event_internal_start_date', 
        'meta_query' => array(
          'relation' => 'OR',
          array(
              'key' => 'vf_event_internal_start_date',
              'value' => $current_date,
              'compare' => '>=',
              'type' => 'numeric'
          ),
          array(
              'key' => 'vf_event_internal_end_date',
              'value' => $current_date,
              'compare' => '>=',
              'type' => 'numeric'
          ),
          array(
            'key' => 'vf_event_internal_start_date',
            'value' => date('Ymd', strtotime('now')),
            'type' => 'numeric',
            'compare' => '>=',
            )         ))); 
      while ($mainEventloop->have_posts()) : $mainEventloop->the_post(); ?>
        <?php include(locate_template('partials/vf-summary-events.php', false, false)); ?>
        <?php endwhile;?>
        <?php wp_reset_postdata(); ?>
  </div>
      </div>
</section>
