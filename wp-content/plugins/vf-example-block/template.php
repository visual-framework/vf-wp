<?php

$acf_id = isset($acf_id) ? $acf_id : false;

$text = get_field('vf_example_text', $acf_id);
$textarea = get_field('vf_example_textarea', $acf_id);
$select = get_field('vf_example_select', $acf_id);
$range = get_field('vf_example_range', $acf_id);
$radio = get_field('vf_example_radio', $acf_id);
$checkbox = get_field('vf_example_checkbox', $acf_id);
$boolean = get_field('vf_example_boolean', $acf_id);
$date = get_field('vf_example_date', $acf_id);
$taxonomy = get_field('vf_example_taxonomy', $acf_id);
$number = get_field('vf_example_number', $acf_id);
$email = get_field('vf_example_email', $acf_id);
$url = get_field('vf_example_url', $acf_id);
$rich = get_field('vf_example_rich', $acf_id);

$checkbox = is_array($checkbox) ? $checkbox : array();

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
