<?php
get_header();
// Global Header
?>
<?php include(locate_template('partials/ebi_header.php', false, false)); ?>
<?php include(locate_template('partials/main_top_navigation.php', FALSE, FALSE)); ?>
<?php

if (class_exists('VF_WP_Hero')) {
  VF_Plugin::render(VF_WP_Hero::get_plugin('vf_wp_hero_group'));
}
global $post;
setup_postdata($post);
global $vf_theme;
$title = get_the_title();
$current_date = date('Ymd');
?>

<nav class="vf-navigation vf-navigation--main | vf-cluster">
  <ul class="vf-navigation__list | vf-list | vf-cluster__inner">
    <?php
    if (has_nav_menu('primary')) {
      wp_nav_menu([
        'theme_location' => 'primary',
        'depth' => 1,
        'container' => FALSE,
        'items_wrap' => '%3$s',
      ]);
    }
    ?>
  </ul>
</nav>

<?php
global $post;
setup_postdata($post);
global $vf_theme;
$title = get_the_title();
?>

<?php
$open_wrap = function ($html, $block_name) {
  $html = '
  <div class="embl-grid embl-grid--has-centered-content">
    <div></div>
    <div>
  ' . $html;
  return $html;
};
$close_wrap = function ($html, $block_name) {
  $html .= '
    </div>
    <div></div>
  </div>
  <!--/embl-grid-->';
  return $html;
};
add_filter(
  'vf/__experimental__/theme/content/open_block_wrap',
  $open_wrap,
  10, 2
);
add_filter(
  'vf/__experimental__/theme/content/close_block_wrap',
  $close_wrap,
  10, 2
);
?>


<section class="embl-grid embl-grid--has-centered-content | vf-content">
  <div>
  </div>
  <div class="vf-u-margin__bottom--800">
    <?php
    $search_placeholder_text = "Filter by event title";
    ?>
    <?php include(locate_template('partials/event_search_form.php', false, false)); ?>
  </div>

</section>
<section class="embl-grid | vf-content">
  <div>
    <?php
    $current_year = date('Y') + 1; // 1 year ahead
    $year_list = range(date('Y'), $current_year);
    $year_list = array_reverse($year_list);
    ?>
    <?php include(locate_template('partials/filter-public-event.php', FALSE, FALSE)); ?>
  </div>
  <div>
    <div data-jplist-group="data-group-1">
      <?php
      $forthcomingLoop = new WP_Query ([
        'post_type' => 'events',
        'order' => 'ASC',
        'orderby' => 'meta_value_num',
        'meta_key' => 'vf_event_start_date',
        'meta_query' => [
          [
            'key' => 'vf_event_start_date',
            'value' => $current_date,
            'compare' => '>',
            'type' => 'numeric',
          ],
          [
            'key' => 'vf_event_start_date',
            'value' => date('Ymd', strtotime('now')),
            'type' => 'numeric',
            'compare' => '>',
          ],
          [
            'key' => 'vf_event_event_type',
            'value' => 'public_event',
          ],
        ],
      ]);
      $temp_query = $wp_query;
      $wp_query = NULL;
      $wp_query = $forthcomingLoop;
      $current_month = ""; ?>
      <?php while ($forthcomingLoop->have_posts()) : $forthcomingLoop->the_post(); ?>
        <?php
        include(locate_template('partials/vf-summary-events.php', FALSE, FALSE)); ?>
      <?php endwhile; ?>
      <!-- no results control -->
      <article class="vf-summary vf-summary--event jplist-text-area" data-jplist-control="no-results" data-group="data-group-1" data-name="no-results" id="no-results">
        <p class="vf-summary__text">
          No matching events found
        </p>
      </article>

    </div>
    <div>
      <?php
      $wp_query = NULL;
      $wp_query = $temp_query;
      ?>
      <style>
        .jplist-selected {
          background-color: #707372;
        }

        .jplist-selected a {
          color: #fff;
        }

      </style>
      <?php include(locate_template('partials/paging-controls.php', false, false)); ?>
    </div>
  </div>

</section>

<section class="vf-content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; endif; ?></section>

<script type="text/javascript">
  jplist.init();

</script>
<?php include(locate_template('partials/ebi_footer.php', false, false)); ?>

<?php get_footer(); ?>
