<?php

function add_export_button()
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
        || in_array('resources_admin', $roles)
    ) {
        ?>
        <div class="alignleft actions">
            <button id="export-to-excel" class="button">Export to Excel</button>
        </div>
        <?php
    }
}

add_action('restrict_manage_posts', 'add_export_button');


function export_data_resources_to_excel()
{
    // Load the PHPSpreadsheet library
    require_once(ABSPATH.'../vendor/autoload.php');

    // Get the data-resources custom post type
    $args  = [
        'post_type'      => 'data-resource',
        'posts_per_page' => -1,
    ];
    $query = new WP_Query($args);

    // Create a new spreadsheet and add a worksheet
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $worksheet   = $spreadsheet->getActiveSheet();

    $fields_to_export = [
        'Display Name'                 => 'display_name',
        'Domain'                       => 'resource_domain',
        'URL'                          => 'url',
        'Popular'                      => 'resource_popular',
        'Status'                       => 'resource_status',
        'Categories'                   => 'resource_categories',
        'Keywords'                     => 'resource_keywords',
        'Long Description'             => 'long_description',
        'Short Description'            => 'short_description',
        'Primary Team'                 => 'resource_primary_team',
        'Additional Teams'             => 'resource_teams',
        'Tool Group'                   => 'resource_tool_group',
        'Activity'                     => 'resource_activity',
        'Functions'                    => 'resource_functions',
        'REST API Landing Page'        => 'resource_rest_api_page',
        'API Compliance'               => 'resource_api_compliant',
        'Out of EBI Technical Control' => 'resource_out_of_ebi_ctrl',
        'EMBL Site'                    => 'resource_embl_site',
        'Data Licence Type'            => 'resource_data_licence_type',
        'Software Licence Type'        => 'resource_sw_licence_type',
    ];


    // Create the header row
    $row    = 1;
    $column = 'A';
    foreach ($fields_to_export as $label => $db_col_name) {
        $worksheet->setCellValue($column.$row, $label);
        $column++;
    }

    // Loop through the data-resources and add each one to the worksheet
    $row = 2;
    while ($query->have_posts()) {
        $query->the_post();
        $column = 'A';
        foreach ($fields_to_export as $label => $db_col_name) {
            $value = get_post_meta(get_the_ID(), $db_col_name, true);
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            if ($db_col_name == 'resource_popular'
                || $db_col_name == 'resource_out_of_ebi_ctrl'
            ) {
                $value = $value ? 'Yes' : 'No';
            }
            if ($db_col_name == 'long_description'
                || $db_col_name == 'short_description'
            ) {
                $value = strip_tags($value);
            }
            if ($db_col_name == 'resource_primary_team') {
                $term = get_term($value, 'embl_teams');
                if ($term && !is_wp_error($term)) {
                    $value = $term->name;
                }
            }
            if ($db_col_name == 'resource_teams') {
                $term_ids =  explode(',', $value);
                $terms = array();
                foreach ($term_ids as $term_id) {
                    $term = get_term($term_id, 'embl_teams');
                    if ($term && !is_wp_error($term)) {
                        $terms[] = $term->name;
                    }
                }

                $value = implode(', ', $terms);
            }
            $worksheet->setCellValue($column.$row, $value);
            $column++;
        }
        $row++;
    }

    // Save the spreadsheet to a variable as a base64-encoded string
    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    // Start output buffering to capture the spreadsheet output
    ob_start();

    // Save the spreadsheet output to the output buffer
    $writer->save('php://output');

    // Get the output buffer contents and clear the buffer
    $file_data = base64_encode(ob_get_clean());

    // Reset the WordPress query
    wp_reset_postdata();

    // Send the file contents in the AJAX response
    wp_send_json(
        [
            'file_data' => $file_data,
            'filename'  => 'data-resources-'.date('Y-m-d').'.xlsx',
        ]
    );

    // End the AJAX request
    wp_die();
}

function register_ajax_actions()
{
    add_action(
        'wp_ajax_export_data_resources',
        'export_data_resources_to_excel'
    );
}

add_action('init', 'register_ajax_actions');