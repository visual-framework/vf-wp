<?php

$text = get_field('vf_example_text');
$select = get_field('vf_example_select');
$range = get_field('vf_example_range');

?>
<div class="vf-content">
  <h3>Example Block plugin</h3>
  <ul>
    <li><b><?php _e('Text field:', 'vfwp'); ?></b> <?php echo $text; ?></li>
    <li><b><?php _e('Select field:', 'vfwp'); ?></b> <?php echo $select; ?></li>
    <li><b><?php _e('Range field:', 'vfwp'); ?></b> <?php echo $range; ?></li>
  </ul>
</div>
