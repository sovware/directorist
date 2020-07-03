jQuery(document).ready(function ($) {
    var stepOne = $('#atbdp_csv_step_two');
    var position = 0;
    $(stepOne).on('submit', function (e) {
        e.preventDefault();
        $('.atbdp-importer-mapping-table-wrapper').fadeOut(300);
        $('.directorist-importer__importing').fadeIn(300);
        var counter = 0;
        var run_import = function () {
            var form_data = new FormData();
            // ajax action
            form_data.append('action', 'atbdp_import_listing');
            form_data.append('file', $('input[name="file"]').val());
            form_data.append('delimiter', $('input[name="delimiter"]').val());
            form_data.append('update_existing', $('input[name="update_existing"]').val());
            form_data.append('position', position)
            form_data.append('wpnonce', $('input[name="_wpnonce"]').val());
            $('select.atbdp_map_to').each(function () {
                var name = $(this).attr("name");
                var value = $(this).val();
                if (('title' == name) || ('description' == name)  || ('preview_image' == name)) {
                    form_data.append(name, value);
                } else if(('category' == name) || ('location' == name) || ('tag' == name)){
                     form_data.append('tax_input[' + name + ']', value);
                } else {
                    form_data.append('meta[' + name + ']', value);
                }
            });
            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
               // async: false,
                url: import_export_data.ajaxurl,
                data: form_data,
                success: function (response) {
                    
                    $('.importer-details').html('Imported ' + response.next_position + ' out of ' + response.total);
                    $('.directorist-importer-progress').val(response.percentage);
                    if ('100' != response.percentage && (counter < 150)) {
                        position = response.next_position;
                        run_import();
                        counter++;
                    } else {
                        window.location = response.url;
                    }
                    //console.log(response);
                },
                error: function (response) {
                    console.log(response);
                }
            });
        }
        run_import();

    })
});