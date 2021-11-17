<?php

if ( ! defined( 'ABSPATH' ) ) exit;

require_once('vf-templates-placeholder.php');

if ( ! class_exists('VF_Templates') ) :

/**
 * Represent a custom post type for Visual Framework WP templates
 */
class VF_Templates {

  public function __construct() {
    // Nothing
  }

  static public function type() {
    return 'vf_template';
  }

  /**
   * Return true if `$post` is valid `vf_template`
   */
  static public function is_template_post($post, $post_name = '') {
    $theme = wp_get_theme();
    if ( ! $post instanceof WP_Post) {
      return false;
    }
    if ($post->post_type !== VF_Templates::type()) {
      return false;
    }
    if ( ! empty($post_name) &&  $post->post_name !== $post_name) {
      return false;
    }
    if (is_singular( 'vf_event' )) {
      return false;
    }
    // industry theme templates
    if (is_singular( 'industry_event' )) {
      return false;
    }
    if (is_page_template(array( 'template-industry-workshop-archive.php', 'template-industry-quarterly-meeting-archive.php', 'template-industry-members-area.php', 'template-industry-quarterly-meeting.php', 'template-industry-workshops.php', 'template-hero-secondary.php', 'template-secondary-hero-embl.php', 'template-secondary-hero-ebi.php'  ))) {
      return false;
    }
    if (($theme == 'VF-WP Intranet') && ((is_search()) || (is_page_template('searchpage.php')))) {
      return false;
     }
    
    return true;
  }

  /**
   * Return default template post content
   */

 

  static public function default_template() {
    $post_content = '

<!-- wp:acf/vf-container-global-header {"id":"' . uniqid('block_') . '","name":"acf/vf-container-global-header"} /-->

<!-- wp:acf/vf-container-ebi-global-header {"id":"' . uniqid('block_') . '","name":"acf/vf-container-ebi-global-header"} /-->

<!-- wp:acf/vf-container-breadcrumbs {"id":"' . uniqid('block_') . '","name":"acf/vf-container-breadcrumbs"} /-->

<!-- wp:acf/vf-container-wp-groups-header {"id":"' . uniqid('block_') . '","name":"acf/vf-container-wp-groups-header"} /-->

<!-- wp:acf/vf-container-page-template {"id":"' . uniqid('block_') . '","name":"acf/vf-container-page-template"} /-->

<!-- wp:acf/vf-container-global-footer {"id":"' . uniqid('block_') . '","name":"acf/vf-container-global-footer"} /-->

<!-- wp:acf/vf-container-ebi-global-footer {"id":"' . uniqid('block_') . '","name":"acf/vf-container-ebi-global-footer"} /-->


';
    $post_content = apply_filters(
      'vf/templates/post_content/default',
      $post_content
    );
    return $post_content;
  }

    /**
   * Return front page template post content
   */
  static public function front_page_template() {
    $post_content = '

<!-- wp:acf/vf-container-global-header {"id":"' . uniqid('block_') . '","name":"acf/vf-container-global-header"} /-->

<!-- wp:acf/vf-container-ebi-global-header {"id":"' . uniqid('block_') . '","name":"acf/vf-container-ebi-global-header"} /-->

<!-- wp:acf/vf-container-breadcrumbs {"id":"' . uniqid('block_') . '","name":"acf/vf-container-breadcrumbs"} /-->

<!-- wp:acf/vf-container-wp-groups-header {"id":"' . uniqid('block_') . '","name":"acf/vf-container-wp-groups-header"} /-->

<!-- wp:acf/vf-container-page-template {"id":"' . uniqid('block_') . '","name":"acf/vf-container-page-template"} /-->

<!-- wp:acf/vf-container-global-footer {"id":"' . uniqid('block_') . '","name":"acf/vf-container-global-footer"} /-->

<!-- wp:acf/vf-container-ebi-global-footer {"id":"' . uniqid('block_') . '","name":"acf/vf-container-ebi-global-footer"} /-->


';
    $post_content = apply_filters(
      'vf/templates/post_content/front_page',
      $post_content
    );
    return $post_content;
  }

  /**
   * Return new block template for Gutenberg editor
   */
  static public function new_template_blocks() {
    return array(
      array(
        'acf/vf-container-global-header',
        array()
      ),
      array(
        'acf/vf-container-breadcrumbs',
        array()
      ),
      array(
        'acf/vf-container-page-template',
        array()
      ),
      array(
        'acf/vf-container-global-footer',
        array()
      ),
    );
   }

  

  /**
   * Reference: `get_post_type_labels`
   * https://core.trac.wordpress.org/browser/tags/5.4/src/wp-includes/post.php
   */
  static public function labels() {
    return array(
      'name'                     => _x( 'Templates', 'template type general name', 'vfwp' ),
      'singular_name'            => _x( 'Template', 'template type singular name', 'vfwp' ),
      'add_new'                  => _x( 'Add New', 'template', 'vfwp' ),
      'add_new_item'             => __( 'Add New Template', 'vfwp' ),
      'edit_item'                => __( 'Edit Template', 'vfwp' ),
      'new_item'                 => __( 'New Template', 'vfwp' ),
      'view_item'                => __( 'View Template', 'vfwp' ),
      'view_items'               => __( 'View Templates', 'vfwp' ),
      'search_items'             => __( 'Search Templates', 'vfwp' ),
      'not_found'                => __( 'No templates found.', 'vfwp' ),
      'not_found_in_trash'       => __( 'No templates found in Trash.', 'vfwp' ),
      'parent_item_colon'        => __( 'Parent Page:', 'vfwp' ),
      'all_items'                => __( 'All Templates', 'vfwp' ),
      'archives'                 => __( 'Template Archives', 'vfwp' ),
      'attributes'               => __( 'Template Attributes', 'vfwp' ),
      'insert_into_item'         => __( 'Insert into template', 'vfwp' ),
      'uploaded_to_this_item'    => __( 'Uploaded to this template', 'vfwp' ),
      'featured_image'           => _x( 'Featured image', 'template', 'vfwp' ),
      'set_featured_image'       => _x( 'Set featured image', 'template', 'vfwp' ),
      'remove_featured_image'    => _x( 'Remove featured image', 'template', 'vfwp' ),
      'use_featured_image'       => _x( 'Use as featured image', 'template', 'vfwp' ),
      'filter_items_list'        => __( 'Filter templates list', 'vfwp' ),
      'items_list_navigation'    => __( 'Templates list navigation', 'vfwp' ),
      'items_list'               => __( 'Templates list', 'vfwp' ),
      'item_published'           => __( 'Template published.', 'vfwp' ),
      'item_published_privately' => __( 'Template published privately.', 'vfwp' ),
      'item_reverted_to_draft'   => __( 'Template reverted to draft.', 'vfwp' ),
      'item_scheduled'           => __( 'Template scheduled.', 'vfwp' ),
      'item_updated'             => __( 'Template updated.', 'vfwp' ),
    );
  }

  public function initialize() {
    // Add hooks
    add_action(
      'plugins_loaded',
      array($this, 'plugins_loaded')
    );
    add_action(
      'after_setup_theme',
      array($this, 'after_setup_theme')
    );
    add_action(
      'after_switch_theme',
      array($this, 'after_switch_theme')
    );
    add_filter(
      'user_has_cap',
      array($this, 'user_has_cap'),
      10, 3
    );
    add_filter(
      'display_post_states',
      array($this, 'display_post_states'),
      10, 2
    );
    add_filter(
      'theme_page_templates',
      array($this, 'theme_page_templates'),
      999, 1
    );
    add_action(
      'vf_header',
      array($this, 'vf_header')
    );
    add_action(
      'vf_footer',
      array($this, 'vf_footer')
    );
  }

  public function activate() {
    $this->setup_default_template();
    $this->setup_front_page_template();
  }

  public function deactivate() {
    // Do nothing...
  }

  /**
   * Setup default template after plugin activation
   */
  public function setup_default_template() {
    $post = $this->get_template_post('default');
    if ($post) {
      if (has_blocks($post)) {
        return;
      }
      wp_delete_post($post->ID, true);
    }
    // Insert new default post
    $post_content = VF_Templates::default_template();
    $post_content = trim($post_content) . "\n";
    $post = get_post(
        wp_insert_post(array(
        'post_author'  => 1,
        'post_name'    => 'default',
        'post_title'   => __('Default', 'theme'),
        'post_type'    => VF_Templates::type(),
        'post_content' => $post_content,
        'post_status'  => 'publish'
      ), true)
    );
  }

    /**
   * Setup front page template after plugin activation
   */
  public function setup_front_page_template() {
    $post = $this->get_template_post('front_page');
    if ($post) {
      if (has_blocks($post)) {
        return;
      }
      wp_delete_post($post->ID, true);
    }
    // Insert new front_page post
    $post_content = VF_Templates::front_page_template();
    $post_content = trim($post_content) . "\n";
    $post = get_post(
        wp_insert_post(array(
        'post_author'  => 1,
        'post_name'    => 'front_page',
        'post_title'   => __('Front page', 'theme'),
        'post_type'    => VF_Templates::type(),
        'post_content' => $post_content,
        'post_status'  => 'publish'
      ), true)
    );
  }

  /**
   * Action: `plugins_loaded`
   */
  public function plugins_loaded() {
    // Register the placeholder container plugin
    $placeholder = new VF_Container_Placeholder(
      array('init' => true)
    );
  }

  /**
   * Action: `after_setup_theme`
   * https://developer.wordpress.org/reference/functions/register_post_type/
   */
  public function after_setup_theme() {
    register_post_type(VF_Templates::type(),array(
      'labels'              => VF_Templates::labels(),
      'description'         => __('Theme Templates', 'vfwp'),
      'public'              => false,
      'hierarchical'        => false,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => false,
      'show_in_admin_bar'   => true,
      'show_in_rest'        => true,
      'rest_base'           => VF_Templates::type() . 's',
      'menu_position'       => 40,
      'menu_icon'           => 'dashicons-layout',
      'capability_type'     => 'page',
      'supports'            => array('title', 'editor'),
      'has_archive'         => false,
      'rewrite'             => false,
      'query_var'           => true,
      'can_export'          => true,
      'delete_with_user'    => false,
    ));

    // Set default Gutenberg template
    $post_type_object = get_post_type_object(VF_Templates::type());
    if ($post_type_object) {
      $post_type_object->template = VF_Templates::new_template_blocks();
    }
  }

  /**
   * Action: `after_switch_theme`
   */
  public function after_switch_theme() {
    $this->setup_default_template();
  }

  /**
   * Filter: `user_has_cap`
   * Do not allow "Default" template to be deleted by user
   */
  public function user_has_cap($allcaps, $cap, $args) {
    if (count($args) > 2 && preg_match('#^delete_#', $args[0])) {
      $post = get_post($args[2]);
      if ($this->is_template_post($post, 'default')) {
        $allcaps[$cap[0]] = false;
      }
      else if ($this->is_template_post($post, 'front_page')) {
        $allcaps[$cap[0]] = false;
      }
    }
    return $allcaps;
  }

  /**
   * Filter: `display_post_states`
   */
  public function display_post_states($post_states, $post) {
    if ($this->is_template_post($post, 'default')) {
      $post_states[] = __('Default template', 'vfwp');
    }
    if ($this->is_template_post($post, 'front_page')) {
      $post_states[] = __('Front page template', 'vfwp');
    }
    return $post_states;
  }

  /**
   * Action: `theme_page_templates`
   * Prepend templates to the "Page Attributes" option
   */
  public function theme_page_templates($page_templates) {
    // Label pre-defined templates provide by the child theme
    $templates = array();
    foreach ($page_templates as $key => $value) {
      $templates[$key] = "${value} (theme)";
    }
    // Get dynamic templates
    $dynamic = array();
    $post_type = VF_Templates::type();
    $query = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type'      => $post_type,
      'post_status'    => 'publish'
    ));
    if ($query->post_count > 0) {
      foreach ($query->posts as $post) {
        if ($post->post_name === 'default') {
          continue;
        }
        $dynamic["{$post_type}_{$post->post_name}.php"] = "{$post->post_title}";
      }
    }
    return array_merge($dynamic, $templates);
  }

  /**
   * Return and validate template post
   */
  public function get_template_post($post_name) {
    $query = new WP_Query(array(
      'posts_per_page' => 1,
      'post_type'      => VF_Templates::type(),
      'post_name__in'  => array($post_name),
      'post_status'    => 'publish'
    ));
    if ($query->post_count === 0) {
      return null;
    }
    return $query->posts[0];
  }

  /**
   * Return array of `VF_Plugin` names in the template post content
   */
  public function get_template_plugins($template) {
    if ( ! $this->is_template_post($template)) {
      return array();
    }
    if ( ! has_blocks($template)) {
      return array();
    }
    $containers = array();
    $blocks = parse_blocks($template->post_content);
    foreach ($blocks as $i => $block) {
      if ( ! $block['blockName']) {
        continue;
      }
      $containers[] = VF_Containers::name_block_to_post(
        $block['blockName']
      );
    }
    return $containers;
  }

  /**
   * Return array of `VF_Plugin` names for current template hierarchy
   */
  public function get_template_containers() {
    // Defer to "default" template
    $template = $this->get_template_post('default');
    // Attempt to find template from post attribute
    global $post;
    if ($post instanceof WP_Post) {
      $template_slug = get_page_template_slug($post);
      if (
        preg_match(
          '#^' . preg_quote(VF_Templates::type()) .  '_(.*?)\.php#',
          $template_slug, $matches
        ) === 1
      ) {
        $alternate = $this->get_template_post($matches[1]);
        if ($alternate) {
          $template = $alternate;
        }
      }
    }
    return $this->get_template_plugins($template);
  }

  /**
   * Action: `vf_header`
   * Render template containers ABOVE the page template
   */
  public function vf_header() {
    $containers = $this->get_template_containers();
    $offset = array_search('vf_page_template', $containers);
    if ($offset !== false) {
      $containers = array_slice($containers, 0, $offset);
      $this->render_containers($containers);
    }
  }

  /**
   * Action: `vf_footer`
   * Render template containers BELOW the page template
   */
  public function vf_footer() {
    $containers = $this->get_template_containers();
    $offset = array_search('vf_page_template', $containers);
    if ($offset !== false) {
      $containers = array_slice($containers, $offset + 1);
      $this->render_containers($containers);
    }
  }

  /**
   * Shared method for `vf_header` and `vf_footer`
   */
  private function render_containers($containers) {
    foreach ($containers as $post_name) {
      if ($post_name === 'vf_page_template') {
        continue;
      }
      $container = VF_Plugin::get_plugin($post_name);
      VF_Plugin::render($container);
    }
  }

} // VF_Templates

endif;

?>
