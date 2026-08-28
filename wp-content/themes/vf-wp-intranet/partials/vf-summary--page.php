<?php

global $vfwp_indexed_search_result;

if (!function_exists('vfwp_intranet_search_summary_format_date')) {
  function vfwp_intranet_search_summary_format_date($raw_date) {
    if (!is_scalar($raw_date)) {
      return '';
    }

    $raw_date = trim((string) $raw_date);

    if ($raw_date === '') {
      return '';
    }

    $formats = array('Ymd', 'j M Y', 'j F Y', 'Y-m-d', 'd/m/Y');

    foreach ($formats as $format) {
      $date = DateTime::createFromFormat($format, $raw_date);

      if ($date instanceof DateTime) {
        return date_i18n(get_option('date_format'), $date->getTimestamp());
      }
    }

    $timestamp = strtotime($raw_date);

    return $timestamp ? date_i18n(get_option('date_format'), $timestamp) : '';
  }
}

if (!function_exists('vfwp_intranet_search_summary_term_names')) {
  function vfwp_intranet_search_summary_term_names($terms) {
    if (empty($terms) || is_wp_error($terms)) {
      return '';
    }

    $names = array();

    foreach ((array) $terms as $term) {
      if (is_object($term) && !empty($term->name)) {
        $names[] = $term->name;
      } elseif (is_array($term) && !empty($term['name'])) {
        $names[] = $term['name'];
      } elseif (is_scalar($term)) {
        $names[] = (string) $term;
      }
    }

    $names = array_unique(array_filter(array_map('trim', $names)));

    return implode(', ', $names);
  }
}

if (!function_exists('vfwp_intranet_search_summary_one_line')) {
  function vfwp_intranet_search_summary_one_line($text, $limit = 220) {
    if (!is_scalar($text)) {
      return '';
    }

    $text = html_entity_decode((string) $text, ENT_QUOTES, get_bloginfo('charset'));
    $text = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags($text)));

    if ($text === '') {
      return '';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr') && function_exists('mb_strrpos')) {
      if (mb_strlen($text, 'UTF-8') <= $limit) {
        return $text;
      }

      $limited = mb_substr($text, 0, $limit, 'UTF-8');
      $last_space = mb_strrpos($limited, ' ', 0, 'UTF-8');

      if ($last_space !== false) {
        $limited = mb_substr($limited, 0, $last_space, 'UTF-8');
      }

      return rtrim($limited) . '...';
    }

    if (strlen($text) <= $limit) {
      return $text;
    }

    $limited = substr($text, 0, $limit);
    $last_space = strrpos($limited, ' ');

    if ($last_space !== false) {
      $limited = substr($limited, 0, $last_space);
    }

    return rtrim($limited) . '...';
  }
}

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
$vfwp_result_detail_meta = '';
$vfwp_show_result_type_badge = false;
$position = '';
$outstation = '';
$email = '';
$people_primary_team = '';
$type = '';
$training_category = '';
$training_location = array();
$training_date = '';
$training_date_formatted = false;
$training_overview = '';
$teamArray = array();

if ($post_type === 'people') {
  $position = get_field('positions_name_1', $post->ID);
  $outstation = get_field('outstation', $post->ID);
  $email = get_field('email', $post->ID);
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

  if (!empty($teamArray)) {
    $people_primary_team_data = reset($teamArray);
    $people_primary_team = !empty($people_primary_team_data['team']) ? $people_primary_team_data['team'] : '';
  }
} elseif ($post_type === 'training') {
  $type = get_field('vf-wp-training-training_type', $post->ID);
  $training_date = get_field('vf-wp-training-start_date', $post->ID);
  $training_date_formatted = vfwp_intranet_search_summary_format_date($training_date);
  $training_category = get_field('vf-wp-training-category', $post->ID);
  $training_location = get_the_terms( $post->ID , 'event-location' );
  $training_overview = vfwp_intranet_search_summary_one_line(get_field('vf-wp-training-info', $post->ID, false, false));
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

if (is_search() && $post_type === 'people') {
  $people_summary_parts = array();

  foreach (array($position, $people_primary_team, $outstation) as $people_summary_part) {
    if (!is_scalar($people_summary_part)) {
      continue;
    }

    $people_summary_part = trim((string) $people_summary_part);

    if ($people_summary_part !== '') {
      $people_summary_parts[] = esc_html($people_summary_part);
    }
  }

  if (is_scalar($email)) {
    $email = trim((string) $email);

    if ($email !== '' && is_email($email)) {
      $people_summary_parts[] = '<a class="vf-link" href="' . esc_url('mailto:' . $email) . '">' . esc_html__('Email', 'vfwp') . '</a>';
    }
  }

  if (!empty($people_summary_parts)) {
    $summary_text = implode(' | ', $people_summary_parts);
  }
}

if (is_search() && $post_type === 'documents' && $has_indexed_search_result) {
  $document_signals = isset($vfwp_indexed_search_result['signals']) && is_array($vfwp_indexed_search_result['signals'])
    ? $vfwp_indexed_search_result['signals']
    : array();
  $document_term_count = isset($document_signals['term_count']) ? max(1, (int) $document_signals['term_count']) : 1;
  $document_pdf_match = !empty($document_signals['content_phrase_hit'])
    || ($document_term_count === 1 && !empty($document_signals['content_term_hits']));
  $document_title_match = !empty($document_signals['exact_title_match'])
    || !empty($document_signals['title_phrase_hit'])
    || !empty($document_signals['title_term_hits']);

  if ($document_pdf_match) {
    $summary_text = !empty($vfwp_indexed_search_result['content_snippet_highlighted'])
      ? $vfwp_indexed_search_result['content_snippet_highlighted']
      : '';
  } elseif ($document_title_match) {
    $document_excerpt = isset($vfwp_indexed_search_result['snippet_source']['excerpt'])
      ? trim((string) $vfwp_indexed_search_result['snippet_source']['excerpt'])
      : '';

    if ($document_excerpt !== '') {
      $document_excerpt_highlighted = !empty($vfwp_indexed_search_result['excerpt_highlighted'])
        ? $vfwp_indexed_search_result['excerpt_highlighted']
        : esc_html($document_excerpt);
      $summary_text = $title . ' | ' . $document_excerpt_highlighted;
    } else {
      $summary_text = '';
    }
  } else {
    $summary_text = '';
  }
}

if (is_search()) {
  if ($post_type === 'community-blog' || $post_type === 'insites') {
    $vfwp_result_detail_meta = get_the_date(get_option('date_format'), $post);
  } elseif ($post_type === 'events' || $post_type === 'vf_event') {
    $vfwp_result_detail_meta = vfwp_intranet_search_summary_format_date(get_field('vf_event_internal_start_date', $post->ID));
  } elseif ($post_type === 'documents') {
    $document_updated_date = function_exists('get_field') ? vfwp_intranet_search_summary_format_date(get_field('latest_update', $post->ID)) : '';
    $vfwp_result_detail_meta = $document_updated_date !== ''
      ? $document_updated_date
      : get_the_date(get_option('date_format'), $post);
  } elseif ($post_type === 'training') {
    if ($training_overview !== '') {
      $summary_text = esc_html($training_overview);
      $content_snippet = '';
    }

    $is_on_demand_training = is_scalar($type) && in_array(strtolower(str_replace(array('_', '-'), ' ', trim((string) $type))), array('on demand', 'on-demand', 'ondemand'), true);
    $training_meta_parts = array();

    if ($is_on_demand_training) {
      $training_meta_parts[] = __('On-demand', 'vfwp');
    }

    if (is_scalar($training_category) && trim((string) $training_category) !== '') {
      $training_meta_parts[] = trim((string) $training_category);
    }

    if (!$is_on_demand_training) {
      $training_location_names = vfwp_intranet_search_summary_term_names($training_location);

      if ($training_location_names !== '') {
        $training_meta_parts[] = $training_location_names;
      }

      if ($training_date_formatted !== '') {
        $training_meta_parts[] = $training_date_formatted;
      }
    }

    $vfwp_result_detail_meta = implode(' | ', array_unique(array_filter($training_meta_parts)));
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
    <?php echo wp_kses($summary_text, array('mark' => array(), 'a' => array('class' => array(), 'href' => array()))); ?>
  </p>
  <?php endif; ?>

  <?php if ($post_type === 'training' && $content_snippet !== '') : ?>
  <p class="vf-summary__meta" style="margin-bottom: 8px;">
    <?php echo wp_kses($content_snippet, array('mark' => array())); ?>
  </p>
  <?php endif; ?>

  <?php if ($vfwp_result_detail_meta !== '') : ?>
  <div class="vf-summary__meta">
    <p class="vf-summary__author | vf-u-margin__bottom--0">
      <span class="vf-search-result__breadcrumb"><?php echo esc_html($vfwp_result_detail_meta); ?></span>
    </p>
  </div>
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
