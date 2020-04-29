<?php

global $post;

$text = get_field('vf_example_text');
$textarea = get_field('vf_example_textarea');
$select = get_field('vf_example_select');
$range = get_field('vf_example_range');
$radio = get_field('vf_example_radio');
$checkbox = get_field('vf_example_checkbox');
$boolean = get_field('vf_example_boolean');
$date = get_field('vf_example_date');
$taxonomy = get_field('vf_example_taxonomy');
$checkbox = is_array($checkbox) ? $checkbox : array();
$number = get_field('vf_example_number');
$email = get_field('vf_example_email');
$url = get_field('vf_example_url');
$rich = get_field('vf_example_rich');

?>
<div class="vf-content">
  <h3>Example Block plugin</h3>
  <ul>
    <li><b><?php _e('Text:', 'vfwp'); ?></b> <?php echo $text; ?></li>
    <li><b><?php _e('Textarea:', 'vfwp'); ?></b> <?php echo $textarea; ?></li>
    <li><b><?php _e('Select:', 'vfwp'); ?></b> <?php echo $select; ?></li>
    <li><b><?php _e('Range:', 'vfwp'); ?></b> <?php echo $range; ?></li>
    <li><b><?php _e('Radio:', 'vfwp'); ?></b> <?php echo $radio; ?></li>
    <li><b><?php _e('Checkbox:', 'vfwp'); ?></b> <?php echo implode(' ', $checkbox); ?></li>
    <li><b><?php _e('Boolean:', 'vfwp'); ?></b> <?php echo $boolean ? '✔' : '✖'; ?></li>
    <li><b><?php _e('Date:', 'vfwp'); ?></b> <?php echo $date; ?></li>
    <li><b><?php _e('Taxonomy:', 'vfwp'); ?></b> <?php echo $taxonomy; ?></li>
    <li><b><?php _e('Number:', 'vfwp'); ?></b> <?php echo $number; ?></li>
    <li><b><?php _e('Email:', 'vfwp'); ?></b> <?php echo $email; ?></li>
    <li><b><?php _e('URL:', 'vfwp'); ?></b> <?php echo $url; ?></li>
    <li><b><?php _e('Rich:', 'vfwp'); ?></b> <?php echo $rich; ?></li>
  </ul>
</div>
