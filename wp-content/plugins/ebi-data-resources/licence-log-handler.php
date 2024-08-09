<?php

$old_licence_values = [
    'old_software_licence_value' => '',
    'old_data_licence_value'     => '',
];


function store_old_licence_value($post_id) {
    if (!get_post_type($post_id) === 'data-resource') {
        return;
    }
    global $old_licence_values;
    $old_licence_values['old_software_licence_value'] = get_post($post_id)->resource_sw_licence_type;
    $old_licence_values['old_data_licence_value'] = get_post($post_id)->resource_data_licence_type;
}
add_action('pre_post_update', 'store_old_licence_value');


function update_log($post_id) {
    if (!get_post_type($post_id) === 'data-resource') {
        return;
    }
    global $old_licence_values;
    foreach ($old_licence_values as $licence_key_name => $old_licence_value) {
        // Determine which licence field was updated
        if ($licence_key_name === 'old_software_licence_value') {
            $log_field_name = 'resource_software_log';
            $data_or_software = 'Software';
            $licence_field_name = 'resource_sw_licence_type';
        } else {
            $log_field_name = 'resource_licence_log';
            $data_or_software = 'Data';
            $licence_field_name = 'resource_data_licence_type';
        }
        $new_licence_value = get_post($post_id)->$licence_field_name;
        if ($old_licence_value == $new_licence_value) {
            continue;
        }
        $old_log_value = get_post_meta($post_id, $log_field_name, true);
        $user = wp_get_current_user();
        $current_date_time = date('Y-m-d H:i:s');
        $new_log_message = "\n" . $data_or_software . " Licence Type updated from " . $old_licence_value . " to " . $new_licence_value . " by " . $user->display_name . " on " . $current_date_time;
        $updated_log_value = $new_log_message . "\n" . $old_log_value;
        update_post_meta($post_id, $log_field_name, $updated_log_value);
    }
}

add_action('save_post', 'update_log');