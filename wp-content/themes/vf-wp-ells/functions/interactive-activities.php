<?php
/** Manifest-driven, isolated HTML activities for the ELLS child theme. */
defined('ABSPATH') || exit;

function vf_ells_interactive_case($slug) {
    if (is_string($slug) && preg_match('/^uploaded-([1-9][0-9]*)$/D', $slug, $matches)) {
        $post = get_post((int) $matches[1]);
        if (!$post || $post->post_type !== 'ells_case' || ($post->post_status !== 'publish' && !current_user_can('edit_post', $post->ID))) return false;
        if (!empty($post->post_password) && !current_user_can('edit_post', $post->ID) && post_password_required($post)) return false;
        $steps = get_post_meta($post->ID, '_ells_case_steps', true);
        if (!is_array($steps) || !$steps) return false;
        foreach ($steps as $step) {
            if (!is_array($step) || !isset($step['title'], $step['html']) || !is_string($step['title']) || !is_string($step['html'])) return false;
        }
        return array('version' => 1, 'title' => get_the_title($post), 'steps' => $steps, 'uploaded' => true);
    }
    $imports = get_option('ells_interactive_packaged_cases', array());
    if (is_string($slug) && is_array($imports) && isset($imports[$slug])) {
        return vf_ells_interactive_case('uploaded-' . (int) $imports[$slug]);
    }
    // Packaged cases accept a slug, never a URL or path.
    if (!is_string($slug) || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $slug)) return false;
    $base = get_stylesheet_directory() . '/interactive/cases/' . $slug . '/';
    if (!is_readable($base . 'case.json')) return false;
    $case = json_decode(file_get_contents($base . 'case.json'), true);
    if (!is_array($case) || !isset($case['version'], $case['title'], $case['steps']) || $case['version'] !== 1 || !is_string($case['title']) || !is_array($case['steps']) || !$case['steps']) return false;
    foreach ($case['steps'] as $step) {
        if (!is_array($step) || empty($step['title']) || !is_string($step['title']) || empty($step['file']) || !is_string($step['file']) || !preg_match('/^[a-z0-9-]+\.html$/D', $step['file']) || !is_readable($base . $step['file'])) return false;
    }
    return $case;
}

function vf_ells_interactive_assets() {
    $path = get_stylesheet_directory() . '/interactive/assets/';
    $url = get_stylesheet_directory_uri() . '/interactive/assets/';
    wp_enqueue_style('ells-interactive', $url . 'player.css', array(), filemtime($path . 'player.css'));
    wp_enqueue_script('ells-interactive', $url . 'player.js', array(), filemtime($path . 'player.js'), true);
}

function vf_ells_interactive_page_assets() {
    $post = get_post();
    if ($post && has_shortcode($post->post_content, 'ells_interactive')) vf_ells_interactive_assets();
}
add_action('wp_enqueue_scripts', 'vf_ells_interactive_page_assets');

function vf_ells_interactive_shortcode($atts) {
    $atts = shortcode_atts(array('case' => ''), $atts, 'ells_interactive');
    $case = vf_ells_interactive_case($atts['case']);
    if (!$case) return '<p class="ells-interactive-error">' . esc_html__('This interactive activity is unavailable.', 'vf-wp-ells') . '</p>';
    vf_ells_interactive_assets();
    $id = wp_unique_id('ells-interactive-');
    $url = get_stylesheet_directory_uri() . '/interactive/cases/' . $atts['case'] . '/';
    $base = get_stylesheet_directory() . '/interactive/cases/' . $atts['case'] . '/';
    ob_start();
    ?>
    <section class="ells-interactive" data-ells-interactive aria-labelledby="<?php echo esc_attr($id); ?>-title">
        <header class="ells-interactive__header">
            <h2 id="<?php echo esc_attr($id); ?>-title"><?php echo esc_html($case['title']); ?></h2>
            <p data-activity-status aria-live="polite"></p>
            <nav aria-label="<?php esc_attr_e('Activity steps', 'vf-wp-ells'); ?>" data-activity-nav hidden>
                <?php foreach ($case['steps'] as $index => $step) : ?>
                    <button type="button" data-step="<?php echo esc_attr($index); ?>" aria-controls="<?php echo esc_attr($id . '-step-' . $index); ?>"><?php echo esc_html(sprintf(__('Step %d', 'vf-wp-ells'), $index + 1)); ?></button>
                <?php endforeach; ?>
            </nav>
        </header>
        <?php foreach ($case['steps'] as $index => $step) : ?>
            <div class="ells-interactive__panel" id="<?php echo esc_attr($id . '-step-' . $index); ?>" data-activity-panel>
                <h3 tabindex="-1"><?php echo esc_html($step['title']); ?></h3>
                <?php if (!empty($case['uploaded'])) : ?>
                    <?php // srcdoc parses twice, so encode existing HTML entities too. ?>
                    <iframe data-activity-frame title="<?php echo esc_attr($step['title']); ?>" srcdoc="<?php echo htmlspecialchars(vf_ells_interactive_upload_document($step['html']), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" sandbox="allow-scripts" referrerpolicy="no-referrer" height="700"></iframe>
                <?php else : ?>
                    <iframe data-activity-frame title="<?php echo esc_attr($step['title']); ?>" src="<?php echo esc_url(add_query_arg('ver', filemtime($base . $step['file']), $url . $step['file'])); ?>" sandbox="allow-scripts" referrerpolicy="no-referrer" height="700"></iframe>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <footer class="ells-interactive__footer" data-activity-controls hidden>
            <button type="button" data-activity-back><?php esc_html_e('Back', 'vf-wp-ells'); ?></button>
            <button type="button" data-activity-next><?php esc_html_e('Next', 'vf-wp-ells'); ?></button>
        </footer>
        <noscript><p><?php esc_html_e('Enable JavaScript to use the interactive exercises and step navigation.', 'vf-wp-ells'); ?></p></noscript>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('ells_interactive', 'vf_ells_interactive_shortcode');
