jQuery(document).ready(function ($) {
    const export_button = $('#export-to-excel');
    if (!export_button.length) {
        return;
    }
    export_button.on('click', function (e) {
        e.preventDefault();

        // Send an AJAX request to trigger the export function
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'export_data_resources',
            },
            success: function (response) {
                // Redirect the user to the Excel file download link
                const anchor_tag = document.createElement('a');
                anchor_tag.href = 'data:application/vnd.ms-excel;base64,' + response.file_data;
                anchor_tag.download = response.filename;
                anchor_tag.style.display = 'none';
                document.body.appendChild(anchor_tag);
                anchor_tag.click();
                document.body.removeChild(anchor_tag);
            }
        });
    });
});