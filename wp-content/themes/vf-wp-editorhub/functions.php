<?php


/*
Registers EMBL site taxonomy
*/

function register_embl_site_taxonomy() {
    register_taxonomy(
        'embl-site', // Taxonomy slug
        'page',      // Post type to attach to (pages in this case)
        array(
            'labels' => array(
                'name'              => 'EMBL site',
                'singular_name'     => 'EMBL site',
                'search_items'      => 'Search EMBL sites',
                'all_items'         => 'All EMBL sites',
                'parent_item'       => 'Parent EMBL site',
                'parent_item_colon' => 'Parent EMBL site:',
                'edit_item'         => 'Edit EMBL site',
                'update_item'       => 'Update EMBL site',
                'add_new_item'      => 'Add New EMBL site',
                'new_item_name'     => 'New EMBL site Name',
                'menu_name'         => 'EMBL sites',
            ),
            'public'            => true,
            'hierarchical'      => true, // Like categories (true) or tags (false)
            'show_ui'           => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'embl-site'),
            'show_in_rest'      => false, // Enable for Gutenberg editor
            
        )
    );
}
add_action('init', 'register_embl_site_taxonomy');


/*
Adds EMBL site texonomy filter to admin page
*/

function embl_site_taxonomy_filter_dropdown() {
    global $typenow;

    // Only show this filter on the Pages screen
    if ($typenow === 'page') {
        $taxonomy = 'embl-site';
        $taxonomy_obj = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => 'All ' . $taxonomy_obj->label,
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '',
            'hierarchical'    => true,
            'depth'           => 0,
            'show_count'      => true,
            'hide_empty'      => false,
        ));
    }
}
add_action('restrict_manage_posts', 'embl_site_taxonomy_filter_dropdown');

function embl_site_filter_pages_by_taxonomy($query) {
    global $pagenow;
    $taxonomy = 'embl-site';

    if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'page' && isset($_GET[$taxonomy]) && is_numeric($_GET[$taxonomy]) && $_GET[$taxonomy] != 0) {
        $term = get_term_by('id', $_GET[$taxonomy], $taxonomy);
        if ($term) {
            $query->query_vars[$taxonomy] = $term->slug;
        }
    }
}
add_filter('parse_query', 'embl_site_filter_pages_by_taxonomy');


/*
Enables tags for pages
*/

function register_label_taxonomy() {
    $labels = array(
        'name'                       => _x('Labels', 'taxonomy general name'),
        'singular_name'              => _x('Label', 'taxonomy singular name'),
        'search_items'               => __('Search Labels'),
        'popular_items'              => __('Popular Labels'),
        'all_items'                  => __('All Labels'),
        'edit_item'                  => __('Edit Label'),
        'update_item'                => __('Update Label'),
        'add_new_item'               => __('Add New Label'),
        'new_item_name'              => __('New Label Name'),
        'separate_items_with_commas' => __('Separate labels with commas'),
        'add_or_remove_items'        => __('Add or remove labels'),
        'choose_from_most_used'      => __('Choose from the most used labels'),
        'not_found'                  => __('No labels found.'),
        'menu_name'                  => __('Labels'),
    );

    register_taxonomy('label', ['page'], array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array('slug' => 'label'),
        'show_in_rest'          => true,
        'public'                => true,
    ));
}
add_action('init', 'register_label_taxonomy');


/*
Adds tags filter to admin pages
*/

function label_filter_dropdown_in_admin() {
    global $typenow;
    if ($typenow === 'page') {
        wp_dropdown_categories(array(
            'show_option_all' => 'All Labels',
            'taxonomy'        => 'label',
            'name'            => 'label',
            'orderby'         => 'name',
            'selected'        => isset($_GET['label']) ? $_GET['label'] : '',
            'hierarchical'    => false,
            'depth'           => 0,
            'show_count'      => true,
            'hide_empty'      => false,
        ));
    }
}
add_action('restrict_manage_posts', 'label_filter_dropdown_in_admin');


function label_filter_query($query) {
    global $pagenow;
    if (
        is_admin() &&
        $pagenow === 'edit.php' &&
        isset($_GET['post_type']) &&
        $_GET['post_type'] === 'page' &&
        isset($_GET['label']) &&
        is_numeric($_GET['label']) &&
        $_GET['label'] != 0
    ) {
        $term = get_term_by('id', $_GET['label'], 'label');
        if ($term) {
            $query->query_vars['label'] = $term->slug;
        }
    }
}
add_filter('parse_query', 'label_filter_query');


/*
Auto assign embl site based on the template selected
*/


function auto_assign_embl_site_term_by_template($post_id) {
    // Exit on autosave, revision, or wrong post type
    if (
        defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ||
        wp_is_post_revision($post_id) ||
        get_post_type($post_id) !== 'page'
    ) {
        return;
    }

    // Get the selected page template
    $template = get_page_template_slug($post_id);

    // Map of templates to taxonomy terms
    $template_term_map = [
        'vf_template_embl-ebi-template.php' => 'embl-ebi',
        'vf_template_embl-template.php'     => 'embl',
    ];

    // If template matches, assign term; otherwise, clear terms
    if (isset($template_term_map[$template])) {
        $term = $template_term_map[$template];
        wp_set_object_terms($post_id, $term, 'embl-site', false); // set single term
    } else {
        wp_set_object_terms($post_id, [], 'embl-site', false); // clear all terms
    }
}
add_action('save_post', 'auto_assign_embl_site_term_by_template');



?>