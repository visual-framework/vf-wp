<?php
// Standalone fixture exercising the real renderer without a WordPress database.
define('ABSPATH', __DIR__);
function get_stylesheet_directory() { return dirname(__DIR__, 2) . '/wp-content/themes/vf-wp-ells'; }
function get_stylesheet_directory_uri() { return '/wp-content/themes/vf-wp-ells'; }
function add_action() {}
function add_shortcode() {}
function get_option($name, $default = false) { return $default; }
function wp_enqueue_style() {}
function wp_enqueue_script() {}
function shortcode_atts($defaults, $atts) { return array_merge($defaults, $atts); }
function wp_unique_id($prefix) { static $n = 0; return $prefix . ++$n; }
function esc_attr($s) { return htmlspecialchars((string) $s, ENT_QUOTES); }
function esc_html($s) { return esc_attr($s); }
function esc_url($s) { return esc_attr($s); }
function __($s, $domain) { return $s; }
function esc_html__($s, $domain) { return esc_html($s); }
function esc_attr_e($s, $domain) { echo esc_attr($s); }
function esc_html_e($s, $domain) { echo esc_html($s); }
function add_query_arg($key, $value, $url) { return $url . '?' . $key . '=' . $value; }
require get_stylesheet_directory() . '/functions/interactive-activities.php';
foreach (array('../epigenetic-clock', 'https://example.com', '', 'missing-case', 'epigenetic-clock/step-1.html') as $invalid) {
    if (vf_ells_interactive_case($invalid) !== false) { throw new Exception('Invalid case accepted'); }
}
?>
<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>ELLS activity QA</title>
<link rel="stylesheet" href="/wp-content/themes/vf-wp-ells/interactive/assets/player.css">
<style>body { margin: 24px auto; max-width: 1000px; padding: 0 12px; font-family: Arial, sans-serif; } body > button { background: pink; }</style>
<button id="outside">Outside theme control</button>
<?php echo vf_ells_interactive_shortcode(array('case' => 'epigenetic-clock')); ?>
<?php echo vf_ells_interactive_shortcode(array('case' => 'epigenetic-clock')); ?>
<script src="/wp-content/themes/vf-wp-ells/interactive/assets/player.js"></script></html>
