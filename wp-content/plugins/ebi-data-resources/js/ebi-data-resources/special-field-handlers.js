jQuery(document).ready(function ($) {
    restrict_popular_field();
    split_resource_function_values();


    function restrict_popular_field() {
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'restrict_popular_field'
            },
            success: function(response) {
                const popular_checkbox = $('#popular-field').find('input[type="checkbox"]');
                popular_checkbox.prop('disabled', !response.user_is_allowed_to_use_popular_field);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    function split_resource_function_values() {
        const split_resource_functions_button = $('#split-resource-functions');
        if (!split_resource_functions_button.length) {
            return;
        }
        split_resource_functions_button.on('click', function (e) {
            if (!confirm('Are you sure you want to split the resource functions?')) {
                return false;
            }
            e.preventDefault();
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'split_resource_functions',
                },
                success: function (response) {
                    alert('Resource functions split successfully!');
                }
            })
        })
    }
});