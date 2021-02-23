jQuery(document).ready(function ($) {
    const query_string = (function (a) {
        if (a == '') return {};
        const b = {};
        for (let i = 0; i < a.length; ++i) {
            const p = a[i].split('=', 2);
            if (p.length == 1) b[p[0]] = '';
            else b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, ' '));
        }
        return b;
    })(window.location.search.substr(1).split('&'));

    $('body').on('change', 'select[name="directory_type"]', function () {
        admin_listing_form($(this).val());
    });

    function admin_listing_form(directory_type) {
        var file = query_string.file;
        var delimiter = query_string.delimiter;
        $.ajax({
            type: 'post',
            url: import_export_data.ajaxurl,
            data: {
                action: 'directorist_listing_type_form_fields',
                directory_type: directory_type,
                delimiter: delimiter,
                file: file,
            },
            beforeSend: function () {
                $('#directorist-type-preloader').show();
            },
            success(response) {
                $('.atbdp-importer-mapping-table').remove();
                $('.directory_type_wrapper').after(response);
            },
            complete: function () {
                $('#directorist-type-preloader').hide();
            }
        });
    }



    $('#atbdp_ie_download_sample').on('click', function (e) {
        const ie_file = $(this).attr('data-sample-csv');
        if (ie_file) {
            window.location.href = ie_file;
            return false;
        }
    });

    const stepTwo = $('#atbdp_csv_step_two');
    let position = 0;
    let failed = 0;
    let imported = 0;

    $(stepTwo).on('submit', function (e) {
        e.preventDefault();

        $('.atbdp-importer-mapping-table-wrapper').fadeOut(300);
        $('.directorist-importer__importing').fadeIn(300);
        $(this)
            .parent('.csv-fields')
            .fadeOut(300);
        $('.atbdp-mapping-step')
            .removeClass('active')
            .addClass('done');
        $('.atbdp-progress-step').addClass('active');

        let counter = 0;
        var run_import = function () {
            const form_data = new FormData();
            // ajax action
            form_data.append('action', 'atbdp_import_listing');
            form_data.append('csv_file', $('input[name="csv_file"]').val());
            form_data.append('delimiter', $('input[name="delimiter"]').val());
            form_data.append('update_existing', $('input[name="update_existing"]').val());

            if ($('select[name="directory_type"]').length) {
                form_data.append('directory_type', $('select[name="directory_type"]').val());
            }

            form_data.append('position', position);
            form_data.append('wpnonce', $('input[name="_wpnonce"]').val());
            var map_elm = null;

            if ($('select.atbdp_map_to').length) {
                map_elm = $('select.atbdp_map_to');
            }

            if ($('input.atbdp_map_to').length) {
                map_elm = $('input.atbdp_map_to');
            }

            if (map_elm) {
                // var log = [];
                map_elm.each(function () {
                    const name = $(this).attr('name');
                    const value = $(this).val();
                    if (value == 'listing_title' || value == 'listing_content' || value == 'listing_img' || value == 'directory_type') {
                        form_data.append(value, name);
                        // log.push( { [ value ]: name } );
                    } else if (value == 'category' || value == 'location' || value == 'tag') {
                        form_data.append(`tax_input[${value}]`, name);
                        // log.push( { [ `tax_input[${value}]` ]: name } );
                    } else {
                        form_data.append(`meta[${value}]`, name);
                        // log.push( { [ `meta[${value}]` ]: name } );
                    }
                });
            }

            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                // async: false,
                url: import_export_data.ajaxurl,
                data: form_data,
                success(response) {
                    console.log({ response });
                    
                    if ( response.error ) {
                        console.log({ response });
                        return;
                    }

                    imported += response.imported;
                    failed += response.failed;
                    $('.importer-details').html(
                        `Imported ${response.next_position} out of ${response.total}`
                    );
                    $('.directorist-importer-progress').val(response.percentage);
                    if (response.percentage != '100' && counter < 150) {
                        position = response.next_position;
                        run_import();
                        counter++;
                    } else {
                        window.location = `${response.url
                            }&listing-imported=${imported}&listing-failed=${failed}`;
                    }
                    $('.directorist-importer-length').css('width', response.percentage + '%');
                },
                error(response) {
                    window.console.log(response);
                },
            });

        };
        run_import();
    });
    /* csv upload */
    $('#upload').change(function (e) {
        const filename = e.target.files[0].name;
        $('.csv-upload .file-name').html(filename);
    });
});
