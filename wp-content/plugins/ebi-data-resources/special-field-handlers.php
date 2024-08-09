<?php

function populate_resource_function_field_choices($field) {
    global $post_type;
    if ($post_type != 'data-resource') {
        return $field;
    }
    $api_url = 'https://www.ebi.ac.uk/ols4/api/ontologies/edam/terms/http%253A%252F%252Fedamontology.org%252Foperation_0004/hierarchicalDescendants?size=1000';
    $api_response = wp_remote_get($api_url);

    $term_labels = [];
    $term_ids = [];

    if (!is_wp_error($api_response) && $api_response['response']['code'] === 200) {
        $data =  json_decode($api_response['body'], true);

        foreach ($data['_embedded']['terms'] as $term) {
            $edam_prefixed_iri = 'edam:' . $term['iri'];
            $term_labels[] = $term['label'] . ' (' . $edam_prefixed_iri . ')';
            $term_ids[] = $edam_prefixed_iri;
        }

        $choices = array_combine($term_ids, $term_labels);
        $field['choices'] = $choices;
    }

    return $field;
}
add_filter('acf/load_field/name=resource_functions', 'populate_resource_function_field_choices');

function add_split_resource_functions_button()
{
    global $post_type;
    if ($post_type != 'data-resource') {
        return;
    }
    $user_id = get_current_user_id();
    $user    = get_userdata($user_id);
    $roles   = $user->roles;

    if (in_array('resources_editor', $roles)
        || in_array(
            'administrator',
            $roles
        )
    ) {
        ?>
        <div class="alignleft actions">
            <button id="split-resource-functions" class="button">Split Resource Functions</button>
        </div>
        <?php
    }
}

//add_action('restrict_manage_posts', 'add_split_resource_functions_button');

function split_resource_functions() {
    $args = array(
        'post_type' => 'data-resource',
        'posts_per_page' => -1, // Retrieve all posts
    );

    $query = new WP_Query($args);

    while ($query->have_posts()) {
        $query->the_post();

        $post = $query->post;
        $post_id = $post->ID;
        if (is_empty($post->resource_functions) || is_array($post->resource_functions)) {
            continue;
        }

        $resource_functions_array = explode(',', $post->resource_functions);
        $trimmed_values = array_map(function($value) {
            return ltrim($value);
        }, $resource_functions_array);
        update_post_meta($post_id, 'resource_functions', $trimmed_values);
    }
}

add_action('wp_ajax_split_resource_functions', 'split_resource_functions');


function restrict_popular_field() {
    $user_id = get_current_user_id();
    $user    = get_userdata($user_id);
    $roles   = $user->roles;
    $user_is_allowed_to_use_popular_field = in_array('resources_admin', $roles) || in_array('administrator', $roles);
    wp_send_json([
        'user_is_allowed_to_use_popular_field' => $user_is_allowed_to_use_popular_field,
    ]);
}
add_action('wp_ajax_restrict_popular_field', 'restrict_popular_field');