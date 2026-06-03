<?php

function vf_wp_ells_archive_browser_enqueue_assets() {
  if ( is_post_type_archive(array('learninglab', 'teachingbase', 'insight-lecture', 'ambassadors')) ) {
    wp_enqueue_script(
      'vf-wp-ells-archive-browser',
      get_stylesheet_directory_uri() . '/scripts/archive-browser.js',
      array(),
      wp_get_theme()->get('Version'),
      true
    );
  }
}
add_action('wp_enqueue_scripts', 'vf_wp_ells_archive_browser_enqueue_assets');

function vf_wp_ells_term_options($taxonomy) {
  $terms = get_terms(array(
    'taxonomy'   => $taxonomy,
    'hide_empty' => false,
  ));

  if ( is_wp_error($terms) ) {
    return array();
  }

  return array_map(function($term) {
    return array(
      'value' => $term->slug,
      'label' => $term->name,
    );
  }, $terms);
}

function vf_wp_ells_archive_year_options($post_type) {
  global $wpdb;

  $years = $wpdb->get_col($wpdb->prepare(
    "SELECT DISTINCT YEAR(post_date) FROM {$wpdb->posts} WHERE post_type = %s AND post_status = 'publish' ORDER BY post_date DESC",
    $post_type
  ));

  return array_map(function($year) {
    return array(
      'value' => (string) $year,
      'label' => (string) $year,
    );
  }, array_filter($years));
}

function vf_wp_ells_archive_language_options() {
  global $sitepress;

  $languages = array();

  if ( has_filter('wpml_active_languages') ) {
    $languages = apply_filters('wpml_active_languages', null, 'skip_missing=1&orderby=code');
  }

  if ( empty($languages) && function_exists('icl_get_languages') ) {
    $languages = icl_get_languages('skip_missing=1&orderby=code');
  }

  if ( empty($languages) && is_object($sitepress) && method_exists($sitepress, 'get_active_languages') ) {
    $languages = $sitepress->get_active_languages();
  }

  if ( empty($languages) || ! is_array($languages) ) {
    return array();
  }

  $options = array();
  foreach ($languages as $key => $language) {
    if ( ! is_array($language) ) {
      continue;
    }

    $code = '';
    if ( ! empty($language['language_code']) ) {
      $code = $language['language_code'];
    } elseif ( ! empty($language['code']) ) {
      $code = $language['code'];
    } elseif ( is_string($key) ) {
      $code = $key;
    }

    if ( ! $code ) {
      continue;
    }

    $options[$code] = array(
      'value' => $code,
      'label' => ! empty($language['native_name']) ? $language['native_name'] : (! empty($language['display_name']) ? $language['display_name'] : $code),
    );
  }

  return array_values($options);
}

function vf_wp_ells_is_wpml_active() {
  global $sitepress;

  return function_exists('icl_get_languages')
    || function_exists('wpml_get_language_information')
    || has_filter('wpml_active_languages')
    || is_object($sitepress);
}

function vf_wp_ells_language_code_from_details($language_info) {
  if ( is_array($language_info) ) {
    if ( ! empty($language_info['language_code']) ) {
      return $language_info['language_code'];
    }
    if ( ! empty($language_info['code']) ) {
      return $language_info['code'];
    }
  }

  if ( is_object($language_info) ) {
    if ( ! empty($language_info->language_code) ) {
      return $language_info->language_code;
    }
    if ( ! empty($language_info->code) ) {
      return $language_info->code;
    }
  }

  return '';
}

function vf_wp_ells_item_terms($post_id, $taxonomy, $acf_field = '') {
  $terms = get_the_terms($post_id, $taxonomy);

  if ( is_wp_error($terms) || empty($terms) ) {
    $terms = array();
  }

  if ( $acf_field && function_exists('get_field') ) {
    $field_terms = get_field($acf_field, $post_id);
    if ( ! empty($field_terms) ) {
      if ( ! is_array($field_terms) ) {
        $field_terms = array($field_terms);
      }
      $terms = array_merge($terms, $field_terms);
    }
  }

  $seen = array();
  $values = array();

  foreach ( $terms as $term ) {
    if ( is_numeric($term) ) {
      $term = get_term((int) $term, $taxonomy);
    }

    if ( is_wp_error($term) || ! is_object($term) || empty($term->slug) || isset($seen[$term->slug]) ) {
      continue;
    }
    $seen[$term->slug] = true;
    $values[] = $term->slug;
  }

  return $values;
}

function vf_wp_ells_render_summary_html($template, $archive_post) {
  global $post;

  $previous_post = $post;
  $post = $archive_post;
  setup_postdata($post);

  ob_start();
  include(locate_template($template, false, false));
  $html = ob_get_clean();

  $post = $previous_post;
  wp_reset_postdata();

  return $html;
}

function vf_wp_ells_archive_browser_items($args) {
  $query_args = array(
    'post_type'      => $args['post_type'],
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
  );

  if ( ! empty($args['query_args']) ) {
    $query_args = array_merge($query_args, $args['query_args']);
  }

  $query = new WP_Query($query_args);
  $items = array();

  foreach ( $query->posts as $archive_post ) {
    $filters = array();

    foreach ( $args['filters'] as $filter ) {
      $key = $filter['key'];

      if ( ! empty($filter['taxonomy']) ) {
        $filters[$key] = vf_wp_ells_item_terms(
          $archive_post->ID,
          $filter['taxonomy'],
          isset($filter['acf_field']) ? $filter['acf_field'] : ''
        );
        continue;
      }

      if ( $key === 'year' ) {
        $filters[$key] = array(get_the_date('Y', $archive_post));
        continue;
      }

      if ( $key === 'lang' ) {
        $language_code = '';
        if ( function_exists('wpml_get_language_information') ) {
          $language_info = wpml_get_language_information(null, $archive_post->ID);
          $language_code = vf_wp_ells_language_code_from_details($language_info);
        } elseif ( has_filter('wpml_post_language_details') ) {
          $language_info = apply_filters('wpml_post_language_details', null, $archive_post->ID);
          $language_code = vf_wp_ells_language_code_from_details($language_info);
        } elseif ( has_filter('wpml_element_language_details') ) {
          $details = apply_filters('wpml_element_language_details', null, array(
            'element_id'   => $archive_post->ID,
            'element_type' => 'post_' . $args['post_type'],
          ));
          $language_code = vf_wp_ells_language_code_from_details($details);
        }
        $filters[$key] = $language_code ? array($language_code) : array();
      }
    }

    $items[] = array(
      'id'    => $archive_post->ID,
      'title' => get_the_title($archive_post),
      'date'  => get_the_date('c', $archive_post),
      'html'  => vf_wp_ells_render_summary_html($args['summary_template'], $archive_post),
      'filters' => $filters,
    );
  }

  wp_reset_postdata();

  return $items;
}

?>
