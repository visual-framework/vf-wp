<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Containers') ) :

/**
 * Represent a custom post type for Visual Framework containers
 */
class VF_Containers extends VF_Type {

  protected $post_type = 'vf_container';
  protected $post_type_plural = 'vf_containers';
  protected $description = 'Containers';

  protected $labels = array(
    'name'          => 'Containers',
    'singular_name' => 'Container',
    'edit_item'     => 'Edit Container'
  );

  public function initialize() {
    parent::initialize();
    add_action('vf_header', array($this, 'header_containers'));
    add_action('vf_footer', array($this, 'footer_containers'));
    add_filter(
      'block_categories',
      array($this, 'block_categories'),
      10, 2
    );
    add_action(
      'admin_print_footer_scripts',
      array($this, 'admin_print_footer_scripts'),
      100
    );
  }

  public function activate() {
    parent::activate();

    // Register Page Template container
    // This is a placeholder for the normal WordPress template
    VF_Plugin::register(array(
      'post_name'  => 'vf_page_template',
      'post_title' => 'Page Template',
      'post_type' => $this->post_type
    ));
  }

  static public function block_category() {
    return 'vf/containers';
  }

  /**
   * Action: `block_categories`
   */
  function block_categories($categories, $post) {
    return array_merge(
      array(
        array(
          'slug'  => VF_Containers::block_category(),
          'title' => __('EMBL – Containers', 'vfwp'),
          'icon'  => null
        ),
      ),
      $categories
    );
  }

  /**
   * Output all containers before the first `vf_page_template`
   * added as an action to the `vf_header` hook
   */
  static public function header_containers() {
    if ( ! have_rows('vf_containers', 'option')) {
      return;
    }
    $containers = get_field('vf_containers', 'option');
    $placeholder = array_filter($containers, function ($item) {
      return $item['vf_container_name'] === 'vf_page_template';
    });
    $total = count($containers);
    $end = count($placeholder) ? array_keys($placeholder)[0] : $total;
    for ($i = 0; $i < $end; $i++) {
      if (array_key_exists('vf_container_name', $containers[$i])) {
        $container = VF_Plugin::get_plugin($containers[$i]['vf_container_name']);
        VF_Plugin::render($container);
      }
    }
  }

  /**
   * Output all containers after the last `vf_page_template`
   * added as an action to the `vf_footer` hook
   */
  static public function footer_containers() {
    if ( ! have_rows('vf_containers', 'option')) {
      return;
    }
    $containers = get_field('vf_containers', 'option');
    $placeholder = array_filter($containers, function ($item) {
      return $item['vf_container_name'] === 'vf_page_template';
    });
    if ( ! count($placeholder)) {
      return;
    }
    $total = count($containers);
    $start = array_keys($placeholder)[count($placeholder) - 1];
    for ($i = $start; $i < count($containers); $i++) {
      if (array_key_exists('vf_container_name', $containers[$i])) {
        $container = VF_Plugin::get_plugin($containers[$i]['vf_container_name']);
        VF_Plugin::render($container);
      }
    }
  }

  /**
   * Only allow container blocks in the "Template" post type
   */
  public function admin_print_footer_scripts() {
    if ( ! class_exists('VF_Template')) {
      return;
    }
    $screen = get_current_screen();
    if (
      ! $screen ||
        $screen->parent_base !== 'edit' ||
        $screen->post_type !== VF_Template::type()
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

} // VF_Containers

endif;

?>
