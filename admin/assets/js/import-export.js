jQuery(document).ready(function ($) {
    var stepOne = $('#atbdp_csv_step_two');
    $(stepOne).on('submit', function (e) {
        e.preventDefault();

        var form_data = new FormData();
        // ajax action
        form_data.append('action', 'atbdp_import_listing');
        form_data.append('file', $('input[name="file"]').val());
        form_data.append('delimiter', $('input[name="delimiter"]').val());
        form_data.append('update_existing', $('input[name="update_existing"]').val());
        form_data.append('wpnonce', $('input[name="_wpnonce"]').val());
        $('select.atbdp_map_to').each(function () {
            var name = $(this).attr("name");
            var value = $(this).val();
            if (('title' == name) || ('description' == name) || ('id' == name) || ('category' == name) || ('location' == name) || ('tag' == name) || ('preview_image' == name)) {
                form_data.append(name, value);
            } else {
                form_data.append('meta[' + name + ']', value);
            }
        });
        $.ajax({
            method: 'POST',
            processData: false,
            contentType: false,
            url: import_export_data.ajaxurl,
            data: form_data,
            success: function (response) {
                console.log(response);
            }
        });

    })
});