<?php
/** Admin-managed HTML cases. Raw HTML stays in post meta, never public files. */
defined('ABSPATH') || exit;

function vf_ells_interactive_can_upload() {
    return current_user_can('manage_options') && current_user_can('unfiltered_html');
}

add_action('init', function () {
    register_post_type('ells_case', array(
        'labels' => array('name' => 'Interactive cases', 'singular_name' => 'Interactive case', 'menu_name' => 'Interactive cases', 'add_new_item' => 'Add interactive case', 'edit_item' => 'Edit interactive case', 'all_items' => 'All cases'),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-screenoptions',
        'supports' => array('title'),
        'capabilities' => array(
            'edit_post' => 'manage_options', 'read_post' => 'manage_options', 'delete_post' => 'manage_options',
            'edit_posts' => 'manage_options', 'edit_others_posts' => 'manage_options', 'publish_posts' => 'manage_options',
            'read_private_posts' => 'manage_options', 'delete_posts' => 'manage_options',
            'delete_private_posts' => 'manage_options', 'delete_published_posts' => 'manage_options',
            'delete_others_posts' => 'manage_options', 'edit_private_posts' => 'manage_options',
            'edit_published_posts' => 'manage_options', 'create_posts' => 'manage_options',
        ),
    ));
});

/** Remove known navigation/resize scaffolding from the supplied exports. */
function vf_ells_interactive_prepare_html($html) {
    if (!class_exists('DOMDocument')) return new WP_Error('parser', 'The PHP DOM extension is required to import HTML.');
    $previous = libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $loaded = $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NONET);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    if (!$loaded) return new WP_Error('html', 'The HTML document could not be read.');
    $xpath = new DOMXPath($dom);
    foreach ($xpath->query('//nav[contains(concat(" ", normalize-space(@class), " "), " embl-progress-tracker ")] | //base | //meta[translate(@http-equiv,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz")="refresh"]') as $node) {
        $node->parentNode->removeChild($node);
    }
    foreach (iterator_to_array($dom->getElementsByTagName('script')) as $script) {
        $code = $script->textContent;
        if (strpos($code, "querySelectorAll('.embl-progress-tracker')") !== false || strpos($code, 'iframeHeight:') !== false) $script->parentNode->removeChild($script);
    }
    foreach (iterator_to_array($dom->childNodes) as $node) {
        if ($node->nodeType === XML_PI_NODE) $dom->removeChild($node);
    }
    return $dom->saveHTML();
}

function vf_ells_interactive_upload_document($html) {
    // Opaque sandbox plus CSP keeps uploaded scripts isolated from WordPress.
    $policy = "default-src 'none'; script-src 'unsafe-inline'; style-src 'unsafe-inline'; img-src data:; font-src data:; connect-src 'none'; frame-src 'none'; object-src 'none'; base-uri 'none'; form-action 'none'";
    $assets = get_stylesheet_directory() . '/interactive/assets/';
    $head = '<meta http-equiv="Content-Security-Policy" content="' . esc_attr($policy) . '"><style>' . file_get_contents($assets . 'step.css') . '</style>';
    $html = preg_replace('/<head\b[^>]*>/i', '$0' . $head, $html, 1);
    $end = strripos($html, '</body>');
    return substr_replace($html, '<script>' . file_get_contents($assets . 'step.js') . '</script>', $end === false ? strlen($html) : $end, 0);
}

/** Import once; preserve admin edits, original shortcodes and intentional deletions. */
function vf_ells_interactive_import_packaged_cases() {
    if (!vf_ells_interactive_can_upload() || !empty($_POST)) return;
    $lock = 'ells_interactive_import_lock';
    $locked_at = get_option($lock);
    if ($locked_at && (int) $locked_at < time() - 300) delete_option($lock);
    if (!add_option($lock, time(), '', false)) return;
    try {
        $imports = get_option('ells_interactive_packaged_cases', array());
        if (!is_array($imports)) $imports = array();
        foreach (glob(get_stylesheet_directory() . '/interactive/cases/*/case.json') as $file) {
            $slug = basename(dirname($file));
            if (isset($imports[$slug])) continue;
            $existing = get_posts(array('post_type' => 'ells_case', 'post_status' => array_keys(get_post_stati()), 'meta_key' => '_ells_case_package', 'meta_value' => $slug, 'numberposts' => 1, 'fields' => 'ids', 'suppress_filters' => true));
            if ($existing) {
                $imports[$slug] = (int) $existing[0];
                update_option('ells_interactive_packaged_cases', $imports, false);
                continue;
            }
            $case = vf_ells_interactive_case($slug);
            if (!$case) continue;
            $steps = array();
            foreach ($case['steps'] as $step) {
                $html = file_get_contents(dirname($file) . '/' . $step['file']);
                $html = str_replace(array('<link rel="stylesheet" href="../../assets/step.css">', '<script src="../../assets/step.js"></script>'), '', $html);
                $html = vf_ells_interactive_prepare_html($html);
                if (is_wp_error($html)) continue 2;
                $steps[] = array('title' => $step['title'], 'filename' => $step['file'], 'html' => $html);
            }
            $id = wp_insert_post(array('post_type' => 'ells_case', 'post_status' => 'publish', 'post_title' => wp_slash($case['title']), 'post_author' => get_current_user_id()), true);
            if (is_wp_error($id)) continue;
            update_post_meta($id, '_ells_case_steps', wp_slash($steps));
            update_post_meta($id, '_ells_case_package', $slug);
            $imports[$slug] = (int) $id;
            update_option('ells_interactive_packaged_cases', $imports, false);
        }
    } finally {
        delete_option($lock);
    }
}
add_action('admin_init', 'vf_ells_interactive_import_packaged_cases');

function vf_ells_interactive_case_key($id) {
    $slug = get_post_meta($id, '_ells_case_package', true);
    $imports = get_option('ells_interactive_packaged_cases', array());
    return is_string($slug) && is_array($imports) && isset($imports[$slug]) && (int) $imports[$slug] === (int) $id ? $slug : 'uploaded-' . $id;
}

add_action('post_edit_form_tag', function ($post) {
    if ($post->post_type === 'ells_case') echo ' enctype="multipart/form-data"';
});

function vf_ells_interactive_admin_row($index, $step = null) {
    ?>
    <div class="ells-case-row" style="border:1px solid #c3c4c7;padding:16px;margin:12px 0;background:white">
        <p><label>Step title <input class="widefat" required name="ells_steps[<?php echo esc_attr($index); ?>][title]" value="<?php echo esc_attr($step ? $step['title'] : ''); ?>"></label></p>
        <input type="hidden" name="ells_steps[<?php echo esc_attr($index); ?>][existing]" value="<?php echo esc_attr($step ? $index : ''); ?>">
        <p><label><?php echo $step ? 'Replace HTML (optional)' : 'HTML file'; ?> <input type="file" accept=".html,.htm,text/html" name="ells_html_<?php echo esc_attr($index); ?>" <?php echo $step ? '' : 'required'; ?>></label></p>
        <?php if ($step) : ?><p>Saved file: <?php echo esc_html($step['filename']); ?></p><?php endif; ?>
        <button type="button" class="button" data-case-up>Move up</button>
        <button type="button" class="button" data-case-down>Move down</button>
        <button type="button" class="button" data-case-remove>Remove step</button>
    </div>
    <?php
}

add_action('add_meta_boxes_ells_case', function ($post) {
    add_meta_box('ells-case-steps', 'Activity steps', function ($post) {
        if (!vf_ells_interactive_can_upload()) {
            echo '<p>HTML uploads require an administrator with the unfiltered_html capability. On multisite, use a network super administrator.</p>';
            return;
        }
        wp_nonce_field('ells_case_save', 'ells_case_nonce');
        echo '<p>Upload one self-contained HTML file per step, in order. Inline JavaScript and CSS are supported. External scripts, fonts, images and relative asset files are blocked; embed images as data URLs. Maximum 1 MB per file, 20 steps per case.</p>';
        echo '<p>The supplied EMBL progress bars and old page links are removed automatically. Other exports may need their navigation adapted before upload. Save the case before previewing; publish it before using the shortcode on a public page.</p>';
        $steps = get_post_meta($post->ID, '_ells_case_steps', true);
        echo '<div data-case-rows>';
        foreach (is_array($steps) ? $steps : array() as $i => $step) vf_ells_interactive_admin_row($i, $step);
        echo '</div><button type="button" class="button" data-case-add>Add step</button><template id="ells-case-row-template">';
        vf_ells_interactive_admin_row('__INDEX__');
        echo '</template>';
    }, 'ells_case', 'normal', 'high');
    add_meta_box('ells-case-shortcode', 'Shortcode and preview', function ($post) {
        $key = vf_ells_interactive_case_key($post->ID);
        $code = '[ells_interactive case="' . $key . '"]';
        echo '<p><input class="widefat" readonly aria-label="Shortcode" data-case-shortcode value="' . esc_attr($code) . '"></p><button type="button" class="button" data-case-copy>Copy shortcode</button><span data-case-copy-status role="status"></span>';
        $url = add_query_arg(array('page' => 'ells-interactive-preview', 'case' => $key), admin_url('admin.php'));
        echo '<p><a class="button" href="' . esc_url($url) . '" target="_blank" rel="noopener">Preview saved case</a></p><p>Drafts are visible only to administrators. The shortcode stays the same when you update or reorder steps.</p>';
    }, 'ells_case', 'side');
});

add_action('save_post_ells_case', function ($post_id) {
    if (wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !isset($_POST['ells_case_nonce']) || !is_string($_POST['ells_case_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ells_case_nonce'])), 'ells_case_save') || !vf_ells_interactive_can_upload() || !current_user_can('edit_post', $post_id)) return;
    $rows = isset($_POST['ells_steps']) && is_array($_POST['ells_steps']) ? wp_unslash($_POST['ells_steps']) : array();
    $old = get_post_meta($post_id, '_ells_case_steps', true);
    $steps = array();
    $error = '';
    if (!$rows || count($rows) > 20) $error = 'Add between 1 and 20 steps. Existing saved steps were kept.';
    foreach ($rows as $key => $row) {
        if ($error) break;
        if (!is_array($row) || !ctype_digit((string) $key) || !isset($row['title']) || !is_string($row['title'])) { $error = 'Invalid step data.'; break; }
        $title = sanitize_text_field($row['title']);
        $existing = isset($row['existing']) && is_string($row['existing']) && ctype_digit($row['existing']) ? (int) $row['existing'] : -1;
        $step = is_array($old) && isset($old[$existing]) ? $old[$existing] : null;
        $file = isset($_FILES['ells_html_' . $key]) ? $_FILES['ells_html_' . $key] : null;
        if ($file && isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['error'] !== UPLOAD_ERR_OK || !isset($file['size'], $file['name'], $file['tmp_name']) || !is_string($file['name']) || !is_string($file['tmp_name']) || $file['size'] > 1024 * 1024 || !in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), array('html', 'htm'), true) || !is_uploaded_file($file['tmp_name'])) {
                $error = 'Upload failed. Use an HTML file smaller than 1 MB.'; break;
            }
            $html = file_get_contents($file['tmp_name']);
            if (!$html || strpos($html, "\0") !== false || !preg_match('//u', $html) || stripos($html, '<?') !== false) { $error = 'Use UTF-8 HTML without PHP code.'; break; }
            $html = vf_ells_interactive_prepare_html($html);
            if (is_wp_error($html)) { $error = $html->get_error_message(); break; }
            $step = array('html' => $html, 'filename' => sanitize_file_name($file['name']));
        }
        if (!$title || !$step) { $error = 'Every step needs a title and an HTML file.'; break; }
        $step['title'] = $title;
        $steps[] = $step;
    }
    if ($error) {
        set_transient('ells_case_error_' . get_current_user_id(), $error . ' No changes to the saved steps were made. Reselect new files before retrying.', 120);
        return;
    }
    update_post_meta($post_id, '_ells_case_steps', wp_slash($steps));
}, 10, 1);

add_action('admin_notices', function () {
    $key = 'ells_case_error_' . get_current_user_id();
    $error = get_transient($key);
    if ($error) { delete_transient($key); echo '<div class="notice notice-error"><p>' . esc_html($error) . '</p></div>'; }
});

add_action('admin_menu', function () {
    add_submenu_page(null, 'Activity preview', 'Activity preview', 'manage_options', 'ells-interactive-preview', function () {
        if (!current_user_can('manage_options')) wp_die('You cannot preview these cases.');
        $slug = isset($_GET['case']) && is_string($_GET['case']) ? sanitize_text_field(wp_unslash($_GET['case'])) : '';
        echo '<div class="wrap" style="max-width:1100px"><h1>Saved activity preview</h1><p>This preview uses the last saved files and order. Unsaved changes are not included.</p>';
        echo vf_ells_interactive_shortcode(array('case' => $slug));
        echo '</div>';
    });
});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'admin_page_ells-interactive-preview') vf_ells_interactive_assets();
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'ells_case') {
        $path = get_stylesheet_directory() . '/interactive/assets/admin.js';
        wp_enqueue_script('ells-cases-admin', get_stylesheet_directory_uri() . '/interactive/assets/admin.js', array(), filemtime($path), true);
    }
});

add_action('load-admin_page_ells-interactive-preview', function () {
    global $title, $parent_file;
    $title = 'Activity preview';
    $parent_file = 'edit.php?post_type=ells_case';
});

add_filter('manage_ells_case_posts_columns', function ($columns) {
    $columns['ells_shortcode'] = 'Shortcode';
    return $columns;
});
add_action('manage_ells_case_posts_custom_column', function ($column, $id) {
    if ($column === 'ells_shortcode') echo '<code>' . esc_html('[ells_interactive case="' . vf_ells_interactive_case_key($id) . '"]') . '</code>';
}, 10, 2);
