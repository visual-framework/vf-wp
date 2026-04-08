<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! function_exists( 'vfwp_latest_posts_normalize_term_ids' ) ) {
  function vfwp_latest_posts_normalize_term_ids( $value ) {
    if ( empty( $value ) && ! is_numeric( $value ) ) {
      return array();
    }

    if ( ! is_array( $value ) ) {
      $value = array( $value );
    }

    $term_ids = array();

    foreach ( $value as $item ) {
      if ( is_object( $item ) && isset( $item->term_id ) ) {
        $item = $item->term_id;
      } elseif ( is_array( $item ) && isset( $item['term_id'] ) ) {
        $item = $item['term_id'];
      }

      $term_id = absint( $item );

      if ( $term_id > 0 ) {
        $term_ids[] = $term_id;
      }
    }

    return array_values( array_unique( $term_ids ) );
  }
}

if ( ! function_exists( 'vfwp_latest_posts_parse_date' ) ) {
  function vfwp_latest_posts_parse_date( $value ) {
    if ( $value instanceof DateTimeInterface ) {
      return DateTimeImmutable::createFromInterface( $value );
    }

    if ( ! is_scalar( $value ) ) {
      return null;
    }

    $value = trim( (string) $value );

    if ( '' === $value ) {
      return null;
    }

    $formats = array( 'Ymd', 'Y-m-d', 'j M Y', 'j F Y', 'd/m/Y', 'm/d/Y' );

    foreach ( $formats as $format ) {
      $date = DateTimeImmutable::createFromFormat( '!' . $format, $value );
      $errors = DateTimeImmutable::getLastErrors();

      if ( $date && ( false === $errors || ( 0 === $errors['warning_count'] && 0 === $errors['error_count'] ) ) ) {
        return $date;
      }
    }

    $timestamp = strtotime( $value );

    if ( false === $timestamp ) {
      return null;
    }

    return ( new DateTimeImmutable() )->setTimestamp( $timestamp );
  }
}

if ( ! function_exists( 'vfwp_latest_posts_parse_time' ) ) {
  function vfwp_latest_posts_parse_time( $value ) {
    if ( ! is_scalar( $value ) ) {
      return null;
    }

    $value = trim( (string) $value );

    if ( '' === $value ) {
      return null;
    }

    $formats = array( 'H:i', 'H:i:s', 'Hi' );

    foreach ( $formats as $format ) {
      $time = DateTimeImmutable::createFromFormat( '!' . $format, $value );
      $errors = DateTimeImmutable::getLastErrors();

      if ( $time && ( false === $errors || ( 0 === $errors['warning_count'] && 0 === $errors['error_count'] ) ) ) {
        return $time;
      }
    }

    return null;
  }
}

if ( ! function_exists( 'vfwp_latest_posts_build_tax_query' ) ) {
  function vfwp_latest_posts_build_tax_query( $clauses, $relation = 'AND' ) {
    $clauses = array_values(
      array_filter(
        $clauses,
        static function( $clause ) {
          return ! empty( $clause );
        }
      )
    );

    if ( empty( $clauses ) ) {
      return array();
    }

    if ( 1 === count( $clauses ) ) {
      return $clauses;
    }

    return array_merge( array( 'relation' => $relation ), $clauses );
  }
}

if ( ! function_exists( 'vfwp_latest_posts_build_event_meta_query' ) ) {
  function vfwp_latest_posts_build_event_meta_query( $current_date ) {
    return array(
      'relation' => 'OR',
      array(
        'key'     => 'vf_event_internal_start_date',
        'value'   => $current_date,
        'compare' => '>=',
        'type'    => 'NUMERIC',
      ),
      array(
        'key'     => 'vf_event_internal_end_date',
        'value'   => $current_date,
        'compare' => '>=',
        'type'    => 'NUMERIC',
      ),
    );
  }
}

if ( ! function_exists( 'vfwp_latest_posts_build_query_args' ) ) {
  function vfwp_latest_posts_build_query_args( $settings ) {
    $limit          = ! empty( $settings['limit'] ) ? absint( $settings['limit'] ) : 3;
    $post_type      = ! empty( $settings['post_type'] ) ? sanitize_key( $settings['post_type'] ) : 'post';
    $category       = vfwp_latest_posts_normalize_term_ids( $settings['category'] ?? array() );
    $tag            = vfwp_latest_posts_normalize_term_ids( $settings['tag'] ?? array() );
    $topic          = vfwp_latest_posts_normalize_term_ids( $settings['topic'] ?? array() );
    $topic_updates  = vfwp_latest_posts_normalize_term_ids( $settings['topic_updates'] ?? array() );
    $topic_events   = vfwp_latest_posts_normalize_term_ids( $settings['topic_events'] ?? array() );
    $location       = vfwp_latest_posts_normalize_term_ids( $settings['location'] ?? array() );
    $event_location = vfwp_latest_posts_normalize_term_ids( $settings['event_location'] ?? array() );
    $current_date   = ! empty( $settings['current_date'] ) ? preg_replace( '/[^0-9]/', '', (string) $settings['current_date'] ) : current_time( 'Ymd' );

    $args = array(
      'posts_per_page'      => $limit,
      'post_status'         => 'publish',
      'no_found_rows'       => true,
      'ignore_sticky_posts' => true,
    );

    if ( ! empty( $tag ) ) {
      $args['tag__in'] = $tag;
    }

    switch ( $post_type ) {
      case 'insites':
        $args['post_type'] = 'insites';
        $tax_query = vfwp_latest_posts_build_tax_query(
          array(
            ! empty( $location ) ? array(
              'taxonomy' => 'embl-location',
              'field'    => 'term_id',
              'terms'    => $location,
            ) : null,
            ! empty( $topic ) ? array(
              'taxonomy' => 'topic',
              'field'    => 'term_id',
              'terms'    => $topic,
            ) : null,
          )
        );
        if ( ! empty( $tax_query ) ) {
          $args['tax_query'] = $tax_query;
        }
        break;

      case 'community-blog':
        $args['post_type'] = 'community-blog';
        $tax_query = vfwp_latest_posts_build_tax_query(
          array(
            ! empty( $location ) ? array(
              'taxonomy' => 'embl-location',
              'field'    => 'term_id',
              'terms'    => $location,
            ) : null,
            ! empty( $topic_updates ) ? array(
              'taxonomy' => 'updates-topic',
              'field'    => 'term_id',
              'terms'    => $topic_updates,
            ) : null,
          )
        );
        if ( ! empty( $tax_query ) ) {
          $args['tax_query'] = $tax_query;
        }
        break;

      case 'events':
        $args['post_type']  = 'events';
        $args['order']      = 'ASC';
        $args['orderby']    = 'meta_value_num';
        $args['meta_key']   = 'vf_event_internal_start_date';
        $args['meta_query'] = vfwp_latest_posts_build_event_meta_query( $current_date );
        $tax_query = vfwp_latest_posts_build_tax_query(
          array(
            ! empty( $event_location ) ? array(
              'taxonomy' => 'event-location',
              'field'    => 'term_id',
              'terms'    => $event_location,
            ) : null,
            ! empty( $topic_events ) ? array(
              'taxonomy' => 'events-topic',
              'field'    => 'term_id',
              'terms'    => $topic_events,
            ) : null,
          )
        );
        if ( ! empty( $tax_query ) ) {
          $args['tax_query'] = $tax_query;
        }
        break;

      case 'both':
        $args['post_type'] = array( 'community-blog', 'insites' );
        $topic_clauses = array();

        if ( ! empty( $topic_updates ) ) {
          $topic_clauses[] = array(
            'taxonomy' => 'updates-topic',
            'field'    => 'term_id',
            'terms'    => $topic_updates,
          );
        }

        if ( ! empty( $topic ) ) {
          $topic_clauses[] = array(
            'taxonomy' => 'topic',
            'field'    => 'term_id',
            'terms'    => $topic,
          );
        }

        $tax_query = array();

        if ( ! empty( $topic_clauses ) ) {
          $tax_query[] = 1 === count( $topic_clauses )
            ? $topic_clauses[0]
            : array_merge( array( 'relation' => 'OR' ), $topic_clauses );
        }

        if ( ! empty( $location ) ) {
          $tax_query[] = array(
            'taxonomy' => 'embl-location',
            'field'    => 'term_id',
            'terms'    => $location,
          );
        }

        $tax_query = vfwp_latest_posts_build_tax_query( $tax_query );
        if ( ! empty( $tax_query ) ) {
          $args['tax_query'] = $tax_query;
        }
        break;

      case 'post':
      default:
        $args['post_type'] = 'post';
        if ( ! empty( $category ) ) {
          $args['category__in'] = $category;
        }
        break;
    }

    return $args;
  }
}

if ( ! function_exists( 'vfwp_latest_posts_get_event_display_data' ) ) {
  function vfwp_latest_posts_get_event_display_data( $post_id ) {
    $start_date = vfwp_latest_posts_parse_date( get_field( 'vf_event_internal_start_date', $post_id, false ) );
    $end_date   = vfwp_latest_posts_parse_date( get_field( 'vf_event_internal_end_date', $post_id, false ) );
    $start_time = vfwp_latest_posts_parse_time( get_field( 'vf_event_internal_start_time', $post_id, false ) );
    $end_time   = vfwp_latest_posts_parse_time( get_field( 'vf_event_internal_end_time', $post_id, false ) );

    $data = array(
      'has_date'     => false,
      'sort_key'     => '',
      'display_date' => '',
      'calendar_url' => '',
    );

    if ( ! $start_date ) {
      return $data;
    }

    if ( $end_date && $end_date < $start_date ) {
      $end_date = $start_date;
    }

    $data['has_date'] = true;
    $data['sort_key'] = $start_date->format( 'Ymd' );

    if ( $end_date ) {
      if ( $start_date->format( 'MY' ) === $end_date->format( 'MY' ) ) {
        $data['display_date'] = $start_date->format( 'j' ) . ' - ' . $end_date->format( 'j F Y' );
      } else {
        $data['display_date'] = $start_date->format( 'j M' ) . ' - ' . $end_date->format( 'j F Y' );
      }
    } else {
      $data['display_date'] = $start_date->format( 'j F Y' );
    }

    $calendar_start = $start_date->format( 'Ymd' );
    if ( $start_time ) {
      $calendar_start .= 'T' . $start_time->format( 'His' );
    }

    $calendar_end_date = $end_date ? $end_date : $start_date;
    $calendar_end_time = $end_time ? $end_time : $start_time;
    $calendar_end      = $calendar_end_date->format( 'Ymd' );

    if ( $calendar_end_time ) {
      $calendar_end .= 'T' . $calendar_end_time->format( 'His' );
    }

    $data['calendar_url'] = add_query_arg(
      array(
        'action' => 'TEMPLATE',
        'text'   => get_the_title( $post_id ),
        'dates'  => $calendar_start . '/' . $calendar_end,
        'sprop'  => 'name:',
      ),
      'https://www.google.com/calendar/render'
    );

    return $data;
  }
}

if ( ! function_exists( 'vfwp_latest_posts_get_term_links' ) ) {
  function vfwp_latest_posts_get_term_links( $post_id, $taxonomy ) {
    $terms = get_the_terms( $post_id, $taxonomy );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
      return '';
    }

    $links = array();

    foreach ( $terms as $term ) {
      $term_link = get_term_link( $term );

      if ( is_wp_error( $term_link ) ) {
        continue;
      }

      $links[] = sprintf(
        '<a class="vf-link %1$s" style="color: #707372;" href="%2$s">%3$s</a>',
        esc_attr( $term->slug ),
        esc_url( $term_link ),
        esc_html( strtoupper( $term->name ) )
      );
    }

    return implode( ', ', $links );
  }
}

if ( ! function_exists( 'vfwp_latest_posts_get_location_label' ) ) {
  function vfwp_latest_posts_get_location_label( $location_name ) {
    $map = array(
      'Heidelberg' => 'HD',
      'Hamburg'    => 'HH',
      'Rome'       => 'RM',
      'Grenoble'   => 'GR',
      'Barcelona'  => 'BCN',
      'EMBL-EBI'   => 'EBI',
    );

    return $map[ $location_name ] ?? $location_name;
  }
}

if ( ! function_exists( 'vfwp_latest_posts_get_term_names' ) ) {
  function vfwp_latest_posts_get_term_names( $post_id, $taxonomy, $map_location_names = false ) {
    $terms = get_the_terms( $post_id, $taxonomy );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
      return array();
    }

    $names = array();

    foreach ( $terms as $term ) {
      $names[] = $map_location_names
        ? vfwp_latest_posts_get_location_label( $term->name )
        : $term->name;
    }

    return array_values( array_unique( array_filter( $names ) ) );
  }
}

if ( ! function_exists( 'vfwp_latest_posts_is_intranet_theme' ) ) {
  function vfwp_latest_posts_is_intranet_theme() {
    $theme = wp_get_theme();

    $names = array_filter(
      array(
        isset( $theme->name ) ? (string) $theme->name : '',
        isset( $theme->parent_theme ) ? (string) $theme->parent_theme : '',
        (string) get_stylesheet(),
        (string) get_template(),
      )
    );

    foreach ( $names as $name ) {
      if ( 'VF-WP Intranet' === $name || 'vf-wp-intranet' === $name ) {
        return true;
      }
    }

    return false;
  }
}

if ( ! function_exists( 'vfwp_latest_posts_get_default_heading_link' ) ) {
  function vfwp_latest_posts_get_default_heading_link( $post_type ) {
    $archive_post_type = 'post';

    switch ( $post_type ) {
      case 'insites':
        $archive_post_type = 'insites';
        break;

      case 'community-blog':
      case 'both':
        $archive_post_type = 'community-blog';
        break;

      case 'events':
        $archive_post_type = 'events';
        break;
    }

    if ( 'post' === $archive_post_type ) {
      $posts_page_id = (int) get_option( 'page_for_posts' );

      if ( $posts_page_id > 0 ) {
        $posts_page_link = get_permalink( $posts_page_id );

        if ( $posts_page_link ) {
          return $posts_page_link;
        }
      }

      return home_url( '/' );
    }

    $archive_link = get_post_type_archive_link( $archive_post_type );

    if ( $archive_link ) {
      return $archive_link;
    }

    if ( ! vfwp_latest_posts_is_intranet_theme() ) {
      return home_url( '/' );
    }

    $fallbacks = array(
      'insites'        => '/news',
      'community-blog' => '/updates',
      'events'         => '/events',
    );

    return home_url( $fallbacks[ $archive_post_type ] ?? '/' );
  }
}
