jQuery(document).ready(function ($) {
    const popular_check_box = $('#acf-field_642d57dab0d22')[0];
    const domain_selector = $('#acf-field_642d5dfa9d260')[0];
    const display_name = $('input[name="acf[field_642d54a8888e4]"]');
    // Hide adminstrator tab.
    $('#acf-group_641995b31b99d .acf-tab-group > li:nth-child(3)').hide();

    if (!popular_check_box || !domain_selector) {
        return;
    }

    popular_check_box.addEventListener("change", function (e) {
        reset_error_span();
        if (popular_check_box.checked) {
            validate_popularity();
        }
    });

    domain_selector.addEventListener("change", function (e) {
        reset_error_span();
        if (popular_check_box.checked) {
            validate_popularity();
        }
    });

    // Listen for changes to the 'display_name' field
    display_name.on('change', function() {
        // Get the new value
        var displayName = $(this).val();
        // Update the title field
        wp.data.dispatch( 'core/editor' ).editPost( { title: displayName} )
    });


    function validate_popularity() {
        const existing_error_span = popular_check_box.parentNode.querySelector('#popularity-error-span');
        if (existing_error_span) {
            existing_error_span.remove();
        }
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'validate_popularity',
                selected_domain: {
                    label: domain_selector.selectedOptions[0].innerHTML,
                    value: domain_selector.selectedOptions[0].value
                }
            },
            success: function (response) {
                popular_check_box.value = 1;
                reset_error_span();
            },
            error: function (xhr, status, error) {
                const error_message = JSON.parse(xhr.responseText).data;
                const error_span = document.createElement('span');
                error_span.innerHTML = error_message;
                error_span.id = 'popularity-error-span';
                error_span.classList.add('error-message');
                reset_error_span();
                popular_check_box.parentNode.appendChild(error_span);
                popular_check_box.checked = false;
            }
        });
    }

    function reset_error_span() {
        const existing_error_span = popular_check_box.parentNode.querySelector('#popularity-error-span');
        if (existing_error_span) {
            existing_error_span.remove();
        }
    }
});
