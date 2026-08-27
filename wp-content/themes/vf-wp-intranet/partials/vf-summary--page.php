<?php

global $vfwp_indexed_search_result;

$has_indexed_search_result = is_array($vfwp_indexed_search_result);
$search_terms = array();
if (is_search() && function_exists('vfwp_search_get_query_terms')) {
  $search_terms = vfwp_search_get_query_terms(get_search_query(false));
}

$raw_title = get_the_title();
$title = $has_indexed_search_result && !empty($vfwp_indexed_search_result['title_highlighted'])
  ? $vfwp_indexed_search_result['title_highlighted']
  : (!empty($search_terms) && function_exists('vfwp_search_highlight_text')
  ? vfwp_search_highlight_text($raw_title, $search_terms)
  : esc_html($raw_title));
$post_type = get_post_type();
$indexed_search_url = $has_indexed_search_result && !empty($vfwp_indexed_search_result['url'])
  ? $vfwp_indexed_search_result['url']
  : '';
$summary_link_url = $indexed_search_url !== '' ? $indexed_search_url : get_permalink();
$redirect_url = '';
$vfwp_result_type_label = '';
$vfwp_result_meta_text = '';
$vfwp_show_result_type_badge = false;
$position = '';
$outstation = '';
$type = '';
$training_category = '';
$training_location = array();
$training_date = '';
$training_date_formatted = false;
$teamArray = array();

if ($post_type === 'people') {
  $position = get_field('positions_name_1', $post->ID);
  $outstation = get_field('outstation', $post->ID);
  $teamArray = array(
    array(
      "team" => get_field('team_name_1', $post->ID),
      "isPrimary" => get_field('is_primary_1', $post->ID),
    ),
    array(
      "team" => get_field('team_name_2', $post->ID),
      "isPrimary" => get_field('is_primary_2', $post->ID),
    ),
    array(
      "team" => get_field('team_name_3', $post->ID),
      "isPrimary" => get_field('is_primary_3', $post->ID),
    ),
    array(
      "team" => get_field('team_name_4', $post->ID),
      "isPrimary" => get_field('is_primary_4', $post->ID),
    ),
  );
  $key_values = array_column($teamArray, 'isPrimary');
  array_multisort($key_values, SORT_DESC, $teamArray);
  $teamArray = array_map('array_filter', $teamArray);
  $teamArray = array_filter($teamArray);
} elseif ($post_type === 'training') {
  $type = get_field('vf-wp-training-training_type', $post->ID);
  $training_date = get_field('vf-wp-training-start_date', $post->ID);
  if (is_string($training_date) && $training_date !== '') {
    $training_date_formatted = DateTime::createFromFormat('Ymd', $training_date);
  }
  $training_category = get_field('vf-wp-training-category', $post->ID);
  $training_location = get_the_terms( $post->ID , 'event-location' );
} elseif (($post_type === 'page') || ($post_type === 'teams')) {
  $redirect_url = get_field('vf_wp_intranet_redirect');
}

$loc_list = [];
if (!empty($training_location)) {
if (is_array($training_location) || is_object($training_location)) {
foreach( $training_location as $loc ) { 
  $loc_list[] = $loc->name; 
  }
}
}

$content_snippet = '';
if (!empty($search_terms) && function_exists('vfwp_search_get_highlighted_content_snippet')) {
  $content_snippet = vfwp_search_get_highlighted_content_snippet(get_post_field('post_content', $post->ID), $search_terms, 200);
}

$summary_text = '';
if ($has_indexed_search_result && !empty($vfwp_indexed_search_result['snippet_highlighted'])) {
  if (get_post_type() === 'training') {
    $content_snippet = $vfwp_indexed_search_result['snippet_highlighted'];
  } else {
    $summary_text = $vfwp_indexed_search_result['snippet_highlighted'];
  }
} elseif (get_post_type() !== 'training') {
  if ($content_snippet !== '') {
    $summary_text = $content_snippet;
  } elseif (has_excerpt()) {
    $excerpt = get_the_excerpt();
    $summary_text = !empty($search_terms) && function_exists('vfwp_search_highlight_text')
      ? vfwp_search_highlight_text($excerpt, $search_terms)
      : esc_html($excerpt);
  } else {
    $content = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags(get_the_content())));
    if ($content !== '') {
      if (function_exists('mb_strlen')) {
        if (mb_strlen($content, 'UTF-8') > 200) {
          $content = rtrim(mb_substr($content, 0, 200, 'UTF-8')) . '...';
        }
      } else {
        if (strlen($content) > 200) {
          $content = rtrim(substr($content, 0, 200)) . '...';
        }
      }
      $summary_text = !empty($search_terms) && function_exists('vfwp_search_highlight_text')
        ? vfwp_search_highlight_text($content, $search_terms)
        : esc_html($content);
    }
  }
}

$vfwp_result_type_labels = array(
  'page'           => __('Page', 'vfwp'),
  'teams'          => __('Team', 'vfwp'),
  'people'         => __('Person', 'vfwp'),
  'documents'      => __('Document', 'vfwp'),
  'community-blog' => __('Announcement', 'vfwp'),
  'insites'        => __('News', 'vfwp'),
  'events'         => __('Event', 'vfwp'),
  'vf_event'       => __('Event', 'vfwp'),
  'training'       => __('Training', 'vfwp'),
);

if (isset($vfwp_result_type_labels[$post_type])) {
  $vfwp_result_type_label = $vfwp_result_type_labels[$post_type];
} else {
  $post_type_object = get_post_type_object($post_type);
  $vfwp_result_type_label = $post_type_object && !empty($post_type_object->labels->singular_name)
    ? $post_type_object->labels->singular_name
    : $post_type;
}

$vfwp_show_result_type_badge = $post_type !== 'page' && $vfwp_result_type_label !== '';

if ($post_type === 'page') {
  $vfwp_breadcrumb_titles = array();
  $vfwp_ancestor_ids = array_reverse(get_post_ancestors($post));

  foreach ($vfwp_ancestor_ids as $vfwp_ancestor_id) {
    $vfwp_ancestor_title = trim(wp_strip_all_tags(get_the_title($vfwp_ancestor_id)));

    if ($vfwp_ancestor_title !== '') {
      $vfwp_breadcrumb_titles[] = $vfwp_ancestor_title;
    }
  }

  if (empty($vfwp_breadcrumb_titles)) {
    $vfwp_breadcrumb_titles[] = __('Home', 'vfwp');
  }

  $vfwp_result_meta_text = implode(' > ', $vfwp_breadcrumb_titles) . ' >';
}

$vfwp_is_search_result_context = is_search();

?>
<article class="vf-summary"<?php echo $vfwp_is_search_result_context ? '' : ' data-jplist-item'; ?>>

  <h2 class="vf-summary__title | search | search-counter" style="margin-bottom: 4px;">
    <a href="<?php echo esc_url($summary_link_url); ?>" class="vf-summary__link"><?php echo wp_kses($title, array('mark' => array())); ?></a>
    <?php if ($vfwp_show_result_type_badge) : ?>
      &nbsp;<span class="vf-badge vf-badge--tertiary vf-search-result__type-pill"><?php echo esc_html($vfwp_result_type_label); ?></span>
    <?php endif; ?>
  </h2>
  <?php if ($summary_text !== '') : ?>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php echo wp_kses($summary_text, array('mark' => array())); ?>
  </p>
  <?php endif; ?>

  <?php if ($post_type === 'training' && $content_snippet !== '') : ?>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php echo wp_kses($content_snippet, array('mark' => array())); ?>
  </p>
  <?php endif; ?>

  <?php if ($post_type === 'page' && $vfwp_result_meta_text !== '') : ?>
  <div class="vf-summary__meta">
    <p class="vf-summary__author | vf-u-margin__bottom--0">
      <span class="vf-search-result__breadcrumb"><?php echo esc_html($vfwp_result_meta_text); ?></span>
    </p>
  </div>
  <?php endif; ?>
  <?php if (!$vfwp_is_search_result_context) : ?>
    <?php
    if (($post_type == 'page') || ($post_type == 'teams'))  {
      echo '<p class="page vf-u-display-none | used-for-filtering">Page</p>';
    } elseif ( $post_type ==  'people') {
      echo '<p class="people vf-u-display-none | used-for-filtering">People</p>';
    } elseif ( $post_type ==  'documents') {
      echo '<p class="documents vf-u-display-none | used-for-filtering">Documents</p>';
    } elseif ( $post_type ==  'insites') {
      echo '<p class="news vf-u-display-none | used-for-filtering">News</p>';
    } elseif ( $post_type ==  'community-blog') {
      echo '<p class="announcements vf-u-display-none | used-for-filtering">Announcements</p>';
    } elseif ( $post_type ==  'events') {
      echo '<p class="events vf-u-display-none | used-for-filtering">Events</p>';
    } elseif ( $post_type ==  'training') {
      echo '<p class="training vf-u-display-none | used-for-filtering">Training</p>';
    }
    ?>
  <?php endif; ?>
  
</article>


<!--/vf-summary-->
