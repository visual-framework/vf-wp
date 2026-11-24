<?php
// Run with WP-CLI eval-file on an isolated/local ELLS WordPress install.
function ells_case_assert($condition, $message) {
    if (!$condition) throw new RuntimeException($message);
}
$users = get_users(array('role' => 'administrator', 'number' => 1));
wp_set_current_user($users[0]->ID);
$id = wp_insert_post(array('post_type' => 'ells_case', 'post_status' => 'draft', 'post_title' => 'ELLS permission test'));
try {
    do_action('load-admin_page_ells-interactive-preview');
    ells_case_assert($GLOBALS['title'] === 'Activity preview', 'Hidden preview pages need an explicit admin title');
    $raw = '<html><head><base href="https://example.com"><meta http-equiv="refresh" content="0;url=https://example.com"></head><body><nav class="embl-progress-tracker">Legacy</nav><script>document.querySelectorAll(\'.embl-progress-tracker\');</script><script>var answer = "a\\\\b";</script><p>Exercise</p></body></html>';
    $html = vf_ells_interactive_prepare_html($raw);
    ells_case_assert(!is_wp_error($html) && strpos($html, '<nav') === false && strpos($html, '<base') === false && strpos($html, 'http-equiv="refresh"') === false, 'Legacy scaffolding should be removed');
    $steps = array(array('title' => 'First', 'html' => $html, 'filename' => 'step.html'));
    update_post_meta($id, '_ells_case_steps', wp_slash($steps));
    ells_case_assert(get_post_meta($id, '_ells_case_steps', true) === $steps, 'JavaScript backslashes must survive storage');
    $_POST = array('ells_steps' => array(array('title' => 'Unauthorized', 'existing' => '0')));
    do_action('save_post_ells_case', $id);
    ells_case_assert(get_post_meta($id, '_ells_case_steps', true) === $steps, 'Missing nonce must not save');
    wp_set_current_user(0);
    $_POST['ells_case_nonce'] = wp_create_nonce('ells_case_save');
    do_action('save_post_ells_case', $id);
    ells_case_assert(get_post_meta($id, '_ells_case_steps', true) === $steps, 'Unauthorized user must not save');
    ells_case_assert(vf_ells_interactive_case('uploaded-' . $id) === false, 'Draft should be private');
    wp_set_current_user($users[0]->ID);
    $_POST['ells_case_nonce'] = wp_create_nonce('ells_case_save');
    do_action('save_post_ells_case', $id);
    ells_case_assert(get_post_meta($id, '_ells_case_steps', true)[0]['title'] === 'Unauthorized', 'Authorized edit should save');
    $_POST = array();
    wp_update_post(array('ID' => $id, 'post_status' => 'publish'));
    wp_set_current_user(0);
    $output = do_shortcode('[ells_interactive case="uploaded-' . $id . '"]');
    ells_case_assert(strpos($output, 'srcdoc=') !== false && strpos($output, 'sandbox="allow-scripts"') !== false && strpos($output, 'Content-Security-Policy') !== false, 'Published uploads need sandbox and CSP');
    wp_trash_post($id);
    ells_case_assert(vf_ells_interactive_case('uploaded-' . $id) === false, 'Trashed cases must be unavailable');
    echo "PASS: nonce and capability checks, draft/trash privacy, published rendering, JS storage, import normalization, sandbox and CSP.\n";
} finally {
    $_POST = array();
    wp_delete_post($id, true);
}
