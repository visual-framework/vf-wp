<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('EMBL_Taxonomy_Register') ) :


class EMBL_Taxonomy_Register {

  // Maximum number of terms to sync per API request
  // Set as high as possible but reduce if requests timeout
  const SYNC_MAX_TERMS = 500;

  // Options table keys
  const OPTION_MODIFIED = EMBL_Taxonomy::TAXONOMY_NAME . '_modified';

  // https://api.drupal.org/api/drupal/core!lib!Drupal!Component!Uuid!Uuid.php/constant/Uuid%3A%3AVALID_PATTERN/
  const UUID_PATTERN = '#^[0-9a-f]{8}-([0-9a-f]{4}-){3}[0-9a-f]{12}$#';

  protected $labels;

  public function __construct() {

    $this->labels = array(
      'name'              => __('EMBL Taxonomy', 'embl'),
      'singular_name'     => __('EMBL Taxonomy Term', 'embl'),
      'search_items'      => __('Search EMBL Taxonomy', 'embl'),
      'all_items'         => __('All EMBL Taxonomy Terms', 'embl'),
      'add_new_item'      => __('Add New EMBL Taxonomy Term', 'embl')
    );

    add_action('init', array($this, 'register_taxonomy'));
    add_action('rest_api_init', array($this, 'register_rest_api'));

    add_filter('pre_insert_term', array($this, 'filter_pre_insert_term'), 1, 2);
    add_filter(EMBL_Taxonomy::TAXONOMY_NAME . '_name', array($this, 'filter_term_name'), 10, 3);

    if (is_admin()) {
      add_action('admin_notices', array($this, 'action_admin_notices'));
      add_action('admin_enqueue_scripts', array($this, 'action_admin_enqueue'));
      
    }

    // Add columns for term meta
    add_filter('manage_edit-embl_taxonomy_columns', array($this, 'embl_taxonomy_columns'));
    add_filter('manage_embl_taxonomy_custom_column', array($this, 'embl_taxonomy_column_content'), 10, 3);

    $this->set_read_only();
  }

  public function embl_taxonomy_columns($columns) {
    $columns['embl_taxonomy_term_uuid'] = __('EMBL Term UUID', 'embl');
    $columns['embl_taxonomy_meta_ids'] = __('Meta IDs', 'embl');
    $columns['embl_taxonomy_meta_name'] = __('Meta Name', 'embl');
    $columns['embl_taxonomy_meta_deprecated'] = __('Meta Deprecated', 'embl');
    return $columns;
  }

  public function embl_taxonomy_column_content($content, $column_name, $term_id) {
    $term = get_term($term_id, 'embl_taxonomy');
    $full_term = embl_taxonomy_get_term($term->term_id);

    if ($column_name === 'embl_taxonomy_term_uuid') {
      $content = '<code>' . end($full_term->meta['embl_taxonomy_ids']) . '</code>';
    } elseif ($column_name === 'embl_taxonomy_meta_ids') {
      $meta_ids = get_term_meta($term_id, EMBL_Taxonomy::META_IDS, true);

      if (is_array($meta_ids)) {
        $content = '<code>' . json_encode($meta_ids) . '</code>';
    } else {
        $content = '<code>' . $meta_ids . '</code>';
    }

    } elseif ($column_name === 'embl_taxonomy_meta_name') {
      $content = '<code>' . get_term_meta($term_id, EMBL_Taxonomy::META_NAME, true) . '</code>';
    } elseif ($column_name === 'embl_taxonomy_meta_deprecated') {
      $content = '<code>' . get_term_meta($term_id, EMBL_Taxonomy::META_DEPRECATED, true) . '</code>';
    }

    return $content;
  }

  public function is_read_only() {
    $name = EMBL_Taxonomy::TAXONOMY_NAME;
    return get_option("vf__{$name}_locked", true);
  }

  private function set_read_only($locked = true) {
    $name = EMBL_Taxonomy::TAXONOMY_NAME;
    return update_option("vf__{$name}_locked", boolval($locked));
  }


  /**
   * Action `init`
   * https://codex.wordpress.org/Function_Reference/register_taxonomy
   */
  public function register_taxonomy() {
    /**
     * Register the EMBL Taxnonomy
     * Because the taxonomy is auto-synced with the contentHub API the
     * "edit" and "delete" capabilities are not assigned to any user
     */
    register_taxonomy(
      EMBL_Taxonomy::TAXONOMY_NAME,
      EMBL_Taxonomy::TAXONOMY_TYPES,
      array(
        'labels'            => $this->labels,
        'hierarchical'      => false,
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'capabilities' => array(
    'manage_terms' => 'manage_options',   // Restricting to administrators
    'edit_terms'   => 'manage_options',   
    'delete_terms' => 'manage_options',   
    'assign_terms' => 'manage_options'    
)
      )
    );

    register_term_meta(
      EMBL_Taxonomy::TAXONOMY_NAME,
      EMBL_Taxonomy::META_IDS,
      array('type' => 'string')
    );

    register_term_meta(
      EMBL_Taxonomy::TAXONOMY_NAME,
      EMBL_Taxonomy::META_NAME,
      array('type' => 'string')
    );

    register_term_meta(
      EMBL_Taxonomy::TAXONOMY_NAME,
      EMBL_Taxonomy::META_DEPRECATED,
      array('type' => 'integer')
    );
  }

  /**
   * Hook for `rest_api_init`
   */
  public function register_rest_api() {
    // Using `POST` because GET requests are easier to accidentally invoke
    // API route will be called multiple times with the `offset` parameter
    register_rest_route(EMBL_Taxonomy::TAXONOMY_NAME . '/v1', '/sync/', array(
      'methods' => 'POST',
      'callback' => array($this, 'sync_taxonomy'),
      'permission_callback' => function () {
          return current_user_can('manage_categories');
      }
  ));

  // Delete deprecated terms route
  register_rest_route(EMBL_Taxonomy::TAXONOMY_NAME . '/v1', '/delete-deprecated/', array(
      'methods' => 'POST',
      'callback' => array($this, 'delete_deprecated_terms'),
      'permission_callback' => function() {
          return current_user_can('manage_categories');
      }
  ));


    // Add taxonomy terms assigned to posts as field for WP REST API
    register_rest_field(
      EMBL_Taxonomy::TAXONOMY_TYPES,
      EMBL_Taxonomy::TAXONOMY_NAME . '_terms',
      array(
        'get_callback' => function($post_arr) {
          $wp_terms = get_terms(array(
            'taxonomy'   => EMBL_Taxonomy::TAXONOMY_NAME,
            'object_ids' => array($post_arr['id'])
          ));
          return array_map(function($wp_term) {
            $meta = get_term_meta($wp_term->term_id);
            $ids = $meta[EMBL_Taxonomy::META_IDS];
            $id = array_pop($ids);
            $data = array(
              'uuid'    => $id,
              'parents' => $ids,
              'name'    => $meta[EMBL_Taxonomy::META_NAME],
              'slug'    => $wp_term->slug
            );
            if (
              array_key_exists(EMBL_Taxonomy::META_DEPRECATED, $meta)
              && $meta[EMBL_Taxonomy::META_DEPRECATED]
            ) {
              $data['deprecated'] = true;
            }
            return $data;
          }, $wp_terms);
        }
      )
    );
  }

  /**
   * Return the WordPress EMBL taxonomy with all term meta assigned
   */
  static public function get_wp_taxonomy() {
    $wp_taxonomy = get_terms(array(
      'taxonomy'   => EMBL_Taxonomy::TAXONOMY_NAME,
      'hide_empty' => false
    ));
    if ($wp_taxonomy instanceof WP_Error) {
      return array();
    }
    foreach ($wp_taxonomy as $wp_term) {
      $wp_term->meta = get_term_meta($wp_term->term_id);
      foreach ($wp_term->meta as $key => $value) {
        if (strpos($key, EMBL_Taxonomy::TAXONOMY_NAME) !== 0) {
          continue;
        }
        // Return single values and unserialize is required
        if (is_array($value) && count($value) === 1) {
          $wp_term->meta[$key] = $value[0];
        }
        $wp_term->meta[$key] = maybe_unserialize($wp_term->meta[$key]);
      }
    }
    return $wp_taxonomy;
  }


  
  public function delete_deprecated_terms() {
    // Get all deprecated terms
    $terms = get_terms(array(
        'taxonomy'   => EMBL_Taxonomy::TAXONOMY_NAME,
        'meta_key'   => EMBL_Taxonomy::META_DEPRECATED,
        'meta_value' => '1',
        'fields'     => 'ids',
        'hide_empty' => false, // Set to false to include terms without posts
    ));
    

    // Check for errors or empty list
    if (is_wp_error($terms)) {
        return new WP_Error('term_query_error', __('Error querying terms: ' . $terms->get_error_message(), 'embl'), array('status' => 500));
    }

    if (empty($terms)) {
        return rest_ensure_response(array(
            'success' => true,
            'message' => __('No deprecated terms found to delete.', 'embl')
        ));
    }

    // Delete each deprecated term
    $deleted_terms = [];
    foreach ($terms as $term_id) {
        $result = wp_delete_term($term_id, EMBL_Taxonomy::TAXONOMY_NAME);
        if (is_wp_error($result)) {
            return new WP_Error('delete_error', __('Error deleting term ID ' . $term_id . ': ' . $result->get_error_message(), 'embl'), array('status' => 500));
        } else {
            $deleted_terms[] = $term_id; // Collect deleted term IDs
        }
    }

    return rest_ensure_response(array(
        'success' => true,
        'message' => sprintf(__('Successfully deleted %d deprecated terms.', 'embl'), count($deleted_terms))
    ));
}

  /**
   * Read and parse the EMBL Taxonomy API as JSON
   * The WordPress Taxonomy is rebuilt with matching ID terms synced
   */
  public function sync_taxonomy(WP_REST_Request $request = null) {

    $params = array();
    if ($request) {
      $params = $request->get_query_params();
    }
    $offset = isset($params['offset']) ? intval($params['offset']) : -1;

    // Temporary file location to cache old and new data during sync process
    $oldpath = trailingslashit( WP_CONTENT_DIR ) . 'uploads/' . EMBL_Taxonomy::TAXONOMY_NAME . '.old.txt';
    $newpath = trailingslashit( WP_CONTENT_DIR ) . 'uploads/' . EMBL_Taxonomy::TAXONOMY_NAME . '.new.txt';

    // Start new sync process if not offset is provided
    if ($offset < 0) {
      // Delete temporary files
      if (file_exists($oldpath)) {
        unlink($oldpath);
      }
      if (file_exists($newpath)) {
        unlink($newpath);
      }
      // Get the existing WordPress taxonomy
      file_put_contents($oldpath, serialize(self::get_wp_taxonomy()));
      // Fetch the new terms
      $value = $this->sync_taxonomy_temp_file($newpath);
      // Handle errors
      if (is_string($value)) {
        if ($request) {
          return array(
            'error' => $value
          );
        } else {
          throw new Exception($value);
        }
      }
      // Return the URL for the first batch
      if ($request) {
        return array(
          'next'   => rest_url(EMBL_Taxonomy::TAXONOMY_NAME .'/v1/sync/?offset=0'),
          'total'  => $value,
          'offset' => 0
        );
        exit;
      }
    }

    // Retrieve the old terms
    $wp_taxonomy = unserialize(file_get_contents($oldpath));
    // Retrieve the new terms
    $new_terms = unserialize(file_get_contents($newpath));

    // Allow taxonomy terms to be inserted
    $this->set_read_only(false);

    // Batch size for Request API or sync all terms (for WP CLI)
    $max_terms = $request ? self::SYNC_MAX_TERMS : PHP_INT_MAX;

    // Iterate over all terms to sync
    $i = 0;
    foreach ($new_terms as $term) {
      // Skip terms before the offset
      if ($i < $offset) {
        $i++;
        continue;
      }
      // Check if batch is complete
      if ($i >= $offset + $max_terms) {
        $this->set_read_only(true);
        // Cache progress and redirect to continue
        file_put_contents($oldpath, serialize($wp_taxonomy));
        // Return the URL for the next batch
        return array(
          'next'   => rest_url(EMBL_Taxonomy::TAXONOMY_NAME . '/v1/sync/?offset=' . $i),
          'total'  => count($new_terms),
          'offset' => $i,
        );
        exit;
      }
      $i++;

      // Find existing `WP_Term` in the taxonomy based on META_IDS structure
      $wp_term = null;
      foreach ($wp_taxonomy as $wp_taxonomy_term) {
        $wp_term_ids = $wp_taxonomy_term->meta[EMBL_Taxonomy::META_IDS];
        if ($wp_term_ids === $term[EMBL_Taxonomy::META_IDS]) {
          $wp_term = $wp_taxonomy_term;
          break;
        }
      }

      if ($wp_term instanceof WP_Term) {
        $wp_term->meta['matched'] = true;

      } else {
        // Add and return a new term if no match was found
        $ids = wp_insert_term(
          $term['name'],
          EMBL_Taxonomy::TAXONOMY_NAME,
          array('slug' => $term['slug'])
        );
        // TODO: is it possible for this to fail?
        if ( ! is_array($ids) || ! array_key_exists('term_id', $ids)) {
          continue;
        }
        $wp_term = get_term($ids['term_id']);
      }

      // Ensure `name` and `slug` values are up-to-date
      if ($wp_term->name !== $term['name'] || $wp_term->slug !== $term['slug']) {
        wp_update_term(
          $wp_term->term_id,
          EMBL_Taxonomy::TAXONOMY_NAME,
          array(
            'name' => $term['name'],
            'slug' => $term['slug']
          )
        );
      }

      update_term_meta(
        $wp_term->term_id,
        EMBL_Taxonomy::META_IDS,
        $term[EMBL_Taxonomy::META_IDS]
      );

      update_term_meta(
        $wp_term->term_id,
        EMBL_Taxonomy::META_NAME,
        $term[EMBL_Taxonomy::META_NAME]
      );

    }

    global $wpdb;

    // Disable term cache to avoid issues with stale data
    wp_defer_term_counting(true);
    
    // Handle deprecated terms
    foreach ($wp_taxonomy as $wp_term) {
        $is_deprecated = array_key_exists(EMBL_Taxonomy::META_DEPRECATED, $wp_term->meta);
    
        // Check if there are any posts tagged with the deprecated term
        if ($is_deprecated && $wp_term->count > 0) {
            // Find a replacement term with the same EMBL_Taxonomy::META_NAME
            $replacement_term_id = null;
            foreach ($wp_taxonomy as $potential_replacement) {
                if ($potential_replacement->term_id !== $wp_term->term_id &&
                    array_key_exists(EMBL_Taxonomy::META_NAME, $potential_replacement->meta) &&
                    $potential_replacement->meta[EMBL_Taxonomy::META_NAME] === $wp_term->meta[EMBL_Taxonomy::META_NAME]) {
                    $replacement_term_id = $potential_replacement->term_id;
                    break;
                }
            }
    
            // If a replacement term is found, proceed with updating posts
            if ($replacement_term_id) {
                // Get all post IDs associated with the deprecated term
                $post_ids = $wpdb->get_col($wpdb->prepare(
                    "SELECT object_id FROM {$wpdb->term_relationships} 
                     WHERE term_taxonomy_id = %d",
                    $wp_term->term_id
                ));
    
                // Replace the deprecated term for each post
                foreach ($post_ids as $post_id) {
                    // Assign the replacement term to the post
                    wp_add_object_terms($post_id, $replacement_term_id, EMBL_Taxonomy::TAXONOMY_NAME);
    
                    // Remove the deprecated term from the post
                    // wp_remove_object_terms($post_id, $wp_term->term_id, EMBL_Taxonomy::TAXONOMY_NAME);
                }
            }
        }
    
        // Delete the term if it's unassigned after replacements
        if ($is_deprecated && $wp_term->count === 0) {
            wp_delete_term($wp_term->term_id, EMBL_Taxonomy::TAXONOMY_NAME);
            continue;
        }
    
        if (array_key_exists('matched', $wp_term->meta)) {
            // Reactivate an old term
            if ($is_deprecated) {
                delete_term_meta($wp_term->term_id, EMBL_Taxonomy::META_DEPRECATED);
            }
        } else if (!$is_deprecated) {
            update_term_meta($wp_term->term_id, EMBL_Taxonomy::META_DEPRECATED, 1);
        }
    }
    
    // Re-enable term counting and recount terms to refresh cache
    wp_defer_term_counting(false);
    wp_update_term_count_now(array_column($wp_taxonomy, 'term_id'), EMBL_Taxonomy::TAXONOMY_NAME);
    


    $this->set_read_only(true);

    update_option(self::OPTION_MODIFIED, time());

    unlink($newpath);
    unlink($oldpath);

    // Return success message
    return array(
      'success' => true,
      'terms'   => count($new_terms)
    );
  }

  /**
   * Request new taxonomy terms from the EMBL Taxonomy API
   * This is done at the start of the sync process
   */
  private function sync_taxonomy_temp_file($newpath) {
    // Make contenthub taxonomy api call to work on local
    $whitelist = array(
      '127.0.0.1',
      '::1'
    );
    if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
      $embl_taxonomy_url = "https://content.embl.org/api/v1/pattern.json?pattern=embl-ontology&source=contenthub";
    } else {
      // Prod Proxy endpoint.
      $embl_taxonomy_url = embl_taxonomy_get_url();
    }

    // Attempt to read the EMBL Taxonomy API
    $data = file_get_contents($embl_taxonomy_url);
    if ($data === false) {
      return sprintf(
        __('The %1$s API endpoint could not be accessed.', 'embl'),
        $this->labels['name']
      );
    }

    // Attempt to parse API results
    $json_terms = self::decode_terms($data);
    if (empty($json_terms)) {
      return sprintf(
        __('The %1$s API result could not be parsed.', 'embl'),
        $this->labels['name']
      );
    }

    // Generate the new taxonomy terms from the API terms provided
    $new_terms = array();

    self::generate_terms($json_terms, $new_terms);

    self::sort_terms($new_terms);

    file_put_contents($newpath, serialize($new_terms));

    return count($new_terms);
  }

  /**
   * Decode and validate JSON data from the API
   */
  static private function decode_terms($data) {
    $json = json_decode($data);
    if ($json === null || ! is_object($json->terms)) {
      return false;
    }
    $new_terms = array();
    $term_keys = array('uuid', 'name', 'parents', 'primary');
    // Format term objects as arrays and add meta keys
    foreach($json->terms as $key => $json_term) {
      $json_term = (array) $json_term;
      $new_term = array();
      if ($json_term['name_display']) {
        $json_term['name'] = $json_term['name_display'];
      }
      if (is_object($json_term['parents'])) {
        // Some terms may have their parents as an object (like EMBL.org profiles)
        // For the purposes of WP, this isn't important, or at least not in its current
        // implementation. So things are much simpler if we make that object a generic array
        // and drop the object keys
        $parents_as_array = array();
        foreach($json_term['parents'] as $key => $parent) {
          // Validate parent term UUID
          if (preg_match(self::UUID_PATTERN, $parent)) {
            array_push($parents_as_array, $parent);
          }
        }
        $json_term['parents'] = $parents_as_array;
      }
      foreach ($term_keys as $key) {
        $new_term[$key] = array_key_exists($key, $json_term) ? $json_term[$key] : null;
      }
      // Validate term UUID
      if (preg_match(self::UUID_PATTERN, $new_term['uuid'])) {
        $new_term[EMBL_Taxonomy::META_NAME] = $new_term['name'];
        $new_term[EMBL_Taxonomy::META_IDS] = array($new_term['uuid']);
        // Is this possible? Not an issue to date...
        if (array_key_exists($new_term['uuid'], $new_terms)) {
          error_log("Duplicate EMBL taxonomy UUID: {$new_term['uuid']}");
        }
        // Use associative array index for faster lookup
        $new_terms[$new_term['uuid']] = $new_term;
      }
    }
    return $new_terms;
  }

  /**
   * Generate an array of taxonomy terms from JSON
   * Add new term for each "Child > Parent" relationship
   */
  static private function generate_terms(& $api_terms, & $terms) {
    // Iterate over each base term
    foreach ($api_terms as $uuid => $term) {
        // Initialize prefix IDs with the term's own IDs
        $prefix_ids = $term[EMBL_Taxonomy::META_IDS];

        // Initialize the prefix name with the primary term
        $primary_term_name = ucfirst($term['primary']);
        $prefix_name = $primary_term_name . EMBL_Taxonomy::TAXONOMY_SEPARATOR;

        // Add primary term ID to prefix IDs
        switch ($term['primary']) {
            case 'what':
                array_unshift($prefix_ids, '302cfdf7-365b-462a-be65-82c7b783ebf7');
                break;
            case 'who':
                array_unshift($prefix_ids, '4428d1fd-441a-4d6d-a1c5-5dcf5665f213');
                break;
            case 'where':
                array_unshift($prefix_ids, 'b14d3f13-5670-44fb-8970-e54dfd9c921a');
                break;
        }

        // Check if the term has parents and retrieve the correct one based on the primary term
        if (is_array($term['parents']) && !empty($term['parents'])) {
            // Initialize a variable to track if a parent was found
            $found_parent = false;

            // Loop through parents to find the one that corresponds to the primary term
            foreach ($term['parents'] as $parent_id) {
                if (array_key_exists($parent_id, $api_terms)) {
                    $parent_term = $api_terms[$parent_id];
                    
                    // Check if the parent term is of the same type as the primary
                    if ($parent_term['primary'] === $term['primary']) {
                        // Append the parent's name to the prefix name
                        $prefix_name .= $parent_term['name'] . EMBL_Taxonomy::TAXONOMY_SEPARATOR;
                        // Add the parent term's IDs to prefix_ids
                        $prefix_ids = array_merge((array)$parent_term[EMBL_Taxonomy::META_IDS], $prefix_ids);
                        $found_parent = true;
                        break; // Stop after finding the first matching parent
                    }
                } else {
                    error_log("Parent ID '$parent_id' not found in api_terms.");
                }
            }

            if (!$found_parent) {
                error_log("No valid parent found for term '{$term['name']}' with primary '{$term['primary']}'.");
            }
        }

        // Append the current term's name
        $prefix_name .= $term['name'];

        // Update the term with the prefixed name and IDs
        $term['name'] = rtrim($prefix_name, EMBL_Taxonomy::TAXONOMY_SEPARATOR); // Remove trailing separator
        $term[EMBL_Taxonomy::META_IDS] = $prefix_ids;

        // Add the modified term to the terms array
        $terms[] = $term;
    }

    // Set the WordPress taxonomy slug
    foreach ($terms as $i => $term) {
        $terms[$i]['slug'] = sanitize_title($term['name']);
    }
}




  /**
   * Sort taxonomy terms
   * only matters when displaying in Admin UI ?
   */
  static private function sort_terms(& $terms) {
    usort($terms, function($a, $b) {
      $alen = count($a[EMBL_Taxonomy::META_IDS]);
      $blen = count($b[EMBL_Taxonomy::META_IDS]);
      // Sort alphabetically if same level
      if ($a[EMBL_Taxonomy::META_IDS][0] !== $b[EMBL_Taxonomy::META_IDS][0] || $alen === $blen) {
        return strcmp($a['name'], $b['name']);
      }
      // Sort by longest chain
      return $alen - $blen;
    });
  }

  /**
   * Action `admin_notices`
   * Show admin warning notice if the taxonomy needs syncing
   * Show admin success notice if a sync happening recently
   */
  public function action_admin_notices() {
    if ( ! current_user_can('manage_categories')) {
      return;
    }

    $now = time();
    $modified = intval(get_option(self::OPTION_MODIFIED));

    $notice = false;

    // Sync required (all pages)
    if (($now - $modified) >= EMBL_Taxonomy::MAX_AGE) {
      printf('<div class="%1$s"><p><span>%2$s</span> %3$s</p></div>',
        esc_attr('notice notice-warning'),
        esc_html(sprintf(
          __('The %1$s may be outdated and should be synced', 'embl'),
          $this->labels['name']
        )),
        sprintf(
          '<button id="embl-taxonomy-sync" type="button" data-href="%1$s" class="button button-small">%2$s</button>',
          esc_attr('edit-tags.php?taxonomy=' . EMBL_Taxonomy::TAXONOMY_NAME . '&sync=true'),
          esc_html(__('Sync now', 'embl'))
        )
      );
      $notice = true;
    }

    // Sync happened (all pages)
    if (($now - $modified) <= 10) {
      printf('<div class="%1$s"><p>%2$s</p></div>',
        esc_attr('notice notice-success'),
        esc_html(sprintf(
          __('%1$s was recently synced.', 'embl'),
          $this->labels['name']
        ))
      );
      $notice = true;
    }

    // Manual sync notice (edit taxonomy page only)
    if ( current_user_can( 'administrator' ) ) {
    if ( ! $notice && function_exists('get_current_screen')) {
      $screen = get_current_screen();
      if ($screen->id === 'edit-embl_taxonomy') {
        if (isset($_GET['synced']) && $_GET['synced'] === 'true') {
          printf('<div class="%1$s"><p>%2$s</p></div>',
            esc_attr('notice notice-success'),
            __('Taxonomy sync complete.', 'embl')
          );
        }
        printf('<div class="%1$s"><p><span>%2$s</span> %3$s</p></div>',
          esc_attr('notice notice-info'),
          esc_html(sprintf(
            __('The %1$s was last synced at %2$s', 'embl'),
            $this->labels['name'],
            date('jS F Y H:i:s', $modified)
          )),
          sprintf(
            '<button id="embl-taxonomy-sync" data-href="%1$s" class="button button-small">%2$s</button>',
            esc_attr('edit-tags.php?taxonomy=' . EMBL_Taxonomy::TAXONOMY_NAME . '&sync=true'),
            esc_html(__('Sync now', 'embl'))
          )
        );
      }
    }
  }
// Add notice for deleting or displaying deprecated terms (all pages for administrators)
if (current_user_can('administrator')) {
  $deprecated_count = $this->get_deprecated_terms_count(); // Assume this function returns count of deprecated terms
  if ( function_exists('get_current_screen')) {
    $screen = get_current_screen();
    if ($screen->id === 'edit-embl_taxonomy') {
  if ($deprecated_count > 0) {
    printf('<div class="%1$s"><p><span>%2$s</span> %3$s %4$s</p></div>',
      esc_attr('notice notice-warning'),
      esc_html(sprintf(
        __('There are %1$d deprecated terms that may need review.', 'embl'),
        $deprecated_count
      )),
      sprintf(
        '<button id="embl-taxonomy-delete-deprecated" type="button" data-href="%1$s" class="button button-small">%2$s</button>',
        esc_attr('edit-tags.php?taxonomy=' . EMBL_Taxonomy::TAXONOMY_NAME . '&delete_deprecated=true'),
        esc_html(__('Delete all deprecated terms', 'embl'))
      ),
      sprintf(
        '<button id="embl-taxonomy-show-deprecated" type="button" data-href="%1$s" class="button button-small">%2$s</button>',
        esc_attr('edit-tags.php?taxonomy=' . EMBL_Taxonomy::TAXONOMY_NAME . '&filter=deprecated'),
        esc_html(__('Show only deprecated terms', 'embl'))
      )
    );
  }
}
}
}




}

private function get_deprecated_terms_count() {
  $terms = get_terms(array(
    'taxonomy'   => EMBL_Taxonomy::TAXONOMY_NAME,
    'meta_key'   => EMBL_Taxonomy::META_DEPRECATED,
    'meta_value' => '1',
    'hide_empty' => false, // Ensure all deprecated terms are counted
  ));
  return is_array($terms) ? count($terms) : 0;
}


  /**
   * Filter `pre_insert_term`
   * Gatekeep new taxonomy terms being inserted
   */
  public function filter_pre_insert_term($term, $taxonomy) {
    if ($taxonomy !== EMBL_Taxonomy::TAXONOMY_NAME) {
      return $term;
    }
    if ($this->is_read_only()) {
      return new WP_Error(
        EMBL_Taxonomy::TAXONOMY_NAME . '_edit_error',
        sprintf(
          __('%1$s terms are predefined and cannot be edited.', 'embl'),
          $this->labels['name']
        )
      );
    }
    return $term;
  }

  /**
   * Filter `embl_taxonomy_name`
   * Add deprecation warning to old terms
   */
  public function filter_term_name($value, $term_id, $context) {
    if ($context === 'display') {
      $deprecated = get_term_meta($term_id, EMBL_Taxonomy::META_DEPRECATED, true);
      if (intval($deprecated) === 1) {
        return "⚠️ {$value} (deprecated but retained as local content still tagged by term)";
      }
    }
    return $value;
  }

  /**
   * Action `admin_enqueue_scripts`
   * Add stylesheet for: /wp-admin/edit-tags.php?taxonomy=embl_taxonomy
   */
  public function action_admin_enqueue() {
    $screen = get_current_screen();
    $edit_tags_id = 'edit-' . EMBL_Taxonomy::TAXONOMY_NAME;

    wp_register_script(
      'embl-taxonomy',
      plugin_dir_url(__FILE__) . '../assets/embl-taxonomy.js',
      array(),
      time(),
      true
    );

    wp_localize_script('embl-taxonomy', 'emblTaxonomySettings', array(
      'data' => array(
        'syncing' => __('Syncing – please do not close this window.', 'embl'),
        'reload'  => __('This page will reload after the sync is done.', 'embl'),
        'error'   => __('There was an error syncing the taxonomy.', 'embl')
      ),
      'redirect' => esc_url_raw(admin_url('edit-tags.php?taxonomy=' . EMBL_Taxonomy::TAXONOMY_NAME . '&synced=true')),
      'path'     => esc_url_raw(rest_url(EMBL_Taxonomy::TAXONOMY_NAME . '/v1/sync')),
      'deletePath' => esc_url_raw(rest_url(EMBL_Taxonomy::TAXONOMY_NAME . '/v1/delete-deprecated')),
      'token'    => wp_create_nonce('wp_rest')
    ));

    wp_enqueue_script('embl-taxonomy');

    if ($screen->id === $edit_tags_id) {
      wp_enqueue_style(
        $edit_tags_id,
        plugin_dir_url(__FILE__) . '../assets/embl-taxonomy-edit-tags.css',
        array(),
        false,
        'all'
      );
    }
  }

}

endif;

?>
