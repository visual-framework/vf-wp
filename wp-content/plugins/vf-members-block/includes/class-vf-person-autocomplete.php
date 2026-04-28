<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'VF_Person_Autocomplete' ) ) :

class VF_Person_Autocomplete {

  const NONCE_ACTION = 'vf_person_autocomplete';

  private static $instance = null;

  public static function boot() {
    if ( null === self::$instance ) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
    add_action(
      'acf/input/admin_enqueue_scripts',
      array( $this, 'enqueue_assets' )
    );
    add_filter(
      'acf/load_field/key=field_5ea93bf243754',
      array( $this, 'prepare_public_field' )
    );
    add_filter(
      'acf/load_field/key=field_618a4d446abf3',
      array( $this, 'prepare_internal_field' )
    );
    add_filter(
      'acf/prepare_field/key=field_5ea93bf243754',
      array( $this, 'prepare_public_field' )
    );
    add_filter(
      'acf/prepare_field/key=field_618a4d446abf3',
      array( $this, 'prepare_internal_field' )
    );
    add_action(
      'wp_ajax_vf_person_autocomplete',
      array( $this, 'handle_ajax' )
    );
  }

  public function enqueue_assets() {
    $handle = 'vf-person-autocomplete';
    $src    = plugins_url( '../assets/vf-person-autocomplete.js', __FILE__ );
    $path   = dirname( __DIR__ ) . '/assets/vf-person-autocomplete.js';

    wp_register_script(
      $handle,
      $src,
      array( 'jquery', 'acf-input' ),
      file_exists( $path ) ? (string) filemtime( $path ) : false,
      true
    );

    wp_localize_script(
      $handle,
      'vfPersonAutocomplete',
      array(
        'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
        'action'    => 'vf_person_autocomplete',
        'nonce'     => wp_create_nonce( self::NONCE_ACTION ),
        'minLength' => 2,
        'limit'     => 8,
      )
    );

    wp_enqueue_script( $handle );
  }

  public function handle_ajax() {
    if ( ! current_user_can( 'edit_posts' ) ) {
      wp_send_json_error( array( 'message' => __( 'Unauthorized', 'vfwp' ) ), 403 );
    }

    check_ajax_referer( self::NONCE_ACTION, 'nonce' );

    $query = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
    $scope = isset( $_GET['scope'] ) ? sanitize_key( wp_unslash( $_GET['scope'] ) ) : 'public';
    $limit = isset( $_GET['limit'] ) ? absint( $_GET['limit'] ) : 8;

    if ( $limit < 1 ) {
      $limit = 8;
    } elseif ( $limit > 20 ) {
      $limit = 20;
    }

    if ( strlen( $query ) < 2 ) {
      wp_send_json_success( array( 'results' => array() ) );
    }

    wp_send_json_success(
      array(
        'results' => $this->get_suggestions( $query, $scope, $limit ),
      )
    );
  }

  public function prepare_public_field( $field ) {
    return $this->prepare_field( $field, 'public' );
  }

  public function prepare_internal_field( $field ) {
    return $this->prepare_field( $field, 'internal' );
  }

  private function prepare_field( $field, $scope ) {
    $field['type']        = 'select';
    $field['ui']          = 0;
    $field['ajax']        = 0;
    $field['allow_null']  = 1;
    $field['multiple']    = 0;
    $field['choices']     = array();
    $field['placeholder'] = __( 'Search for a person', 'vfwp' );
    $field['wrapper']['class'] = sprintf(
      'vf-person-record-select vf-person-scope-%s',
      sanitize_html_class( $scope )
    );

    return $this->inject_current_choice( $field );
  }

  private function inject_current_choice( $field ) {
    $value = isset( $field['value'] ) && is_scalar( $field['value'] )
      ? trim( (string) $field['value'] )
      : '';

    if ( '' !== $value ) {
      $field['choices'][ $value ] = $value;
    }

    return $field;
  }

  private function get_suggestions( $query, $scope, $limit ) {
    $scope = 'internal' === $scope ? 'internal' : 'public';

    $cache_key = sprintf(
      'vf_person_autocomplete_%s',
      md5( strtolower( $scope . '|' . $query . '|' . $limit ) )
    );

    $cached = get_transient( $cache_key );
    if ( is_array( $cached ) ) {
      return $cached;
    }

    $suggestions = $this->fetch_json_suggestions( $query, $scope, $limit );

    if ( empty( $suggestions ) ) {
      $suggestions = $this->fetch_html_suggestions( $query, $scope, $limit );
    }

    $suggestions = array_slice(
      $this->deduplicate_records( $suggestions ),
      0,
      $limit
    );

    set_transient( $cache_key, $suggestions, 5 * MINUTE_IN_SECONDS );

    return $suggestions;
  }

  private function build_query_args( $query, $scope, $limit ) {
    $args = array(
      'source'                                          => 'contenthub',
      'pattern'                                         => 'internal' === $scope ? 'vf-profile-inline-internal' : 'vf-profile-inline',
      'filter-content-type'                             => 'person',
      'limit'                                           => $limit,
      'sort-field-value[field_person_full_name]'        => 'ASC',
      'filter-field-contains[field_person_full_name]'   => $query,
      'filter-ref-entity[field_person_positions][title]' => '',
      'filter-ref-entity[field_person_positions][field_position_primary]' => 1,
    );

    if ( 'public' === $scope ) {
      $args['filter-fields-empty'] = 'field_person_visible_internally';
    }

    return $args;
  }

  private function fetch_json_suggestions( $query, $scope, $limit ) {
    if ( ! class_exists( 'VF_Cache' ) ) {
      return array();
    }

    $base_url = VF_Cache::get_api_url();
    if ( empty( $base_url ) ) {
      return array();
    }

    $url = add_query_arg(
      $this->build_query_args( $query, $scope, $limit ),
      trailingslashit( $base_url ) . 'pattern.json'
    );

    $response = wp_remote_get(
      esc_url_raw( $url ),
      array(
        'timeout' => 3,
        'headers' => array(
          'Accept' => 'application/json',
        ),
      )
    );

    if ( is_wp_error( $response ) ) {
      return array();
    }

    $body = wp_remote_retrieve_body( $response );
    if ( empty( $body ) ) {
      return array();
    }

    $decoded = json_decode( $body, true );
    if ( ! is_array( $decoded ) ) {
      return array();
    }

    $results = array();
    $this->extract_records_from_json( $decoded, $results );

    return $results;
  }

  private function extract_records_from_json( $data, array &$results ) {
    if ( is_array( $data ) ) {
      $record = $this->normalize_record( $data );
      if ( ! empty( $record ) ) {
        $results[] = $record;
      }

      foreach ( $data as $key => $value ) {
        if ( is_array( $value ) || is_object( $value ) ) {
          $this->extract_records_from_json( $value, $results );
        }
      }
    } elseif ( is_object( $data ) ) {
      $this->extract_records_from_json( (array) $data, $results );
    }
  }

  private function fetch_html_suggestions( $query, $scope, $limit ) {
    if ( ! class_exists( 'VF_Cache' ) ) {
      return array();
    }

    $base_url = VF_Cache::get_api_url();
    if ( empty( $base_url ) ) {
      return array();
    }

    $url = add_query_arg(
      $this->build_query_args( $query, $scope, $limit ),
      trailingslashit( $base_url ) . 'pattern.html'
    );

    $html = VF_Cache::fetch_remote( esc_url_raw( $url ) );
    if ( ! is_string( $html ) || empty( $html ) || is_numeric( $html ) ) {
      return array();
    }

    $results = array();
    $patterns = array(
      '/<h3[^>]*class="[^"]*vf-profile__title[^"]*"[^>]*>(.*?)<\/h3>/is',
      '/<h3[^>]*class="[^"]*vf-summary__title[^"]*"[^>]*>(.*?)<\/h3>/is',
    );

    foreach ( $patterns as $pattern ) {
      if ( ! preg_match_all( $pattern, $html, $matches ) ) {
        continue;
      }

      foreach ( $matches[1] as $match ) {
        $record = $this->normalize_record(
          array(
            'field_person_full_name' => wp_strip_all_tags( $match ),
          )
        );

        if ( ! empty( $record ) ) {
          $results[] = $record;
        }
      }

      if ( ! empty( $results ) ) {
        break;
      }
    }

    return $results;
  }

  private function normalize_record( $data ) {
    if ( ! is_array( $data ) ) {
      return null;
    }

    $name = '';
    $id   = '';

    foreach ( array( 'field_person_full_name', 'full_name', 'profile__name' ) as $key ) {
      if ( isset( $data[ $key ] ) && is_scalar( $data[ $key ] ) ) {
        $name = trim( preg_replace( '/\s+/', ' ', (string) $data[ $key ] ) );
        if ( '' !== $name ) {
          break;
        }
      }
    }

    foreach ( array( 'field_person_embl_id', 'embl_id', 'profile__uuid' ) as $key ) {
      if ( isset( $data[ $key ] ) && is_scalar( $data[ $key ] ) ) {
        $id = trim( preg_replace( '/\s+/', ' ', (string) $data[ $key ] ) );
        if ( '' !== $id ) {
          break;
        }
      }
    }

    if ( '' === $name || preg_match( '/^CP-\d+$/', $name ) ) {
      return null;
    }

    return array(
      'id'   => $name,
      'text' => '' !== $id ? sprintf( '%s (%s)', $name, $id ) : $name,
    );
  }

  private function deduplicate_records( $records ) {
    $deduplicated = array();

    foreach ( $records as $record ) {
      if ( empty( $record['id'] ) || empty( $record['text'] ) ) {
        continue;
      }

      $deduplicated[ $record['id'] ] = array(
        'id'   => (string) $record['id'],
        'text' => (string) $record['text'],
      );
    }

    return array_values( $deduplicated );
  }
}

endif;
