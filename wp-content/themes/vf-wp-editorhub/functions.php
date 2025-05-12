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
            'show_in_rest'      => true, // Enable for Gutenberg editor
        )
    );
}
add_action('init', 'register_embl_site_taxonomy');

/*
Adds EMBL site taxonomy to pages
*/

function add_embl_site_taxonomy_to_pages() {
    add_meta_box('embl-sitediv', 'EMBL Sites', 'post_categories_meta_box', 'page', 'side', 'default');
}
add_action('add_meta_boxes', 'add_embl_site_taxonomy_to_pages');

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

function enable_tags_for_pages() {
    register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'enable_tags_for_pages');


function add_tags_meta_box_to_pages() {
    register_taxonomy_for_object_type('post_tag', 'page');
    add_meta_box('tagsdiv-post_tag', 'Tags', 'post_tags_meta_box', 'page', 'side', 'default');
}
add_action('add_meta_boxes', 'add_tags_meta_box_to_pages');


/*
Adds tags filter to admin pages
*/

function page_tags_filter_dropdown() {
    global $typenow;

    if ($typenow === 'page') {
        $taxonomy = 'post_tag';
        $taxonomy_obj = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' => 'All Tags',
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '',
            'hierarchical'    => false,
            'depth'           => 0,
            'show_count'      => true,
            'hide_empty'      => false,
        ));
    }
}
add_action('restrict_manage_posts', 'page_tags_filter_dropdown');



?>