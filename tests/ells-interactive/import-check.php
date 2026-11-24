<?php
// Imports the real packaged case into the local admin as requested; leaves it there.
$admins = get_users(array('role' => 'administrator', 'number' => 1));
wp_set_current_user($admins[0]->ID);
$_POST = array();
vf_ells_interactive_import_packaged_cases();
$imports = get_option('ells_interactive_packaged_cases', array());
$id = isset($imports['epigenetic-clock']) ? (int) $imports['epigenetic-clock'] : 0;
if (!$id || get_post_type($id) !== 'ells_case' || get_post_status($id) !== 'publish') throw new RuntimeException('Expected a published admin case');
$before = get_post_meta($id, '_ells_case_steps', true);
if (count($before) !== 4 || vf_ells_interactive_case_key($id) !== 'epigenetic-clock') throw new RuntimeException('Expected four editable steps and the original shortcode');
vf_ells_interactive_import_packaged_cases();
if ($imports !== get_option('ells_interactive_packaged_cases') || $before !== get_post_meta($id, '_ells_case_steps', true)) throw new RuntimeException('Repeated imports must preserve the case');
$duplicates = get_posts(array('post_type' => 'ells_case', 'post_status' => array_keys(get_post_stati()), 'meta_key' => '_ells_case_package', 'meta_value' => 'epigenetic-clock', 'numberposts' => -1, 'fields' => 'ids', 'suppress_filters' => true));
if (count($duplicates) !== 1) throw new RuntimeException('Duplicate case imported');
wp_set_current_user(0);
$case = vf_ells_interactive_case('epigenetic-clock');
if (!$case || empty($case['uploaded']) || count($case['steps']) !== 4) throw new RuntimeException('Original shortcode must resolve to the admin version');
$html = do_shortcode('[ells_interactive case="epigenetic-clock"]');
if (strpos($html, 'srcdoc=') === false) throw new RuntimeException('Expected the managed case player');
echo "PASS: packaged case visible as published admin entry #$id, four editable steps, original shortcode, repeat import preserves content and avoids duplicates.\n";
