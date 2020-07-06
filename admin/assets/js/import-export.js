jQuery(document).ready(function ($) {
    $('#atbdp_ie_download_sample').on('click', function(e) {
        var ie_file = $(this).attr('data-sample-csv');
        if (ie_file) {
            window.location.href = ie_file;
            return false;
        }
    });
    var stepOne = $('#atbdp_csv_step_two');
    var position = 0;
    $(stepOne).on('submit', function (e) {
        e.preventDefault();
        $('.atbdp-importer-mapping-table-wrapper').fadeOut(300);
        $('.directorist-importer__importing').fadeIn(300);
        $('.atbdp-mapping-step').removeClass('active');
        $('.atbdp-progress-step').addClass('active');
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
                if (('title' == value) || ('description' == value)  || ('_listing_prv_img' == value)) {
                    form_data.append(value, name);
                } else if(('category' == value) || ('location' == value) || ('tag' == value)){
                     form_data.append('tax_input[' + value + ']', name);
                } else {
                    form_data.append('meta[' + value + ']', name);
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
                    $('.importer-details').html('Imported ' + (parseInt(response.next_position) - 1) + ' out of ' + response.total);
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