(function ($) {

window.addEventListener('DOMContentLoaded', () => {
    /* Archive sidebar toggle */
    const archiveSidebar = document.querySelector('.listing-with-sidebar__sidebar');
    const archiveSidebarToggle = document.querySelector('.directorist-archive-sidebar-toggle');
    const archiveSidebarClose = document.querySelector('.directorist-advanced-filter__close');
    const body = document.body;

    // Toggle sidebar and update toggle button's active state
    function toggleSidebar() {
        archiveSidebar.classList.toggle('listing-with-sidebar__sidebar--open');
        archiveSidebarToggle.classList.toggle('directorist-archive-sidebar-toggle--active');
    }

    // Close sidebar and reset toggle button's active state
    function closeSidebar() {
        if(archiveSidebar) {
            archiveSidebar.classList.remove('listing-with-sidebar__sidebar--open');
        }
        if(archiveSidebarToggle) {
            archiveSidebarToggle.classList.remove('directorist-archive-sidebar-toggle--active');
        }
    }

    // Event delegation for sidebar toggle and close buttons
    function handleSidebarToggleClick(e) {
        e.preventDefault();
        toggleSidebar();
    }

    function handleSidebarCloseClick(e) {
        e.preventDefault();
        closeSidebar();
    }

    // Event delegation for outside click to close sidebar
    function handleOutsideClick(e) {
        if (!e.target.closest('.listing-with-sidebar__sidebar') && e.target !== archiveSidebarToggle) {
            closeSidebar();
        }
    }

    // Attach event listeners
    if(archiveSidebarToggle) {
        archiveSidebarToggle.addEventListener('click', handleSidebarToggleClick);
    }
    if(archiveSidebarClose) {
        archiveSidebarClose.addEventListener('click', handleSidebarCloseClick);
    }
    body.addEventListener('click', handleOutsideClick);

    $('body').on("submit", ".listing-with-sidebar .directorist-basic-search, .listing-with-sidebar .directorist-advanced-search", function (e) {
        e.preventDefault();
        let basic_data    = $('.listing-with-sidebar .directorist-basic-search').serialize();
        let advanced_data = $('.listing-with-sidebar .directorist-advanced-search').serialize();
        let actionValue   = $('.directorist-advanced-search').attr('action');
        let url           = actionValue + '?' + basic_data + '&' + advanced_data;
    
        window.location.href = url;
    });

    function getURLParameter( url, name ) {
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
        var results = regex.exec( url );
        if ( ! results || ! results[2] ) {
            return '';
        }
        return decodeURIComponent( results[2] );
    }

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

    // sidebar on click searching
    $('body').on("change", ".directorist-instant-search .listing-with-sidebar", function (e) {
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

        let view_href      = $(".directorist-viewas-dropdown .directorist-dropdown__links--single.active").attr('href');
        let view_as        = (view_href && view_href.length) ? view_href.match(/view=.+/) : '';
        let view           = (view_as && view_as.length) ? view_as[0].replace(/view=/, '') : '';
        let type_href      = $('.directorist-type-nav__list .current a').attr('href');
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
            q               : $(this).find('input[name="q"]').val(),
            in_cat          : $(this).find('.bdas-category-search, .directorist-category-select').val(),
            in_loc          : $(this).find('.bdas-category-location, .directorist-location-select').val(),
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
            fields.miles = $(this).find('.directorist-range-slider-value').val();
        }

        if (fields.zip && fields.zip.length) {
            fields.zip_cityLat = $(this).find('.zip-cityLat').val();
            fields.zip_cityLng = $(this).find('.zip-cityLng').val();
            fields.miles = $(this).find('.directorist-range-slider-value').val();
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
                    $(_this).closest('.directorist-instant-search').find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", true);
                    $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').addClass('atbdp-form-fade');
                    $(_this).closest('.directorist-instant-search').find('.directorist-header-bar .directorist-advanced-filter').removeClass('directorist-advanced-filter--show')
                    $(_this).closest('.directorist-instant-search').find('.directorist-header-bar .directorist-advanced-filter').hide();
                    $(document).scrollTop($(_this).closest(".directorist-instant-search").offset().top);
                },
                success: function (html) {
                    if (html.search_result) {
                        $(_this).closest('.directorist-instant-search').find('.directorist-header-found-title span').text(html.count);
                        $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').replaceWith(html.search_result);
                        $(_this).closest('.directorist-instant-search').find('.directorist-archive-items').removeClass('atbdp-form-fade');
                        $(_this).closest('.directorist-instant-search').find('.directorist-advanced-filter__form .directorist-btn-sm').attr("disabled", false)
                        window.dispatchEvent(new CustomEvent('directorist-instant-search-reloaded'));
                        window.dispatchEvent(new CustomEvent('directorist-reload-listings-map-archive'));
                    }
                }
            });
        }
    });
    
});

})(jQuery);