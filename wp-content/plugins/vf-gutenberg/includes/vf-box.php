<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('VF_Gutenberg_Box') ) :

class VF_Gutenberg_Box implements VF_Gutenberg_Block {

  function key() {
    return 'vf-gutenberg-box';
  }

  function title() {
    return __('VF Box', 'vfwp');
  }

  function render(array $block) {
    $variant = get_field('variant');
    $heading = get_field('heading');
    $content = get_field('content');

    $heading = trim(strip_tags($heading));
    $content = trim(strip_tags($content));

    $classes = array('vf-box');
    if ( ! empty($variant)) {
      $classes[] = "vf-box--{$variant}";
    }
    $classes = esc_attr(implode(' ', $classes));

    ob_start();
?>
<div class="<?php echo $classes; ?>">
<?php
    if ( ! empty($heading)) {
      $heading = esc_html($heading);
?>
  <h2 class="vf-box__heading"><?php echo $heading; ?></h2>
<?php
    }
    if ( ! empty($content)) {
      $content = nl2br(esc_html($content));
?>
  <p class="vf-box__text"><?php echo $content; ?></p>
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
        'key' => "{$prefix}_variant",
        'label' => 'Variant',
        'name' => 'variant',
        'type' => 'select',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array(
          'box' => 'Box',
          'factoid' => 'Factoid',
          'inlay' => 'Inlay',
        ),
        'default_value' => array(
          0 => 'box',
        ),
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'return_format' => 'value',
        'ajax' => 0,
        'placeholder' => '',
      ),
      array(
        'key' => "{$prefix}_heading",
        'label' => 'Heading',
        'name' => 'heading',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => "{$prefix}_content",
        'label' => 'Content',
        'name' => 'content',
        'type' => 'textarea',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'maxlength' => '',
        'rows' => 4,
        'new_lines' => '',
      ),
    );
  }

} // VF_Gutenberg_Box

vf_gutenberg()->add_block( new VF_Gutenberg_Box() );

endif;

?>
