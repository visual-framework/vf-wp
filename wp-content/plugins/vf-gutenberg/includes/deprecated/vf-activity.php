<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Activity') ) :

class VF_Gutenberg_Activity implements VF_Gutenberg_Block {

  function __construct() {
    $callback = array($this, 'admin_head');
    if ( ! has_filter('action_head', $callback)) {
      add_action('admin_head', $callback);
    }
  }

  function key() {
    return 'vf-gutenberg-activity';
  }

  function title() {
    return __('VF Activity List', 'vfwp');
  }

  function render(array $block) {

    $classes = array('vf-activity');
    $classes = esc_attr(implode(' ', $classes));

    $date = get_field('date');

    ob_start();
?>
<div class="<?php echo $classes; ?>">
  <p class="vf-activity__date"><?php echo esc_html($date); ?></p>
  <?php if (have_rows('items')) { ?>
  <ul class="vf-activity__list | vf-list">
  <?php
  while (have_rows('items')) {
    the_row();

    $description = get_sub_field('description');
    $description = wp_kses($description, $this->allowed_html());
    $description = trim($description);

    if (vf_html_empty($description)) {
      continue;
    }

    echo "<li class=\"vf-activity__item\">{$description}";

    $details = get_sub_field('details');
    $details = wp_kses($details, $this->allowed_html());
    $details = trim($details);

    if ( ! vf_html_empty($details)) {
      echo "<blockquote class=\"vf-activity__blockquote | vf-blockquote\">{$details}</blockquote>";
    }

    echo "</li>";
  }
  ?>
  </ul>
  <?php } ?>
</div>
<?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  function fields() {
    $prefix = "field_{$this->key()}";
    return array(
      array(
        'key' => "{$prefix}_date",
        'label' => __('Date', 'vfwp'),
        'name' => 'date',
        'type' => 'date_picker',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'display_format' => 'l jS F Y',
        'return_format' => 'l jS F Y',
        'first_day' => 1,
      ),
      array(
        'key' => "{$prefix}_items",
        'label' => __('Activities', 'vfwp'),
        'name' => 'items',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'collapsed' => '',
        'min' => 0,
        'max' => 0,
        'layout' => 'block',
        'button_label' => 'New Item',
        'sub_fields' => array(
          array(
            'key' => "{$prefix}_description",
            'label' => __('Description', 'vfwp'),
            'name' => 'description',
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
          array(
            'key' => "{$prefix}_details",
            'label' => __('Additional Details', 'vfwp'),
            'name' => 'details',
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
        ),
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
      'Insert'
    );
    $acf_field = '.acf-field[data-key^="field_vf-gutenberg-activity"]';
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

} // VF_Gutenberg_Activity

vf_gutenberg()->_deprecated_add_block( new VF_Gutenberg_Activity() );

endif;

?>
