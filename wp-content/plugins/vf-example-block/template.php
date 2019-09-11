<?php

global $post;

$text = get_field('vf_example_text', $post->ID);
$textarea = get_field('vf_example_textarea', $post->ID);
$select = get_field('vf_example_select', $post->ID);
$range = get_field('vf_example_range', $post->ID);
$radio = get_field('vf_example_radio', $post->ID);

?>
<div class="vf-content">
  <h3>Example Block plugin</h3>
  <ul>
    <li><b><?php _e('Text:', 'vfwp'); ?></b> <?php echo $text; ?></li>
    <li><b><?php _e('Textarea:', 'vfwp'); ?></b> <?php echo $textarea; ?></li>
    <li><b><?php _e('Select:', 'vfwp'); ?></b> <?php echo $select; ?></li>
    <li><b><?php _e('Range:', 'vfwp'); ?></b> <?php echo $range; ?></li>
    <li><b><?php _e('Radio:', 'vfwp'); ?></b> <?php echo $radio; ?></li>
  </ul>
</div>
