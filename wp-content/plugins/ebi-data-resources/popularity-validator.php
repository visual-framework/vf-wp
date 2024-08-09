<?php

add_action('wp_ajax_validate_popularity', 'validate_popularity');

function validate_popularity()
{
    $selected_domain       = $_POST['selected_domain'];
    $error_message         = "";
    $args                  = [
        'post_type'      => 'data-resource',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'     => 'resource_domain',
                'value'   => $selected_domain['value'],
                'compare' => '=',
            ],
            [
                'key'     => 'resource_popular',
                'value'   => 1,
                'compare' => '=',
            ],
        ],
    ];
    $query                 = new WP_Query($args);
    $count                 = $query->post_count;
    $selected_domain_label = $selected_domain['label'];
    if ($count >= 3) {
        $popular_items = array();
        while ($query->have_posts()) {
            $query->the_post();
            $popular_items[] = get_the_title(); // Fetch the title of each popular item
        }
        $popular_items_string = implode(', ', $popular_items);

        $error_message = "There can be no more than 3 popular items in the $selected_domain_label domain. Currently, there are $count popular items: $popular_items_string";
    }
    if (empty($error_message)) {
        wp_send_json_success('success');
    } else {
        wp_send_json_error($error_message, 422);
    }
    wp_die();
}