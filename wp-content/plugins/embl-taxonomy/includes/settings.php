<?php

if( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('EMBL_Taxonomy_Settings') ) :

/**
 * Class for adding a new field to the options-general.php page
 */
class EMBL_Taxonomy_Settings {

  private $defaults = array(
    'embl_taxonomy' => 'https://www.embl.org/api/v1/pattern.json?pattern=embl-ontology&source=contenthub'
  );

  private $props = array(
    'who' => array(
      'ref'    => 'embl:who',
      'acf'    => 'embl_taxonomy_term_who',
      'search' => 'Who &gt;'
    ),
    'where' => array(
      'ref'    => 'embl:where',
      'acf'    => 'embl_taxonomy_term_where',
      'search' => 'Where &gt;'
    ),
    'what' => array(
      'ref'    => 'embl:what',
      'acf'    => 'embl_taxonomy_term_what',
      'search' => 'What &gt;'
    )
  );

  function __construct() {
    add_action(
      'acf/init',
      array($this, 'acf_init')
    );
    add_filter(
      'acf/settings/load_json',
      array($this, 'acf_settings_load_json')
    );
    add_action(
      'init',
      array($this, 'init')
    );
    add_action(
      'wp_head',
      array($this, 'wp_head')
    );
    foreach ($this->props as $prop) {
      add_filter(
        "acf/fields/taxonomy/query/name={$prop['acf']}",
        array($this, 'acf_query_terms'), 10, 3
      );
    }
  }

  /**
   * Filter: `acf/settings/load_json`
   */
  public function acf_settings_load_json($paths) {
    $paths[] = trailingslashit(dirname(__FILE__, 2)) . 'acf-json';
    return $paths;
  }

  /**
   * Action `acf/init`
   */
  function acf_init() {
    if ( ! function_exists('acf_add_local_field_group')) {
      return;
    }

    acf_add_local_field_group(array(
      'key' => 'group_embl_taxonomy_setings',
      'title' => 'EMBL Taxonomy Settings',
      'fields' => array (),
      'location' => array (
        array (
          array (
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'vf-settings',
          ),
        ),
      ),
      'menu_order' => 10,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => true,
      'description' => '',
      'modified' => 1598527351
    ));

    acf_add_local_field(
      array(
        'parent' => 'group_embl_taxonomy_setings',
        'key' => 'field_embl_taxonomy',
        'label' => 'EMBL Taxonomy URL',
        'name' => 'embl_taxonomy',
        'type' => 'url',
        'instructions' => 'Where to load the EMBL Taxonomy from; recommended:<br><code>' . $this->defaults['embl_taxonomy'] . '</code>',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => $this->defaults['embl_taxonomy'],
        'placeholder' => '',
      )
    );

    acf_add_local_field(
      array(
        'parent' => 'group_embl_taxonomy_setings',
        'key' => 'field_embl_taxonomy_autocomplete',
        'label' => 'Search Autocomplete',
        'name' => 'embl_taxonomy_autocomplete',
        'type' => 'true_false',
        'instructions' => 'Allow keyword search suggestions using EMBL Taxonomy terms',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'message' => '',
        'default_value' => 1,
        'ui' => 1,
        'ui_on_text' => '',
        'ui_off_text' => '',
      )
    );

        // New field group specifically for Deprecated checkbox
        acf_add_local_field_group(array(
          'key' => 'group_embl_taxonomy_deprecated',
          'fields' => array(
              array(
                  'key' => 'field_embl_taxonomy_deprecated',
                  'label' => 'Deprecated',
                  'name' => 'embl_taxonomy_deprecated',
                  'type' => 'true_false',
                  'instructions' => 'Select "Yes" to manually deprecate the term.',
                  'required' => 0,
                  'conditional_logic' => 0,
                  'wrapper' => array(
                      'width' => '',
                      'class' => '',
                      'id' => '',
                  ),
                  'message' => '',
                  'default_value' => get_term_meta(get_queried_object_id(), EMBL_Taxonomy::META_DEPRECATED, true) ? 1 : 0,
                  'ui' => 1,
                  'ui_on_text' => 'Yes',
                  'ui_off_text' => 'No',
              ),
          ),
          'location' => array(
              array(
                  array(
                      'param' => 'taxonomy',
                      'operator' => '==',
                      'value' => 'embl_taxonomy', // Specify the taxonomy
                  ),
              ),
          ),
          'menu_order' => 20,
          'position' => 'side',
          'style' => 'default',
          'label_placement' => 'top',
          'instruction_placement' => 'label',
          'hide_on_screen' => '',
          'active' => true,
          'description' => '',
      ));

      $active_theme = wp_get_theme();
      $is_news_theme = ($active_theme->get('Name') === 'VF-WP News');
      
      if ($active_theme == $is_news_theme) {

        acf_add_local_field_group(array(
          'key' => 'group_embl_taxonomy_hidden',
          'fields' => array(
              array(
                  'key' => 'field_embl_taxonomy_hidden',
                  'label' => 'Hidden',
                  'name' => 'embl_taxonomy_hidden',
                  'type' => 'true_false',
                  'instructions' => 'Select "Yes" to not show the term in the taxonomy picker.',
                  'required' => 0,
                  'conditional_logic' => 0,
                  'wrapper' => array(
                      'width' => '',
                      'class' => '',
                      'id' => '',
                  ),
                  'message' => '',
                  'default_value' => 0,
                  'ui' => 1,
                  'ui_on_text' => 'Yes',
                  'ui_off_text' => 'No',
              ),
          ),
          'location' => array(
              array(
                  array(
                      'param' => 'taxonomy',
                      'operator' => '==',
                      'value' => 'embl_taxonomy', // Specify the taxonomy
                  ),
              ),
          ),
          'menu_order' => 20,
          'position' => 'side',
          'style' => 'default',
          'label_placement' => 'top',
          'instruction_placement' => 'label',
          'hide_on_screen' => '',
          'active' => true,
          'description' => '',
      ));
    }
  }

  /**
   * Return a settings field value (or default value)
   */
  function get_field($name) {
    $value = function_exists('get_field') ? get_field($name, 'option') : null;
    // Ensure user has not entered extraneous whitespace
    $value = is_string($value) ? trim($value) : $value;
    // Return default if no value is set
    if ( ! $value && array_key_exists($name, $this->defaults)) {
      return $this->defaults[$name];
    }
    return $value;
  }

  /**
   * Filter the ACF taxonomy query response so it's limited to relevant terms
   */
  public function acf_query_terms($args, $field, $post_id) {
    // Find the root term to filter by
    foreach ($this->props as $slug => $prop) {
      if ($field['name'] === $prop['acf']) {
        // Get the root term object
        $term = get_term_by('slug', $slug, EMBL_Taxonomy::TAXONOMY_NAME);
        if ( ! ($term instanceof WP_Term) || $term->slug !== $slug) {
          break;
        }
        // Get the root term meta IDs
        $meta = get_term_meta($term->term_id, EMBL_Taxonomy::META_IDS, true);
        if ( ! is_array($meta) || ! count($meta)) {
          break;
        }
        // Filter EMBL taxonomy terms where the root term is a parent
        // Parent term IDs are stored in the serialized array meta value
        // These args are for a `LIKE` keyword search on the serialized string
        // Because IDs are 36 character UUIDs it should not return false positives
        $args['meta_key'] = EMBL_Taxonomy::META_IDS;
        $args['meta_value'] = $meta[0];
        $args['meta_compare'] = 'LIKE';
        break;
      }
    }
    return $args;
  }

  /**
   * Action `init`
   * Retrieve WP_Term objects and meta data
   */
  public function init() {
    if (
      ! function_exists('get_field') ||
      ! function_exists('embl_taxonomy_get_term')
    ) {
      return;
    }
    foreach ($this->props as $i => $prop) {
      $term = embl_taxonomy_get_term(
        get_field($prop['acf'], 'option')
      );
      if ($term) {
        $this->props[$i]['term'] = $term;
      }
    }
  }

  /**
   * Action `wp_head`
   * Output EMBL Taxonomy meta properties in the `<head>`
   */
  public function wp_head() {
    $props = $this->props;
    // Override global settings for single posts
    if (is_singular()) {
      foreach ($props as $i => $prop) {
        $term = embl_taxonomy_get_term(
          get_field($prop['acf'])
        );
        if ($term) {
          $props[$i]['term'] = $term;
        }
      }
    }
    foreach ($props as $prop) {
      if ( ! array_key_exists('term', $prop)) {
        continue;
      }
      if ( ! property_exists($prop['term'], 'meta')) {
        continue;
      }
      $ids = $prop['term']->meta[EMBL_Taxonomy::META_IDS];
      echo '<meta name="' , $prop['ref'] , '"';
      echo ' content="' , esc_attr($prop['term']->meta[EMBL_Taxonomy::META_NAME]) , '"';
      if (is_array($ids)) {
        echo ' uuid="' , esc_attr(end($ids)) , '"';
      }
      echo ">\n";
    }
    if (array_key_exists('term', $props['what'])) {
      echo '<meta name="embl:active" content="what">' , "\n";
    }
  }
}

endif;

?>
