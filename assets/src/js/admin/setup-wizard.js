/* eslint-disable */
jQuery(document).ready(function($) {
        const import_dummy = $('#atbdp_dummy_form');
        let position = 0;
        let failed = 0;
        let imported = 0;
        let redirect_url = '';
        $(import_dummy).on('submit', function(e) {
                e.preventDefault();

                $('.atbdp_dummy_body').fadeOut(300);
                $('.atbdp-c-footer').fadeOut(300);
                $('.directorist-importer__importing').fadeIn(300);
                $(this)
                        .parent('.csv-fields')
                        .fadeOut(300);
                $('.atbdp-mapping-step')
                        .removeClass('active')
                        .addClass('done');
                $('.atbdp-progress-step').addClass('active');
                let counter = 0;
                var run_import = function() {
                        const form_data = new FormData();
                        // ajax action
                        form_data.append('action', 'atbdp_dummy_data_import');
                        form_data.append('file', $('#dummy_csv_file').val());
                        form_data.append('limit', $('#atbdp-listings-to-import').val());
                        form_data.append('image', ($('#atbdp-import-image')).is(':checked') ? 1 : '');
                        form_data.append('delimiter', ',');
                        form_data.append('update_existing', '');
                        form_data.append('position', position);
                        form_data.append('wpnonce', $('input[name="_wpnonce"]').val());
                        form_data.append('pre_mapped', true);
                        $.ajax({
                                method: 'POST',
                                processData: false,
                                contentType: false,
                                // async: false,
                                url: import_export_data.ajaxurl,
                                data: form_data,
                                success(response) {
                                        imported += response.imported;
                                        failed += response.failed;
                                        redirect_url = response.url;
                                        $('.importer-details').html(
                                                `Imported ${response.next_position} out of ${response.total}`
                                        );
                                        $('.directorist-importer-progress').val(response.percentage);
                                        if (response.percentage != '100' && counter < 150) {
                                                position = response.next_position;
                                                run_import();
                                                counter++;
                                        } else {
                                                window.location = response.url;
                                        }
                                        $('input[name="save_step"]').addClass("btn-hide");
                                        $('.directorist-importer-length').css('width', response.percentage + '%');
                                },
                                error(response) {
                                        window.location = redirect_url;
                                },
                        });

                };
                run_import();
        });

        //options
        $('.atbdp-sw-gmap-key').hide();
        $('#select_map').on('change', function (e) {
                if($(this).val() === 'google'){
                        $('.atbdp-sw-gmap-key').show();
                }else{
                        $('.atbdp-sw-gmap-key').hide();
                }
        });
        if($('#select_map').val() === 'google'){
                $('.atbdp-sw-gmap-key').show();
        }else{
                $('.atbdp-sw-gmap-key').hide();
        }

        $('.atbdp-sw-featured-listing').hide();
        $('#enable_monetization').on('change', function(){
                if($(this).prop("checked") === true){
                        $('.atbdp-sw-featured-listing').show();
                }else{
                        $('.atbdp-sw-featured-listing').hide();
                }
        });
        if($('#enable_monetization').prop("checked") === true){
                $('.atbdp-sw-featured-listing').show();
        }else{
                $('.atbdp-sw-featured-listing').hide();
        }

        $('.atbdp-sw-listing-price').hide();
        $('#enable_featured_listing').on('change', function(){
                if($(this).prop("checked") === true){
                        $('.atbdp-sw-listing-price').show();
                }else{
                        $('.atbdp-sw-listing-price').hide();
                }
        });
        if($('#enable_monetization').prop("checked") === true){
                $('.atbdp-sw-listing-price').show();
        }else{
                $('.atbdp-sw-listing-price').hide();
        }

        /* custom select */
        $('#select_map').select2({minimumResultsForSearch: -1});
        $('#atbdp-listings-to-import').select2({minimumResultsForSearch: -1});
});