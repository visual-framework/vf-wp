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

  static public function default_blocks() {
    return array(
      array(
        'vf/container-page-template',
        array()
      ),
    );
  }

  // public function default_content() {
  //   return '<!-- wp:vf/container-page-template {"ver":"1.0.0","defaults":1} /-->' + "\n";
  // }

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
      'init',
      array($this, 'init')
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
    add_action(
      'admin_print_footer_scripts',
      array($this, 'admin_print_footer_scripts'),
      100
    );
  }

  /**
   * Action: `init`
   * Register custom post type
   * https://developer.wordpress.org/reference/functions/register_post_type/
   */
  public function init() {
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

    // Register the placeholder container plugin
    $placeholder = new VF_Container_Placeholder(
      array('init' => true)
    );

    // Set default Gutenberg template
    $post_type_object = get_post_type_object(VF_Templates::type());
    if ($post_type_object) {
      $post_type_object->template = VF_Templates::default_blocks();
    }
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
   * Return array of `VF_Plugin` names in the template post content
   */
  public function get_template_plugins($template) {
    $containers = array();
    if ( ! $template instanceof WP_Post) {
      return $containers;
    }
    $blocks = parse_blocks($template->post_content);
    foreach ($blocks as $i => $block) {
      if ( ! $block['blockName']) {
        continue;
      }
      $containers[] = VF_Gutenberg::name_block_to_post(
        $block['blockName']
      );
    }
    return $containers;
  }

  /**
   * Return array of `VF_Plugin` names for current template hierarchy
   */
  public function get_template_containers() {
    $post_name = 'default';
    global $post;
    if ($post instanceof WP_Post) {
      $template_slug = get_page_template_slug($post);
      if (
        preg_match(
          '#^' . preg_quote(VF_Templates::type()) .  '_(.*?)\.php#',
          $template_slug,
          $matches
        ) === 1
      ) {
        $post_name = $matches[1];
      }
    }
    $query = new WP_Query(array(
      'posts_per_page' => 1,
      'post_type'      => VF_Templates::type(),
      'post_name__in'  => array($post_name)
    ));
    if ($query->post_count === 0) {
      return array();
    }
    if ( ! has_blocks($query->posts[0])) {
      return array();
    }
    return $this->get_template_plugins($query->posts[0]);
  }

  /**
   * Action: `vf_header`
   * Render template containers ABOVE the page template
   */
  public function vf_header() {
    $containers = $this->get_template_containers();
    $offset = array_search('vf_page_template', $containers);
    if ($offset === false) {
      return;
    }
    $containers = array_slice($containers, 0, $offset);
    foreach ($containers as $post_name) {
      $container = VF_Plugin::get_plugin($post_name);
      VF_Plugin::render($container);
    }
  }

  /**
   * Action: `vf_footer`
   * Render template containers BELOW the page template
   */
  public function vf_footer() {
    $containers = $this->get_template_containers();
    $offset = array_search('vf_page_template', $containers);
    if ($offset === false) {
      return;
    }
    $containers = array_slice($containers, $offset + 1);
    foreach ($containers as $post_name) {
      $container = VF_Plugin::get_plugin($post_name);
      VF_Plugin::render($container);
    }
  }

  /**
   * Only allow container blocks in the "Template" post type
   */
  public function admin_print_footer_scripts() {
    $screen = get_current_screen();
    if (
      ! $screen ||
        $screen->parent_base !== 'edit' ||
        $screen->post_type !== VF_Templates::type()
    ) {
      return;
    }
    $category = VF_Containers::block_category();
?>
<script type="text/javascript">
(function() {
  const onReady = () => {
    if (typeof wp !== 'object') {
      return;
    }
    wp.domReady(() => {
      if (typeof wp.blocks !== 'object') {
        return;
      }
      const blocks = wp.blocks.getBlockTypes();
      blocks.forEach(block => {
        // Disable non-containers
        if (block.category !== '<?php echo $category; ?>') {
          wp.blocks.unregisterBlockType(block.name);
          return;
        }
        // Enable containers
        block.supports.inserter = true;
      });
    });
  };
  document.addEventListener(
    'DOMContentLoaded',
    onReady
  );
})();
</script>
<?php
  }

} // VF_Templates

endif;

?>
