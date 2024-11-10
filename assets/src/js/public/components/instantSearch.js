import debounce from '../../global/components/debounce';
;
(function ($) {

    let full_url       = window.location.href;

    // Update search URL with form data
    function update_instant_search_url(form_data) {
        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;

            if (form_data.paged && form_data.paged.length) {
                var query = '?paged=' + form_data.paged + '';
            }
            if (form_data.q && form_data.q.length) {
                var query = '?q=' + form_data.q;
            }
            if (form_data.in_cat && form_data.in_cat.length) {
                var query = (query && query.length) ? query + '&in_cat=' + form_data.in_cat : '?in_cat=' + form_data.in_cat;
            }
            if (form_data.in_loc && form_data.in_loc.length) {
                var query = (query && query.length) ? query + '&in_loc=' + form_data.in_loc : '?in_loc=' + form_data.in_loc;
            }
            if (form_data.in_tag && form_data.in_tag.length) {
                var query = (query && query.length) ? query + '&in_tag=' + form_data.in_tag : '?in_tag=' + form_data.in_tag;
            }
            if (form_data.price && form_data.price[0] && form_data.price[0] > 0) {
                var query = (query && query.length) ? query + '&price%5B0%5D=' + form_data.price[0] : '?price%5B0%5D=' + form_data.price[0];
            }
            if (form_data.price && form_data.price[1] && form_data.price[1] > 0) {
                var query = (query && query.length) ? query + '&price%5B1%5D=' + form_data.price[1] : '?price%5B1%5D=' + form_data.price[1];
            }
            if (form_data.price_range && form_data.price_range.length) {
                var query = (query && query.length) ? query + '&price_range=' + form_data.price_range : '?price_range=' + form_data.price_range;
            }
            if (form_data.search_by_rating && form_data.search_by_rating.length) {
                var query = (query && query.length) ? query + '&search_by_rating=' + form_data.search_by_rating : '?search_by_rating=' + form_data.search_by_rating;
            }
            if (form_data.cityLat && form_data.cityLat.length && form_data.address && form_data.address.length) {
                var query = (query && query.length) ? query + '&cityLat=' + form_data.cityLat : '?cityLat=' + form_data.cityLat;
            }
            if (form_data.cityLng && form_data.cityLng.length && form_data.address && form_data.address.length) {
                var query = (query && query.length) ? query + '&cityLng=' + form_data.cityLng : '?cityLng=' + form_data.cityLng;
            }
            if (form_data.miles && form_data.miles > 0) {
                var query = (query && query.length) ? query + '&miles=' + form_data.miles : '?miles=' + form_data.miles;
            }
            if (form_data.address && form_data.address.length) {
                var query = (query && query.length) ? query + '&address=' + form_data.address : '?address=' + form_data.address;
            }
            if (form_data.zip && form_data.zip.length) {
                var query = (query && query.length) ? query + '&zip=' + form_data.zip : '?zip=' + form_data.zip;
            }
            if (form_data.fax && form_data.fax.length) {
                var query = (query && query.length) ? query + '&fax=' + form_data.fax : '?fax=' + form_data.fax;
            }
            if (form_data.email && form_data.email.length) {
                var query = (query && query.length) ? query + '&email=' + form_data.email : '?email=' + form_data.email;
            }
            if (form_data.website && form_data.website.length) {
                var query = (query && query.length) ? query + '&website=' + form_data.website : '?website=' + form_data.website;
            }
            if (form_data.phone && form_data.phone.length) {
                var query = (query && query.length) ? query + '&phone=' + form_data.phone : '?phone=' + form_data.phone;
            }
            if (form_data.custom_field && form_data.custom_field.length) {
                var query = (query && query.length) ? query + '&custom_field=' + form_data.custom_field : '?custom_field=' + form_data.custom_field;
            }
            if (form_data.open_now && form_data.open_now.length) {
                var query = (query && query.length) ? query + '&open_now=' + form_data.open_now : '?open_now=' + form_data.open_now;
            }

            var newurl = query ? newurl + query : newurl;

            window.history.pushState({
                path: newurl
            }, '', newurl);
        }
    }

    // Get URL Parameter
    function getURLParameter( url, name ) {
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
        var results = regex.exec( url );
        if ( ! results || ! results[2] ) {
            return '';
        }
        return decodeURIComponent( results[2] );
    }

    // Close Search Modal
    function closeAllSearchModal() {
        var searchModalElement = document.querySelectorAll('.directorist-search-modal');

        searchModalElement.forEach((modal)=>{
            var modalOverlay = modal.querySelector('.directorist-search-modal__overlay');
            var modalContent = modal.querySelector('.directorist-search-modal__contents');

            // Overlay Style
            if(modalOverlay) {
                modalOverlay.style.cssText = "opacity: 0; visibility: hidden; transition: 0.5s ease";
            }

            // Modal Content Style
            if(modalContent) {
                modalContent.style.cssText = "opacity: 0; visibility: hidden; bottom: -200px;";
            }
        })
    }

    /* Directorist instant search */
    $('body').on("submit", ".directorist-instant-search form", function (e) {
        e.preventDefault();
        let instant_search_element = $(this).closest('.directorist-instant-search');
        let tag                    = [];
        let search_by_rating       = [];
        let price                  = [];
        let custom_field           = {};

        $(this).find('input[name^="in_tag["]:checked').each(function (index, el) {
            tag.push($(el).val())
        });

        $(this).find('input[name^="search_by_rating["]:checked').each(function (index, el) {
            search_by_rating.push($(el).val())
        });

        $(this).find('input[name^="price["]').each(function (index, el) {
            price.push($(el).val())
        });

        $(this).find('[name^="custom_field"]').each(function (index, el) {
            var name = $(el).attr('name');
            var type = $(el).attr('type');
            var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
                    value                 = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                if (!custom_field[post_id]) {
                    custom_field[post_id] = [];
                }
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
                    var value = $(this).val();
                    custom_field[post_id].push(value);
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let view_href      = instant_search_element.find(".directorist-viewas .directorist-viewas__item.active").attr('href');
        let view_as        = (view_href && view_href.length) ? view_href.match(/view=.+/) : '';
        let view           = (view_as && view_as.length) ? view_as[0].replace(/view=/, '') : '';
        let type_href      = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
        let type           = (type_href && type_href.length) ? type_href.match(/directory_type=.+/) : '';
        let directory_type = getURLParameter(type_href, 'directory_type');
        let data_atts      = instant_search_element.attr('data-atts');

        var data = {
            action          : 'directorist_instant_search',
            _nonce          : directorist.ajax_nonce,
            current_page_id : directorist.current_page_id,
            in_tag          : tag,
            price           : price,
            search_by_rating: search_by_rating,
            custom_field    : custom_field,
            data_atts       : JSON.parse(data_atts)
        };

        var fields = {
            q               : $(this).find('input[name="q"]').val(),
            in_cat          : $(this).find('.directorist-category-select').val(),
            in_loc          : $(this).find('.directorist-location-select').val(),
            price_range     : $(this).find("input[name='price_range']:checked").val(),
            address         : $(this).find('input[name="address"]').val(),
            zip             : $(this).find('input[name="zip"]').val(),
            fax             : $(this).find('input[name="fax"]').val(),
            email           : $(this).find('input[name="email"]').val(),
            website         : $(this).find('input[name="website"]').val(),
            phone           : $(this).find('input[name="phone"]').val(),
        };

        //business hours
        if ( $('input[name="open_now"]').is(':checked') ) {
            fields.open_now = $(this).find('input[name="open_now"]').val();
        }

        if (fields.address && fields.address.length) {
            fields.cityLat = $(this).find('#cityLat').val();
            fields.cityLng = $(this).find('#cityLng').val();
            fields.miles = $(this).find('.directorist-custom-range-slider__value input').val();
        }

        if (fields.zip && fields.zip.length) {
            fields.zip_cityLat = $(this).find('.zip-cityLat').val();
            fields.zip_cityLng = $(this).find('.zip-cityLng').val();
            fields.miles = $(this).find('.directorist-custom-range-slider__value input').val();
        }

        var form_data = {
            ...data,
            ...fields
        };

        const allFieldsAreEmpty    = Object.values(fields).every(item => !item);
        const tagFieldEmpty        = data.in_tag.every(item => !item);
        const priceFieldEmpty      = data.price.every(item => !item);
        const ratingFieldEmpty     = data.search_by_rating.every(item => !item);
        const customFieldsAreEmpty = Object.values(data.custom_field).every(item => !item);

        if ( !allFieldsAreEmpty || !tagFieldEmpty || !priceFieldEmpty || !customFieldsAreEmpty || !ratingFieldEmpty ) {

            if (view && view.length) {
                form_data.view = view
            }

            if (directory_type && directory_type.length) {
                form_data.directory_type = directory_type;
            }

            update_instant_search_url(form_data);

            $.ajax({
                url: directorist.ajaxurl,
                type: "POST",
                data: form_data,
                beforeSend: function () {
                    instant_search_element.find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", true);
                    instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
                    instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show')
                    instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').hide();
                    $(document).scrollTop(instant_search_element.offset().top);
                    closeAllSearchModal();
                },
                success: function (html) {
                    if (html.search_result) {
                        instant_search_element.find('.directorist-header-found-title').remove();
                        instant_search_element.find('.dsa-save-search-container').remove();
                        instant_search_element.find('.directorist-listings-header__left').append(html.header_title);
                        instant_search_element.find('.directorist-header-found-title span').text(html.count);
                        instant_search_element.find('.directorist-archive-items').replaceWith(html.search_result);
                        instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
                        instant_search_element.find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false)
                        window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                        window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));

                        var website_name = directorist.site_name; // This is dynamically set from WordPress

                        // Construct the new meta title
                        var new_meta_title = ''; // Start with an empty title
                        // Check if the category is selected and append to the title
                        if ( String( html.category_name ) ) {
                            new_meta_title += html.category_name;
                        }

                        // Check if location is selected and append with proper formatting
                        if ( String( html.location_name ) ) {
                            if ( String( html.category_name ) ) {
                                new_meta_title += ' within ' + html.location_name; // If category exists, add with a comma
                            } else {
                                new_meta_title += html.location_name; // If no category, just add location
                            }
                        }

                        // Check if address is selected and append with proper formatting
                        if (fields.address) {
                            if (fields.in_cat || fields.in_loc) {
                                new_meta_title += ' near ' + fields.address; // If category or location exists, add "near"
                            } else {
                                new_meta_title += fields.address; // Default to just the address
                            }
                        }

                        // Append website name to the meta title with a pipe separator
                        if (new_meta_title) {
                            new_meta_title += ' | ' + website_name; // Append the website name only if the title has content
                        } else {
                            new_meta_title = website_name; // Default to only the website name if no other title parts are present
                        }

                        // Update the meta title dynamically
                        document.title = new_meta_title;
                    }
                }
            });
        }
    });

    /* Directorist instant reset */
    $('body').on("click", ".directorist-instant-search .directorist-btn-reset-js", function (e) {
        e.preventDefault();
        let instant_search_element = $(this).closest('.directorist-instant-search');
        let tag                    = [];
        let search_by_rating       = [];
        let price                  = [];
        let custom_field           = {};

        $(this).find('input[name^="in_tag["]:checked').each(function (index, el) {
            tag.push($(el).val())
        });

        $(this).find('input[name^="search_by_rating["]:checked').each(function (index, el) {
            search_by_rating.push($(el).val())
        });

        $(this).find('input[name^="price["]').each(function (index, el) {
            price.push($(el).val())
        });

        $(this).find('[name^="custom_field"]').each(function (index, el) {
            var name = $(el).attr('name');
            var type = $(el).attr('type');
            var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
                    value                 = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
                    var checkValue = [];
                        value      = $(this).val();
                    checkValue.push(value);
                    custom_field[post_id] = checkValue;
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let view_href      = instant_search_element.find(".directorist-viewas .directorist-viewas__item.active").attr('href');
        let view_as        = (view_href && view_href.length) ? view_href.match(/view=.+/) : '';
        let view           = (view_as && view_as.length) ? view_as[0].replace(/view=/, '') : '';
        let type_href      = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
        let type           = (type_href && type_href.length) ? type_href.match(/directory_type=.+/) : '';
        let directory_type = getURLParameter(type_href, 'directory_type');
        let data_atts      = instant_search_element.attr('data-atts');

        var data = {
            action          : 'directorist_instant_search',
            _nonce          : directorist.ajax_nonce,
            current_page_id : directorist.current_page_id,
            data_atts       : JSON.parse(data_atts)
        };

        var form_data = {
            ...data,
        };

        if (view && view.length) {
            form_data.view = view
        }

        if (directory_type && directory_type.length) {
            form_data.directory_type = directory_type;
        }

        update_instant_search_url(form_data);

        $.ajax({
            url: directorist.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                instant_search_element.find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", true);
                instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
                instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show')
                instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').hide();
                $(document).scrollTop(instant_search_element.offset().top);
            },
            success: function (html) {
                if (html.search_result) {
                    instant_search_element.find('.directorist-header-found-title span').text(html.count);
                    instant_search_element.find('.directorist-archive-items').replaceWith(html.search_result);
                    instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
                    instant_search_element.find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false)
                    window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                    window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
                }
            }
        });
    });

    $('body').on("submit", ".widget .default-ad-search:not(.directorist_single) .directorist-advanced-filter__form", function (e) {
        if ($('.directorist-instant-search').length) {
            e.preventDefault();
            let _this            = $(this);
            let tag              = [];
            let price            = [];
            let search_by_rating = [];
            let custom_field     = {};

            $(this).find('input[name^="in_tag["]:checked').each(function (index, el) {
                tag.push($(el).val())
            });

            $(this).find('input[name^="search_by_rating["]:checked').each(function (index, el) {
                search_by_rating.push($(el).val())
            });

            $(this).find('input[name^="price["]').each(function (index, el) {
                price.push($(el).val())
            });

            $(this).find('[name^="custom_field"]').each(function (index, el) {
                var name = $(el).attr('name');
                var type = $(el).attr('type');
                var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
                if ('radio' === type) {
                    $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
                        value                 = $(this).val();
                        custom_field[post_id] = value;
                    });
                } else if ('checkbox' === type) {
                    post_id = post_id.split('[]')[0];
                    if (!custom_field[post_id]) {
                        custom_field[post_id] = [];
                    }
                    $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
                        var value = $(this).val();
                        custom_field[post_id].push(value);
                    });
                } else {
                    var value = $(el).val();
                    custom_field[post_id] = value;
                }
            });

            let view_href      = $(".directorist-viewas .directorist-viewas__item.active").attr('href');
            let view_as        = (view_href && view_href.length) ? view_href.match(/view=.+/) : '';
            let view           = (view_as && view_as.length) ? view_as[0].replace(/view=/, '') : '';
            let type_href      = $('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
            let type           = (type_href && type_href.length) ? type_href.match(/directory_type=.+/) : '';
            let directory_type = getURLParameter(type_href, 'directory_type');
            let data_atts      = $(this).closest('.directorist-instant-search').attr('data-atts');

            var data = {
                action          : 'directorist_instant_search',
                _nonce          : directorist.ajax_nonce,
                current_page_id : directorist.current_page_id,
                in_tag          : tag,
                price           : price,
                search_by_rating: search_by_rating,
                custom_field    : custom_field,
                data_atts       : JSON.parse(data_atts)
            };

            var fields = {
                q               : $(this).find('input[name="q"]').val(),
                in_cat          : $(this).find('.directorist-category-select').val(),
                in_loc          : $(this).find('.directorist-location-select').val(),
                price_range     : $(this).find("input[name='price_range']:checked").val(),
                address         : $(this).find('input[name="address"]').val(),
                zip             : $(this).find('input[name="zip"]').val(),
                fax             : $(this).find('input[name="fax"]').val(),
                email           : $(this).find('input[name="email"]').val(),
                website         : $(this).find('input[name="website"]').val(),
                phone           : $(this).find('input[name="phone"]').val(),
            };

            if ( $('input[name="open_now"]').is(':checked') ) {
                fields.open_now = $(this).find('input[name="open_now"]').val();
            }

            if (fields.address && fields.address.length) {
                fields.cityLat = $(this).find('#cityLat').val();
                fields.cityLng = $(this).find('#cityLng').val();
                fields.miles = $(this).find('input[name="miles"]').val();
            }

            if (fields.zip && fields.zip.length) {
                fields.zip_cityLat = $(this).find('.zip-cityLat').val();
                fields.zip_cityLng = $(this).find('.zip-cityLng').val();
                fields.miles       = $(this).find('.directorist-custom-range-slider__value input').val();
            }

            var form_data = {
                ...data,
                ...fields
            };

            const allFieldsAreEmpty    = Object.values(fields).every(item => !item);
            const tagFieldEmpty        = data.in_tag.every(item => !item);
            const priceFieldEmpty      = data.price.every(item => !item);
            const ratingFieldEmpty     = data.search_by_rating.every(item => !item);
            const customFieldsAreEmpty = Object.values(data.custom_field).every(item => !item);

            if ( !allFieldsAreEmpty || !tagFieldEmpty || !priceFieldEmpty || !customFieldsAreEmpty || !ratingFieldEmpty ) {

                if (view && view.length) {
                    form_data.view = view
                }

                if (directory_type && directory_type.length) {
                    form_data.directory_type = directory_type;
                }

                update_instant_search_url(form_data);

                $.ajax({
                    url       : directorist.ajaxurl,
                    type      : "POST",
                    data      : form_data,
                    beforeSend: function () {
                        $('.directorist-archive-contents').find('.directorist-archive-items').addClass('atbdp-form-fade');
                        $('.directorist-archive-contents').find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show')
                        $('.directorist-archive-contents').find('.directorist-header-bar .directorist-advanced-filter').hide();
                        $(document).scrollTop($(".directorist-archive-contents").offset().top);
                    },
                    success: function (html) {
                        if (html.search_result) {
                            $('.directorist-archive-contents').find('.directorist-header-found-title span').text(html.count);
                            $('.directorist-archive-contents').find('.directorist-archive-items').replaceWith(html.search_result);
                            $('.directorist-archive-contents').find('.directorist-archive-items').removeClass('atbdp-form-fade');
                            $('.directorist-archive-contents').find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false)
                            window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                            window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
                        }
                    }
                });
            }
        }
    });

    // Directorist type changes
    $('body').on("click", ".directorist-instant-search .directorist-type-nav__link", function (e) {
        e.preventDefault();
        let _this     = $(this);
        let type_href = $(this).attr('href');
        let type      = type_href.match(/directory_type=.+/);
        //let directory_type = ( type && type.length ) ? type[0].replace( /directory_type=/, '' ) : '';
        let directory_type = getURLParameter(type_href, 'directory_type');
        let data_atts      = $(this).closest('.directorist-instant-search').attr('data-atts');
        var form_data      = {
            action         : 'directorist_instant_search',
            _nonce         : directorist.ajax_nonce,
            current_page_id: directorist.current_page_id,
            directory_type : directory_type,
            data_atts      : JSON.parse(data_atts)
        };

        update_instant_search_url(form_data);

        $.ajax({
            url       : directorist.ajaxurl,
            type      : "POST",
            data      : form_data,
            beforeSend: function () {
                $(_this).closest('.directorist-instant-search').addClass('atbdp-form-fade');
            },
            success: function (html) {
                if (html.directory_type) {
                    $(_this).closest('.directorist-instant-search').replaceWith(html.directory_type);
                    $(_this).closest('.directorist-instant-search').find( '.atbdp-form-fade' ).removeClass('atbdp-form-fade');
                    window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                    window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
                }
                let events = [
                    new CustomEvent('directorist-instant-search-reloaded'),
                    new CustomEvent('directorist-search-form-nav-tab-reloaded'),
                    new CustomEvent('directorist-reload-select2-fields'),
                    new CustomEvent('directorist-reload-map-api-field'),
                ];

                events.forEach(event => {
                    document.body.dispatchEvent(event);
                    window.dispatchEvent(event);
                });
            }
        });
    })

    $('body').on("click", ".disabled-link", function (e) {
        e.preventDefault();
    })

    // Directorist view as changes
    $('body').on("click", ".directorist-instant-search .directorist-viewas .directorist-viewas__item", function (e) {
        e.preventDefault();
        let instant_search_element = $(this).closest('.directorist-instant-search');
        let tag          = [];
        let price        = [];
        let custom_field = {};

        let sort_href      = $(this).closest(".directorist-sortby-dropdown .directorist-dropdown__links__single.active").attr('data-link');
        let sort_by        = (sort_href && sort_href.length) ? sort_href.match(/sort=.+/) : '';
        let sort           = (sort_by && sort_by.length) ? sort_by[0].replace(/sort=/, '') : '';
        let view_href      = $(this).closest(this).attr('href');
        let view           = (view_href && view_href.length) ? view_href.match(/view=.+/) : '';
        let type_href      = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
        let type           = (type_href && type_href.length) ? type_href.match(/directory_type=.+/) : '';
        let directory_type = getURLParameter(type_href, 'directory_type');
        let page_no        = $(this).closest(".page-numbers.current").text();
        let data_atts      = instant_search_element.attr('data-atts');

        // Select Active Form Based on Screen Size
        const advancedForm = instant_search_element.find('.directorist-advanced-filter__form');
        const searchForm  = instant_search_element.find('.directorist-search-form');
        const sidebarListing = instant_search_element.find('.listing-with-sidebar');
        const activeForm = sidebarListing.length ? instant_search_element : screen.width > 575 ? advancedForm : searchForm;

        // Get Values from Active Form
        activeForm.find('input[name^="in_tag["]:checked').each(function (index, el) {
            tag.push($(el).val())
        });

        activeForm.find('input[name^="price["]').each(function (index, el) {
            price.push($(el).val())
        });

        activeForm.find('[name^="custom_field"]').each(function (index, el) {
            var test    = $(el).attr('name');
            var type    = $(el).attr('type');
            var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');

            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
                    value                 = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                if (!custom_field[post_id]) {
                    custom_field[post_id] = [];
                }
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
                    var value = $(this).val();
                    custom_field[post_id].push(value);
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let q                = activeForm.find('input[name="q"]').val();
        let in_cat           = activeForm.find('.directorist-category-select').val();
        let in_loc           = activeForm.find('.directorist-location-select').val();
        let price_range      = activeForm.find("input[name='price_range']:checked").val();
        let search_by_rating = activeForm.find('select[name=search_by_rating]').val();
        let cityLat          = activeForm.find('#cityLat').val();
        let cityLng          = activeForm.find('#cityLng').val();
        let miles            = activeForm.find('input[name="miles"]').val();
        let address          = activeForm.find('input[name="address"]').val();
        let zip              = activeForm.find('input[name="zip"]').val();
        let fax              = activeForm.find('input[name="fax"]').val();
        let email            = activeForm.find('input[name="email"]').val();
        let website          = activeForm.find('input[name="website"]').val();
        let phone            = activeForm.find('input[name="phone"]').val();

        $(".directorist-viewas .directorist-viewas__item").removeClass('active');
        $(this).addClass("active");

        var form_data = {
            action          : 'directorist_instant_search',
            _nonce          : directorist.ajax_nonce,
            current_page_id : directorist.current_page_id,
            view            : ( view && view.length ) ? view[0].replace(/view=/, '') : '',
            q               : q || getURLParameter( full_url, 'q' ),
            in_cat          : in_cat || getURLParameter( full_url, 'in_cat' ),
            in_loc          : in_loc || getURLParameter( full_url, 'in_loc' ),
            in_tag          : tag || getURLParameter( full_url, 'in_tag' ),
            price           : price || getURLParameter( full_url, 'price' ),
            price_range     : price_range || getURLParameter( full_url, 'price_range' ),
            search_by_rating: search_by_rating || getURLParameter( full_url, 'search_by_rating' ),
            cityLat         : cityLat || getURLParameter( full_url, 'cityLat' ),
            cityLng         : cityLng || getURLParameter( full_url, 'cityLng' ),
            miles           : miles || getURLParameter( full_url, 'miles' ),
            address         : address || getURLParameter( full_url, 'address' ),
            zip             : zip || getURLParameter( full_url, 'zip' ),
            fax             : fax || getURLParameter( full_url, 'fax' ),
            email           : email || getURLParameter( full_url, 'email' ),
            website         : website || getURLParameter( full_url, 'website' ),
            phone           : phone || getURLParameter( full_url, 'phone' ),
            custom_field    : custom_field || getURLParameter( full_url, 'custom_field' ),
            data_atts       : JSON.parse(data_atts)
        };

        //business hours
        if ( $('input[name="open_now"]').is(':checked') ) {
            form_data.open_now = activeForm.find('input[name="open_now"]').val();
        }

        if (page_no && page_no.length) {
            form_data.paged = page_no;
        }

        if (directory_type && directory_type.length) {
            form_data.directory_type = directory_type;
        }

        if (sort && sort.length) {
            form_data.sort = sort
        }

        $.ajax({
            url: directorist.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
                instant_search_element.find('.directorist-viewas-dropdown .directorist-dropdown__links__single').addClass("disabled-link");
                instant_search_element.find('.directorist-dropdown__links-js a').removeClass('directorist-dropdown__links__single');
                instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
                instant_search_element.find('.directorist-dropdown__links').hide();
                instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show')
                instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').css('visibility', 'hidden');
                //$(document).scrollTop( $(this).closest(".directorist-instant-search").offset().top );
            },
            success: function (html) {
                if (html.view_as) {
                    instant_search_element.find('.directorist-header-found-title span').text(html.count);
                    instant_search_element.find('.directorist-archive-items').replaceWith(html.view_as);
                    instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
                    instant_search_element.find('.directorist-viewas-dropdown .directorist-dropdown__links__single').removeClass("disabled-link");
                    instant_search_element.find('.directorist-dropdown__links-js a').addClass('directorist-dropdown__links__single');

                    window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                    window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
                    instant_search_element.find('.directorist-header-bar .directorist-advanced-filter').css('visibility', 'visible');
                }
            }
        });
    });

    $('.directorist-instant-search .directorist-dropdown__links__single-js').off('click');

    // Directorist sort by changes
    $('body').on("click", ".directorist-instant-search .directorist-sortby-dropdown .directorist-dropdown__links__single-js", function (e) {
        e.preventDefault();
        let instant_search_element = $(this).closest('.directorist-instant-search');
        let tag                    = [];
        let price                  = [];
        let custom_field           = {};

        let view_href        = instant_search_element.find(".directorist-viewas .directorist-viewas__item.active").attr('href');
        let view_as          = (view_href && view_href.length) ? view_href.match(/view=.+/) : '';
        let view             = (view_as && view_as.length) ? view_as[0].replace(/view=/, '') : '';
        let sort_href        = $(this).closest(this).attr('data-link');
        let sort_by          = sort_href.match(/sort=.+/);
        let type_href        = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
        let type             = (type_href && type_href.length) ? type_href.match(/directory_type=.+/) : '';
        let directory_type   = getURLParameter(type_href, 'directory_type');
        let data_atts        = instant_search_element.attr('data-atts');

        instant_search_element.find(".directorist-sortby-dropdown .directorist-dropdown__links__single").removeClass('active');
        $(this).addClass("active");

        // Select Active Form Based on Screen Size
        const advancedForm = instant_search_element.find('.directorist-advanced-filter__form');
        const searchForm  = instant_search_element.find('.directorist-search-form');
        const sidebarListing = instant_search_element.find('.listing-with-sidebar');
        const activeForm = sidebarListing.length ? instant_search_element : screen.width > 575 ? advancedForm : searchForm;

        // Get Values from Active Form
        activeForm.find('input[name^="in_tag["]:checked').each(function (index, el) {
            tag.push($(el).val())
        });

        activeForm.find('input[name^="price["]').each(function (index, el) {
            price.push($(el).val())
        });

        activeForm.find('[name^="custom_field"]').each(function (index, el) {
            var test    = $(el).attr('name');
            var type    = $(el).attr('type');
            var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');
            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
                    value = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                if (!custom_field[post_id]) {
                    custom_field[post_id] = [];
                }
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
                    var value = $(this).val();
                    custom_field[post_id].push(value);
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let q                = activeForm.find('input[name="q"]').val();
        let in_cat           = activeForm.find('.directorist-category-select').val();
        let in_loc           = activeForm.find('.directorist-location-select').val();
        let price_range      = activeForm.find("input[name='price_range']:checked").val();
        let search_by_rating = activeForm.find('select[name=search_by_rating]').val();
        let cityLat          = activeForm.find('#cityLat').val();
        let cityLng          = activeForm.find('#cityLng').val();
        let miles            = activeForm.find('input[name="miles"]').val();
        let address          = activeForm.find('input[name="address"]').val();
        let zip              = activeForm.find('input[name="zip"]').val();
        let fax              = activeForm.find('input[name="fax"]').val();
        let email            = activeForm.find('input[name="email"]').val();
        let website          = activeForm.find('input[name="website"]').val();
        let phone            = activeForm.find('input[name="phone"]').val();

        var form_data = {
            action          : 'directorist_instant_search',
            _nonce          : directorist.ajax_nonce,
            current_page_id : directorist.current_page_id,
            sort            : (sort_by && sort_by.length) ? sort_by[0].replace(/sort=/, '') : '',
            q               : q || getURLParameter( full_url, 'q' ),
            in_cat          : in_cat || getURLParameter( full_url, 'in_cat' ),
            in_loc          : in_loc || getURLParameter( full_url, 'in_loc' ),
            in_tag          : tag || getURLParameter( full_url, 'in_tag' ),
            price           : price || getURLParameter( full_url, 'price' ),
            price_range     : price_range || getURLParameter( full_url, 'price_range' ),
            search_by_rating: search_by_rating || getURLParameter( full_url, 'search_by_rating' ),
            cityLat         : cityLat || getURLParameter( full_url, 'cityLat' ),
            cityLng         : cityLng || getURLParameter( full_url, 'cityLng' ),
            miles           : miles || getURLParameter( full_url, 'miles' ),
            address         : address || getURLParameter( full_url, 'address' ),
            zip             : zip || getURLParameter( full_url, 'zip' ),
            fax             : fax || getURLParameter( full_url, 'fax' ),
            email           : email || getURLParameter( full_url, 'email' ),
            website         : website || getURLParameter( full_url, 'website' ),
            phone           : phone || getURLParameter( full_url, 'phone' ),
            custom_field    : custom_field || getURLParameter( full_url, 'custom_field' ),
            view            : view,
            data_atts       : JSON.parse(data_atts)
        };

        //business hours
        if ( $('input[name="open_now"]').is(':checked') ) {
            form_data.open_now = activeForm.find('input[name="open_now"]').val();
        }

        if (directory_type && directory_type.length) {
            form_data.directory_type = directory_type;
        }

        $.ajax({
            url: directorist.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                instant_search_element.find('.directorist-sortby-dropdown .directorist-dropdown__links__single-js').addClass("disabled-link");
                instant_search_element.find('.directorist-dropdown__links-js a').removeClass('directorist-dropdown__links__single-js');
                instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
                instant_search_element.find('.directorist-dropdown__links').hide();
                const advance_filter = instant_search_element.find('.directorist-header-bar .directorist-advanced-filter')[0];
                $(advance_filter).removeClass('directorist-advanced-filter--show')
                $(advance_filter).hide();
                $(document).scrollTop( instant_search_element.offset().top );
            },
            success: function (html) {
                if (html.view_as) {
                    instant_search_element.find('.directorist-header-found-title span').text(html.count);
                    instant_search_element.find('.directorist-archive-items').replaceWith(html.view_as);
                    instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
                    instant_search_element.find('.directorist-sortby-dropdown .directorist-dropdown__links__single-js').removeClass("disabled-link");
                    instant_search_element.find('.directorist-dropdown__links-js a').addClass('directorist-dropdown__links__single-js');
                }
                window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
            }
        });
    });

    // Directorist pagination
    $('body').on("click", ".directorist-instant-search .directorist-pagination .page-numbers", function (e) {
        e.preventDefault();
        let tag                    = [];
        let price                  = [];
        let custom_field           = {};
        let instant_search_element = $(this).closest('.directorist-instant-search');

        let sort_href      = instant_search_element.find(".directorist-sortby-dropdown .directorist-dropdown__links__single.active").attr('data-link');
        let sort_by        = (sort_href && sort_href.length) ? sort_href.match(/sort=.+/) : '';
        let sort           = (sort_by && sort_by.length) ? sort_by[0].replace(/sort=/, '') : '';
        let view_href      = instant_search_element.find(".directorist-viewas .directorist-viewas__item.active").attr('href');
        let view_as        = (view_href && view_href.length) ? view_href.match(/view=.+/) : '';
        let view           = (view_as && view_as.length) ? view_as[0].replace(/view=/, '') : '';
        let type_href      = instant_search_element.find('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
        let type           = (type_href && type_href.length) ? type_href.match(/directory_type=.+/) : '';
        let directory_type = getURLParameter(type_href, 'directory_type');
        let data_atts      = instant_search_element.attr('data-atts');

        // Select Active Form Based on Screen Size
        const advancedForm = instant_search_element.find('.directorist-advanced-filter__form');
        const searchForm  = instant_search_element.find('.directorist-search-form');
        const sidebarListing = instant_search_element.find('.listing-with-sidebar');
        const activeForm = sidebarListing.length ? instant_search_element : screen.width > 575 ? advancedForm : searchForm;

        // Get Values from Active Form
        activeForm.find('input[name^="in_tag["]:checked').each(function (index, el) {
            tag.push($(el).val())
        });

        activeForm.find('input[name^="price["]').each(function (index, el) {
            price.push($(el).val())
        });

        activeForm.find('[name^="custom_field"]').each(function (index, el) {
            var test    = $(el).attr('name');
            var type    = $(el).attr('type');
            var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');
            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
                    value                 = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                if (!custom_field[post_id]) {
                    custom_field[post_id] = [];
                }
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
                    var value = $(this).val();
                    custom_field[post_id].push(value);
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let q                = activeForm.find('input[name="q"]').val();
        let in_cat           = activeForm.find('.directorist-category-select').val();
        let in_loc           = activeForm.find('.directorist-location-select').val();
        let price_range      = activeForm.find("input[name='price_range']:checked").val();
        let search_by_rating = activeForm.find('select[name=search_by_rating]').val();
        let cityLat          = activeForm.find('#cityLat').val();
        let cityLng          = activeForm.find('#cityLng').val();
        let miles            = activeForm.find('input[name="miles"]').val();
        let address          = activeForm.find('input[name="address"]').val();
        let zip              = activeForm.find('input[name="zip"]').val();
        let fax              = activeForm.find('input[name="fax"]').val();
        let email            = activeForm.find('input[name="email"]').val();
        let website          = activeForm.find('input[name="website"]').val();
        let phone            = activeForm.find('input[name="phone"]').val();

        instant_search_element.find(".directorist-pagination .page-numbers").removeClass('current');
        $(this).addClass("current");

        var paginate_link = $(this).attr('href');
        var page          = ( paginate_link && paginate_link.length ) ? paginate_link.match(/page\/.+/) : '';
        var page_value    = (page && page.length) ? page[0].replace(/page\//, '') : '';
        var page_no       = (page_value && page_value.length) ? page_value.replace(/\//, '') : '';
        if (!page_no) {
            var page    = ( paginate_link && paginate_link.length ) ? paginate_link.match(/paged=.+/) : '';
            var page_no = (page && page.length) ? page[0].replace(/paged=/, '') : '';
        }

        var form_data = {
            action          : 'directorist_instant_search',
            _nonce          : directorist.ajax_nonce,
            current_page_id : directorist.current_page_id,
            view            : (view && view.length) ? view[0].replace(/view=/, '') : '',
            q               : q || getURLParameter( full_url, 'q' ),
            in_cat          : in_cat || getURLParameter( full_url, 'in_cat' ),
            in_loc          : in_loc || getURLParameter( full_url, 'in_loc' ),
            in_tag          : tag || getURLParameter( full_url, 'in_tag' ),
            price           : price || getURLParameter( full_url, 'price' ),
            price_range     : price_range || getURLParameter( full_url, 'price_range' ),
            search_by_rating: search_by_rating || getURLParameter( full_url, 'search_by_rating' ),
            cityLat         : cityLat || getURLParameter( full_url, 'cityLat' ),
            cityLng         : cityLng || getURLParameter( full_url, 'cityLng' ),
            miles           : miles || getURLParameter( full_url, 'miles' ),
            address         : address || getURLParameter( full_url, 'address' ),
            zip             : zip || getURLParameter( full_url, 'zip' ),
            fax             : fax || getURLParameter( full_url, 'fax' ),
            email           : email || getURLParameter( full_url, 'email' ),
            website         : website || getURLParameter( full_url, 'website' ),
            phone           : phone || getURLParameter( full_url, 'phone' ),
            custom_field    : custom_field || getURLParameter( full_url, 'custom_field' ),
            view            : view,
            paged           : page_no,
            data_atts       : JSON.parse(data_atts)
        };

        //business hours
        if ( $('input[name="open_now"]').is(':checked') ) {
            form_data.open_now = activeForm.find('input[name="open_now"]').val();
        }

        update_instant_search_url(form_data);

        if (directory_type && directory_type.length) {
            form_data.directory_type = directory_type;
        }

        if (sort && sort.length) {
            form_data.sort = sort
        }

        $.ajax({
            url: directorist.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                instant_search_element.find('.directorist-archive-items').addClass('atbdp-form-fade');
            },
            success: function (html) {
                if (html.view_as) {
                    instant_search_element.find('.directorist-header-found-title span').text(html.count);
                    instant_search_element.find('.directorist-archive-items').replaceWith(html.view_as);
                    instant_search_element.find('.directorist-archive-items').removeClass('atbdp-form-fade');
                    $(document).scrollTop( instant_search_element.offset().top );
                }
                window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
            }
        });
    });

    // Filter on AJAX Search
    function filterListing(searchElm) {
        if (!searchElm) {
            return;
        }

        let _this            = searchElm;
        let tag              = [];
        let price            = [];
        let search_by_rating = [];
        let custom_field     = {};

        searchElm.find('input[name^="in_tag[]"]:checked').each(function (index, el) {
            tag.push($(el).val());
        });

        searchElm.find('input[name^="search_by_rating[]"]:checked').each(function (index, el) {
            search_by_rating.push($(el).val())
        });

        searchElm.find('input[name^="price["]').each(function (index, el) {
            price.push($(el).val())
        });

        searchElm.find('[name^="custom_field"]').each(function (index, el) {
            var name = $(el).attr('name');
            var type = $(el).attr('type');
            var post_id = name.replace(/(custom_field\[)/, '').replace(/\]/, '');
            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function () {
                    value                 = $(this).val();;
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                if (!custom_field[post_id]) {
                    custom_field[post_id] = [];
                }
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function () {
                    var value = $(this).val();
                    custom_field[post_id].push(value);
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let view_href      = $(".directorist-viewas .directorist-viewas__item.active").attr('href');
        let view_as        = (view_href && view_href.length) ? view_href.match(/view=.+/) : '';
        let view           = (view_as && view_as.length) ? view_as[0].replace(/view=/, '') : '';
        let type_href      = $('.directorist-type-nav__list .directorist-type-nav__list__current a').attr('href');
        let type           = (type_href && type_href.length) ? type_href.match(/directory_type=.+/) : '';
        let directory_type = getURLParameter(type_href, 'directory_type');
        let data_atts      = $('.directorist-instant-search').attr('data-atts');

        var data = {
            action          : 'directorist_instant_search',
            _nonce          : directorist.ajax_nonce,
            current_page_id : directorist.current_page_id,
            in_tag          : tag,
            price           : price,
            search_by_rating: search_by_rating,
            custom_field    : custom_field,
            data_atts       : JSON.parse(data_atts)
        };

        var fields = {
            q               : searchElm.find('input[name="q"]').val(),
            in_cat          : searchElm.find('.directorist-category-select').val(),
            in_loc          : searchElm.find('.directorist-location-select').val(),
            price_range     : searchElm.find("input[name='price_range']:checked").val(),
            address         : searchElm.find('input[name="address"]').val(),
            zip             : searchElm.find('input[name="zip"]').val(),
            fax             : searchElm.find('input[name="fax"]').val(),
            email           : searchElm.find('input[name="email"]').val(),
            website         : searchElm.find('input[name="website"]').val(),
            phone           : searchElm.find('input[name="phone"]').val(),
        };

        //business hours
        if ( $('input[name="open_now"]').is(':checked') ) {
            fields.open_now = searchElm.find('input[name="open_now"]').val();
        }

        if (fields.address && fields.address.length) {
            fields.cityLat = searchElm.find('#cityLat').val();
            fields.cityLng = searchElm.find('#cityLng').val();
            fields.miles = searchElm.find('input[name="miles"]').val();
        }

        if (fields.zip && fields.zip.length) {
            fields.zip_cityLat = searchElm.find('.zip-cityLat').val();
            fields.zip_cityLng = searchElm.find('.zip-cityLng').val();
            fields.miles = searchElm.find('input[name="miles"]').val();
        }

        var form_data = {
            ...data,
            ...fields
        };

        if (view && view.length) {
            form_data.view = view
        }

        if (directory_type && directory_type.length) {
            form_data.directory_type = directory_type;
        }

        update_instant_search_url(form_data);

        $.ajax({
            url: directorist.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                $(_this).closest('.directorist-instant-search').find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", true);
                $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').addClass('atbdp-form-fade');
                $(_this).closest('.directorist-instant-search').find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show')
                $(_this).closest('.directorist-instant-search').find('.directorist-header-bar .directorist-advanced-filter').hide();
                if ($(".directorist-instant-search").offset() > 0) {
                    $(document).scrollTop($(_this).closest(".directorist-instant-search").offset().top);
                }
            },
            success: function (html) {
                if (html.search_result) {
                    $(_this).closest('.directorist-instant-search').find('.directorist-header-found-title').remove();
                    $(_this).closest('.directorist-instant-search').find('.dsa-save-search-container').remove();
                    if( String( html.header_title ) ) {
                        $(_this).closest('.directorist-instant-search').find('.directorist-listings-header__left').append(html.header_title);
                        $(_this).closest('.directorist-instant-search').find('.directorist-header-found-title span').text(html.count);
                    }
                    $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').replaceWith(html.search_result);
                    $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').removeClass('atbdp-form-fade');
                    $(_this).closest('.directorist-instant-search').find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false)
                    window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                    window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));

                    var website_name = directorist.site_name; // This is dynamically set from WordPress

                    // Construct the new meta title
                    var new_meta_title = ''; // Start with an empty title
                    // Check if the category is selected and append to the title
                    if ( String( html.category_name ) ) {
                        new_meta_title += html.category_name;
                    }

                    // Check if location is selected and append with proper formatting
                    if ( String( html.location_name ) ) {
                        if ( String( html.category_name ) ) {
                            new_meta_title += ' within ' + html.location_name; // If category exists, add with a comma
                        } else {
                            new_meta_title += html.location_name; // If no category, just add location
                        }
                    }

                    // Check if address is selected and append with proper formatting
                    if (fields.address) {
                        if (fields.in_cat || fields.in_loc) {
                            new_meta_title += ' near ' + fields.address; // If category or location exists, add "near"
                        } else {
                            new_meta_title += fields.address; // Default to just the address
                        }
                    }

                    // Append website name to the meta title with a pipe separator
                    if (new_meta_title) {
                        new_meta_title += ' | ' + website_name; // Append the website name only if the title has content
                    } else {
                        new_meta_title = website_name; // Default to only the website name if no other title parts are present
                    }

                    // Update the meta title dynamically
                    document.title = new_meta_title;
                }
            }
        });

    }

    // Range Slider searching observer
    function initObserver() {
        let targetNodes = document.querySelectorAll('.directorist-instant-search .directorist-custom-range-slider__value input');

        targetNodes.forEach((targetNode) => {

            let searchElm = $(targetNode.closest('form'));

            if (targetNode) {
                let timeout;
                const observerCallback = (mutationList, observer) => {
                    for (const mutation of mutationList) {
                        if (mutation.attributeName == 'value') {
                            clearTimeout(timeout);
                            timeout = setTimeout(() => {
                                filterListing(searchElm);
                            }, 250);
                        }
                    }
                };

                const observer = new MutationObserver(observerCallback);
                observer.observe(targetNode, { attributes: true, childList: true, subtree: true });
            }
        })
    }

    // sidebar on keyup searching
    $('body').on("keyup", ".directorist-instant-search .listing-with-sidebar form", debounce( function(e) {
        e.preventDefault();
        var searchElm = $(this).closest('.listing-with-sidebar');
        filterListing(searchElm);

    }, 250));

    // sidebar on change searching
    $('body').on("change", ".directorist-instant-search .listing-with-sidebar input[type='checkbox'],.directorist-instant-search .listing-with-sidebar input[type='radio'], .directorist-custom-range-slider__wrap .directorist-custom-range-slider__range", debounce( function(e) {
        e.preventDefault();
        var searchElm = $(this).closest('.listing-with-sidebar');
        filterListing(searchElm);

    }, 250));

    // select on change with value - searching
    $('body').on("change", ".directorist-instant-search .listing-with-sidebar select", debounce( function(e) {
        e.preventDefault();
        var searchElm = $(this).val() && $(this).closest('.listing-with-sidebar');
        filterListing(searchElm);

    }, 250));

    // select on change with value - searching
    $('body').on("click", ".directorist-instant-search .listing-with-sidebar .directorist-filter-location-icon", debounce( function(e) {
        e.preventDefault();
        var searchElm = $(this).closest('.listing-with-sidebar');
        filterListing(searchElm);

    }, 1000));

    // Clear Input Value
    $('body').on("click", ".directorist-instant-search .directorist-search-field__btn--clear", function(e) {
        let inputValue = $(this).closest('.directorist-search-field').find('input:not([type="checkbox"]):not([type="radio"]), select').val('');

        if (inputValue) {
            let searchElm = $(document.querySelector('.directorist-instant-search .listing-with-sidebar form'));
            if (searchElm) {
                filterListing(searchElm);
            }
        }
    })

    if( $('.directorist-instant-search').length === 0 ) {
        $('body').on("submit", ".listing-with-sidebar .directorist-basic-search, .listing-with-sidebar .directorist-advanced-search", function (e) {
            e.preventDefault();
            let basic_data    = $('.listing-with-sidebar .directorist-basic-search').serialize();
            let advanced_data = $('.listing-with-sidebar .directorist-advanced-search').serialize();
            let action_value  = $('.directorist-advanced-search').attr('action');
            let url           = action_value + '?' + basic_data + '&' + advanced_data;

            window.location.href = url;
        });

    }

    window.addEventListener('load', function() {
        debounce(initObserver(), 250);
    });
})(jQuery);
