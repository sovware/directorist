/* eslint-disable */
jQuery(document).ready(function ($) {
    const import_dummy = $('#atbdp_dummy_form');
    let position = 0;
    let failed = 0;
    let imported = 0;
    let redirect_url = '';

    $(import_dummy).on('submit', function (e) {
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
        var run_import = function () {
            const form_data = new FormData();
            // ajax action
            form_data.append('action', 'atbdp_dummy_data_import');
            form_data.append('file', $('#dummy_csv_file').val());
            form_data.append('limit', $('#atbdp-listings-to-import').val());
            form_data.append('image', ($('#atbdp-import-image')).is(':checked') ? 1 : '');
            form_data.append('delimiter', ',');
            form_data.append('update_existing', '');
            form_data.append('position', position);
            form_data.append('directorist_nonce', import_export_data.directorist_nonce );
            form_data.append('pre_mapped', true);
            $.ajax({
                method: 'POST',
                processData: false,
                contentType: false,
                // async: false,
                url: import_export_data.ajaxurl,
                data: form_data,
                success(response) {

                    if ( response.error ) {
                        console.log({ response });
                        return;
                    }

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

    $('.directorist-submit-importing').on('click', function(e) {
        e.preventDefault();
        // Add a class when the button is clicked
        $(this).addClass('loading');
        $( '.directorist_dummy_data_log' ).text( 'Preparing data...' );

        $('.directorist-setup-wizard__content').addClass('hidden');
        $('.middle-content-import').removeClass('hidden');

        let type_count = 0;
        var import_dummy = function () {

            var data = {
                action                 : 'directorist_setup_wizard',
                directorist_nonce      : import_export_data.directorist_nonce,
            };
    
            if ( $('input[name="directory_type_settings"]').is(':checked') ) {
                data.directory_type_settings = true;
            }
    
            if ( $('input[name="share_non_sensitive_data"]').is(':checked') ) {
                data.share_non_sensitive_data = true;
            }
    
            if ( $('input[name="import_listings"]').is(':checked') ) {
                data.import_listings = true;
            }
    
            if ( $('input[name="required_plugins"]').is(':checked') ) {
                data.required_plugins = true;
            }

            data.counter = type_count;
            
            $.ajax({
                method: 'POST',
                url   : import_export_data.ajaxurl,
                data  : data,
                success(response) {

                    console.log( response );

                    $( '.directorist-import-text-inner' ).empty().text( response.log );
                    if ( response.completed ) {

                        $( '.directorist-import-text-inner' ).empty().text( response.log );

                        window.location = response.url;
                    } 
                    
                    type_count++;
                    let progressPercentage = response.percentage;

                    $('.directorist-import-progress-bar').css('width', progressPercentage + '%');
                    $('.directorist-importer-progress').val(progressPercentage);
                    $('.directorist-import-progress-info-precent').text(progressPercentage + '%');

                    import_dummy();
                    
                },
            });

        };
        import_dummy();

    });

    // Reusable function to check and toggle the class based on the input value
    function handleInputFocus(inputElement) {
        if ($(inputElement).val().length > 0) {
            $(inputElement).parent('.directorist-search-field').addClass('input-is-focused');
        } else {
            $(inputElement).parent('.directorist-search-field').removeClass('input-is-focused');
        }
    }

    // Keyup event listener for user typing in the input field
    $('body').on('keyup', '.directorist-location-js', function(e) {
        e.preventDefault();
        handleInputFocus(this);
    });

    // Clear location input value
    $('body').on('click', '.directorist-setup-wizard__box__content__input--clear', function(e) {
        e.preventDefault();
        $(this).siblings('input').val('');
        $(this).parent('.directorist-search-field').removeClass('input-is-focused');
    });

    //options
    $('.atbdp-sw-gmap-key').hide();
    $('#select_map').on('change', function (e) {
        if ($(this).val() === 'google') {
            $('.atbdp-sw-gmap-key').show();
        } else {
            $('.atbdp-sw-gmap-key').hide();
        }
    });
    if ($('#select_map').val() === 'google') {
        $('.atbdp-sw-gmap-key').show();
    } else {
        $('.atbdp-sw-gmap-key').hide();
    }

    $('.atbdp-sw-featured-listing').hide();
    $('#enable_monetization').on('change', function () {
        if ($(this).prop("checked") === true) {
            $('.atbdp-sw-featured-listing').show();
        } else {
            $('.atbdp-sw-featured-listing').hide();
        }
    });
    if ($('#enable_monetization').prop("checked") === true) {
        $('.atbdp-sw-featured-listing').show();
    } else {
        $('.atbdp-sw-featured-listing').hide();
    }

    $('.atbdp-sw-listing-price').hide();
    $('#enable_featured_listing').on('change', function () {
        if ($(this).prop("checked") === true) {
            $('.atbdp-sw-listing-price').show();
        } else {
            $('.atbdp-sw-listing-price').hide();
        }
    });
    if ($('#enable_monetization').prop("checked") === true) {
        $('.atbdp-sw-listing-price').show();
    } else {
        $('.atbdp-sw-listing-price').hide();
    }

    /* custom select */
    $('#select_map').select2({
        minimumResultsForSearch: -1
    });
    $('#atbdp-listings-to-import').select2({
        minimumResultsForSearch: -1
    });


    // Setup Wizard
    $('#others-listing').on('change', function () {
        // $('.directorist-setup-wizard__checkbox--custom').slideToggle();
        if ($(this).is(':checked')) {
            $('.directorist-setup-wizard__checkbox--custom').slideDown();
        } else {
            $('.directorist-setup-wizard__checkbox--custom').slideUp();
        }
    });

    const setupWizardTypes = document.querySelectorAll('.directorist-setup-wizard__checkbox input[type="checkbox"]');
    const setupWizardTypeCounterDesc = document.querySelector('.directorist-setup-wizard__counter .directorist-setup-wizard__counter__desc');
    const setupWizardTypeCounterNotice = document.querySelector('.directorist-setup-wizard__notice');
    const setupWizardTypeNextStepBtn = document.querySelector('.directorist-setup-wizard__next .directorist-setup-wizard__btn--next');
    const setupWizardSelectedTypeCount = document.querySelector('.directorist-setup-wizard__counter .selected_count');
    const setupWizardTypesMaxCount = document.querySelector('.directorist-setup-wizard__counter .max_count');
    const setupWizardTypesMaxAllowed = 5;

    const handleSetupWizardTypeChange = () => {
        const setupWizardCheckedTypeCount = Array.from(setupWizardTypes).filter(checkbox => checkbox.checked).length;

        setupWizardSelectedTypeCount.textContent = setupWizardCheckedTypeCount;
        setupWizardTypesMaxCount.textContent = setupWizardTypesMaxAllowed;
        
        if (setupWizardCheckedTypeCount < 1) {
            setupWizardTypeCounterNotice.style.display = 'block';
            setupWizardTypeNextStepBtn.disabled = true;
        } else {
            setupWizardTypeCounterNotice.style.display = 'none';
            setupWizardTypeNextStepBtn.disabled = false;
        }

        if (setupWizardCheckedTypeCount >= setupWizardTypesMaxAllowed) {
            setupWizardTypeCounterDesc.style.display = 'block';
            setupWizardTypes.forEach(checkbox => {
                if (!checkbox.checked) {
                    checkbox.disabled = true;
                }
            });
        } else {
            setupWizardTypeCounterDesc.style.display = 'none';
            setupWizardTypes.forEach(checkbox => {
                checkbox.disabled = false;
            });
        }
    };

    setupWizardTypes.forEach(type => {
        type.addEventListener('change', handleSetupWizardTypeChange);
    });

});