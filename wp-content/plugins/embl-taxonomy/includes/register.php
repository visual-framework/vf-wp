<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('EMBL_Taxonomy_Register') ) :

class EMBL_Taxonomy_Register {

  // Options table keys
  const OPTION_MODIFIED = EMBL_Taxonomy::TAXONOMY_NAME . '_modified';

  // https://api.drupal.org/api/drupal/core!lib!Drupal!Component!Uuid!Uuid.php/constant/Uuid%3A%3AVALID_PATTERN/
  const UUID_PATTERN = '#^[0-9a-f]{8}-([0-9a-f]{4}-){3}[0-9a-f]{12}$#';

  protected $labels;

  private $sync_error = false;

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
      add_action('admin_init', array($this, 'action_admin_init'));
      add_action('admin_notices', array($this, 'action_admin_notices'));
      add_action('admin_enqueue_scripts', array($this, 'action_admin_enqueue'));
    }

    // Add column for term UUID
    // http://wpthemecraft.com/code-snippets/add-custom-columns-to-taxonomy-list-table/

    /*
     * filter pattern: manage_edit-{taxonomy}_columns
     * where {taxonomy} is the name of taxonomy e.g; 'embl_taxonomy'
     * codex ref: https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$taxonomy_id_columns
     */
    add_filter( 'manage_edit-embl_taxonomy_columns' , 'wptc_embl_taxonomy_columns' );
    function wptc_embl_taxonomy_columns( $columns ) {
    	// remove slug column
    	// unset($columns['slug']);

    	// add column
    	$columns['embl_taxonomy_term_uuid'] = __('EMBL Term UUID');
    	return $columns;
    }

    /*
     * filter pattern: manage_{taxonomy}_custom_column
     * where {taxonomy} is the name of taxonomy e.g; 'embl_taxonomy'
     * codex ref: https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$taxonomy_id_columns
     */
    add_filter( 'manage_embl_taxonomy_custom_column', 'wptc_embl_taxonomy_column_content', 10, 3 );
    function wptc_embl_taxonomy_column_content( $content, $column_name, $term_id ) {
    	// get the term object
    	$term = get_term( $term_id, 'embl_taxonomy' );
    	// check if column is our custom column
    	if ( 'embl_taxonomy_term_uuid' == $column_name ) {
        $full_term = embl_taxonomy_get_term($term->term_id);
        // Eventually we should link back to the contenHub, however we don't currently
        // have a good way to search by UUID
        // https://dev.content.embl.org/api/v1/pattern.html?filter-content-type=profiles&filter-uuid=2a270b68-46c3-4b3f-92c5-0a65eb896c86&pattern=node-display-title
        // the above query depends on knowing the conent type
    		$content = '<code>'.end($full_term->meta['embl_taxonomy_ids']).'</code>';
    	}
    	return $content;
    }

    $this->set_read_only();
  }

  /**
   * Use a global option to manage the read-only state
   */
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
        'capabilities'      => array(
          'manage_terms' => 'manage_categories',
          'edit_terms'   => EMBL_Taxonomy::TAXONOMY_NAME . 'edit_terms',
          'delete_terms' => EMBL_Taxonomy::TAXONOMY_NAME . 'delete_terms',
          'assign_terms' => 'edit_posts'
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
   * Add taxonomy terms assigned to posts
   */
  public function register_rest_api() {
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

  /**
   * Read and parse the EMBL Taxonomy API as JSON
   * Error messages are picked up by the `admin_notices` action
   * The WordPress Taxonomy is rebuilt with matching ID terms synced
   */
  public function sync_taxonomy() {
    // Make contenthub taxonomy api call to work on local
    $whitelist = array(
      '127.0.0.1',
      '::1'
    );

    if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
      $embl_taxonomy_url = "https://content.embl.org/api/v1/pattern.json?pattern=embl-ontology&source=contenthub";
    }
    else {
      // Prod Proxy endpoint.
      $embl_taxonomy_url = embl_taxonomy_get_url();
    }

    // Attempt to read the EMBL Taxonomy API
    $data = file_get_contents($embl_taxonomy_url);
    if ($data === false) {
      $this->sync_error = sprintf(
        __('The %1$s API endpoint could not be accessed.', 'embl'),
        $this->labels['name']
      );
      return $this->sync_error;
    }

    // Attempt to parse API results
    $json_terms = self::decode_terms($data);
    if (empty($json_terms)) {
      $this->sync_error = sprintf(
        __('The %1$s API result could not be parsed.', 'embl'),
        $this->labels['name']
      );
      return $this->sync_error;
    }

    // Generate the new taxonomy terms from the API terms provided
    $new_terms = array();

    self::generate_terms($json_terms, $new_terms);

    self::sort_terms($new_terms);

    // Get the existing WordPress taxonomy
    $wp_taxonomy = self::get_wp_taxonomy();

    // Allow taxonomy terms to be inserted
    $this->set_read_only(false);

    // Iterate over all terms to sync
    foreach ($new_terms as $term) {

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

    // Handle deprecated terms
    foreach ($wp_taxonomy as $wp_term) {
      $is_deprecated = array_key_exists(EMBL_Taxonomy::META_DEPRECATED, $wp_term->meta);

      // Delete term if unassigned
      if ($is_deprecated && $wp_term->count === 0) {
        wp_delete_term($wp_term->term_id, EMBL_Taxonomy::TAXONOMY_NAME);
        continue;
      }

      if (array_key_exists('matched', $wp_term->meta)) {
        // Reactivate an old term
        if ($is_deprecated) {
          delete_term_meta($wp_term->term_id, EMBL_Taxonomy::META_DEPRECATED);
        }
      } else if ( ! $is_deprecated) {
        update_term_meta($wp_term->term_id, EMBL_Taxonomy::META_DEPRECATED, 1);
      }
    }

    $this->set_read_only(true);

    update_option(self::OPTION_MODIFIED, time());

    return true;
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
    $term_keys = array('uuid', 'name', 'parents');
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
  static private function generate_terms(& $api_terms, & $terms, array $term = null, array $parent = null) {

    // If no term is specified start the recursion
    if ( ! $term) {
      // Iterate over each base term
      foreach ($api_terms as $uuid => $term) {
        self::generate_terms($api_terms, $terms, $term);
      }
      // Set the WordPress taxonomy slug
      // use implode('-', $term[EMBL_Taxonomy::META_IDS]) if name is too long?
      foreach ($terms as $i => $term) {
        $terms[$i]['slug'] = sanitize_title($term['name']);
      }
      return;
    }

    // Prefix IDs and name with parent(s)
    if (is_array($parent)) {
      $term['name'] .= EMBL_Taxonomy::TAXONOMY_SEPARATOR . $parent['name'];
      $term[EMBL_Taxonomy::META_NAME] = $parent[EMBL_Taxonomy::META_NAME];
      $term[EMBL_Taxonomy::META_IDS] = array_merge(
        $term[EMBL_Taxonomy::META_IDS],
        $parent[EMBL_Taxonomy::META_IDS]
      );
    }

    // If this new term has multiple parents generate a unique term for
    // the final hierarchy level, for example:
    // Parent-1 > Term
    // Parent-2 > Parent-1 > Term
    if (is_array($term['parents']) && count($term['parents'])) {
      // Iterate over parent term IDs and then all terms to find the parent
      foreach ($term['parents'] as $parent_id) {

        if (array_key_exists($parent_id, $api_terms)) {
          $new_parent = $api_terms[$parent_id];
          // Cannot generate terms that are a parent of itself
          if ($term['uuid'] === $new_parent['uuid']) {
            continue;
          }
          self::generate_terms($api_terms, $terms, $new_parent, $term);
        }
      }
    // Otherwise add new term
    } else {
      // Ignore base level terms "Who", "What", "Where", etc
      if (count($term[EMBL_Taxonomy::META_IDS]) > 1) {
        $terms[] = $term;
      }
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
        return $a['name'] > $b['name'];
      }
      // Sort by longest chain
      return $alen > $blen;
    });
  }

  /**
   * Action `admin_init`
   */
  public function action_admin_init() {
    if ( ! current_user_can('manage_categories')) {
      return;
    }
    global $pagenow;
    if ($pagenow !== 'edit-tags.php') {
      return;
    }
    if (array_key_exists('taxonomy', $_GET) && $_GET['taxonomy'] !== EMBL_Taxonomy::TAXONOMY_NAME) {
      return;
    }
    if (array_key_exists('sync', $_GET) && $_GET['sync'] === 'true') {
      $this->sync_taxonomy();
    }
  }

  /**
   * Action `admin_notices`
   * Show admin warning notice if the taxonomy needs syncing
   * Show admin success notice if a sync happening recently
   */
  public function action_admin_notices() {

    if ($this->sync_error) {
      printf('<div class="%1$s"><p>%2$s</p></div>',
        esc_attr('notice notice-error'),
        esc_html($this->sync_error)
      );
    }

    if ( ! current_user_can('manage_categories')) {
      return;
    }

    $now = time();
    $modified = intval(get_option(self::OPTION_MODIFIED));

    $notice = false;

    // Sync required (all pages)
    if (($now - $modified) >= EMBL_Taxonomy::MAX_AGE) {
      printf('<div class="%1$s"><p>%2$s %3$s</p></div>',
        esc_attr('notice notice-warning'),
        esc_html(sprintf(
          __('The %1$s may be outdated and should be synced', 'embl'),
          $this->labels['name']
        )),
        sprintf(
          '<a href="%1$s" class="button button-small">%2$s</a>',
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
    if ( ! $notice && function_exists('get_current_screen')) {
      $screen = get_current_screen();
      if ($screen->id === 'edit-embl_taxonomy') {
        printf('<div class="%1$s"><p>%2$s %3$s</p></div>',
          esc_attr('notice notice-info'),
          esc_html(sprintf(
            __('The %1$s was last synced at %2$s', 'embl'),
            $this->labels['name'],
            date('jS F Y H:i:s', $modified)
          )),
          sprintf(
            '<a href="%1$s" class="button button-small">%2$s</a>',
            esc_attr('edit-tags.php?taxonomy=' . EMBL_Taxonomy::TAXONOMY_NAME . '&sync=true'),
            esc_html(__('Sync now', 'embl'))
          )
        );
      }
    }
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
