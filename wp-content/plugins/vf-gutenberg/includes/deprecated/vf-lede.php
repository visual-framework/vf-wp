<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Lede') ) :

class VF_Gutenberg_Lede implements VF_Gutenberg_Block {

  function __construct() {
    $callback = array($this, 'admin_head');
    if ( ! has_filter('action_head', $callback)) {
      add_action('admin_head', $callback);
    }
  }

  function key() {
    return 'vf-gutenberg-lede';
  }

  function title() {
    return __('VF Lede', 'vfwp');
  }

  function render(array $block) {
    $content = get_field('content');
    // $content = trim(strip_tags($content));
    $content = wp_kses($content, $this->allowed_html());
    $content = trim($content);

    if ( ! preg_match('#\S#', $content)) {
      $content = __('Lede paragraph.', 'vfwp');
    }

    // $content = esc_html($content);
    ob_start();
?>
<p class="vf-lede"><?php echo $content; ?></p>
<?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  function fields() {
    $prefix = "field_{$this->key()}";
    return array(
      array(
        'key' => "{$prefix}_content",
        'label' => 'Content',
        'name' => 'content',
        'type' => 'wysiwyg',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'tabs' => 'visual',
        'toolbar' => 'basic',
        'media_upload' => 0,
        'delay' => 0,
      ),
    );
  }

  /**
   * Allowed HTML tags used for:
   * https://codex.wordpress.org/Function_Reference/wp_kses
   */
  function allowed_html() {
    $arr = array();
    return array(
      'a' => array(
        'href' => $arr,
        'title' => $arr
      ),
      'span' => array(
        'style' => $arr
      ),
      'u' => $arr,
      'b' => $arr,
      'i' => $arr,
      'em' => $arr,
      'strong' => $arr,
    );
  }

  /**
   * Action: `admin_head`
   * Add CSS to hide TinyMCE buttons for unallowed HTML elements
   */
  function admin_head() {
    $labels = array(
      'Bold',
      'Italic',
      'Underline',
      'Strikethrough',
      'Insert'
    );
    $acf_field = '.acf-field[data-key="field_vf-gutenberg-lede_content"]';
    $mce_btn = $acf_field . ' .mce-toolbar .mce-btn';
?>
<style>
<?php echo $mce_btn; ?> {
  display: none;
}
<?php foreach ($labels as $label) { ?>
<?php echo $mce_btn; ?>[aria-label^="<?php echo $label; ?>"] {
  display: inline-block;
}
<?php } ?>
</style>
<?php
  }

} // VF_Gutenberg_Lede

vf_gutenberg()->_deprecated_add_block( new VF_Gutenberg_Lede() );

endif;

?>
