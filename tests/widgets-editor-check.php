<?php
// WP-CLI eval-file: VF editor scripts must work in all current editor contexts.
global $vf_wp, $wp_scripts;
require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
require_once ABSPATH . 'wp-admin/includes/screen.php';
set_current_screen('widgets');
foreach (array('wp-edit-widgets', 'wp-customize-widgets', 'wp-edit-post') as $editor) {
    $wp_scripts = new WP_Scripts();
    $vf_wp->enqueue_block_editor_assets();
    wp_enqueue_script($editor);
    $wp_scripts->all_deps($wp_scripts->queue);
    if ($editor !== 'wp-edit-post' && in_array('wp-editor', $wp_scripts->to_do, true)) throw new RuntimeException('Legacy wp-editor loaded in ' . $editor);
    foreach (array('vf-plugin', 'wp-block-editor', 'wp-blocks', 'wp-hooks', 'wp-dom-ready', 'jquery') as $required) {
        if (!in_array($required, $wp_scripts->to_do, true)) throw new RuntimeException('Missing dependency: ' . $required);
    }
    echo 'PASS: ' . $editor . " resolves VF dependencies" . ($editor === 'wp-edit-post' ? '.' : ' without legacy wp-editor.') . "\n";
}
