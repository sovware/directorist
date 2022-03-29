;(function ($) {

    function update_instant_search_url( form_data ) {
        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;

            if( form_data.paged && form_data.paged.length ) {
                var query = '?paged=' + form_data.paged + '';
            }
            if( form_data.q && form_data.q.length ) {
                var query = '?q=' + form_data.q;
            }
            if( form_data.in_cat && form_data.in_cat.length ) {
                var query =  ( query && query.length ) ? query + '&in_cat=' + form_data.in_cat : '?in_cat=' + form_data.in_cat;
            }
            if( form_data.in_loc && form_data.in_loc.length ) {
                var query =  ( query && query.length ) ? query + '&in_loc=' + form_data.in_loc : '?in_loc=' + form_data.in_loc;
            }
            if( form_data.in_tag && form_data.in_tag.length ) {
                var query =  ( query && query.length ) ? query + '&in_tag=' + form_data.in_tag : '?in_tag=' + form_data.in_tag;
            }
            if( form_data.price&& form_data.price[0] && form_data.price[0] > 0 ) {
                var query =  ( query && query.length ) ? query + '&price%5B0%5D=' + form_data.price[0] : '?price%5B0%5D=' + form_data.price[0];
            }
            if( form_data.price && form_data.price[1] && form_data.price[1] > 0 ) {
                var query =  ( query && query.length ) ? query + '&price%5B1%5D=' + form_data.price[1] : '?price%5B1%5D=' + form_data.price[1];
            }
            if( form_data.price_range && form_data.price_range.length ) {
                var query =  ( query && query.length ) ? query + '&price_range=' + form_data.price_range : '?price_range=' + form_data.price_range;
            }
            if( form_data.search_by_rating && form_data.search_by_rating.length ) {
                var query =  ( query && query.length ) ? query + '&search_by_rating=' + form_data.search_by_rating : '?search_by_rating=' + form_data.search_by_rating;
            }
            if( form_data.cityLat && form_data.cityLat.length && form_data.address && form_data.address.length ) {
                var query =  ( query && query.length ) ? query + '&cityLat=' + form_data.cityLat : '?cityLat=' + form_data.cityLat;
            }
            if( form_data.cityLng && form_data.cityLng.length && form_data.address && form_data.address.length ) {
                var query =  ( query && query.length ) ? query + '&cityLng=' + form_data.cityLng : '?cityLng=' + form_data.cityLng;
            }
            if( form_data.miles && form_data.miles > 0 ) {
                var query =  ( query && query.length ) ? query + '&miles=' + form_data.miles : '?miles=' + form_data.miles;
            }
            if( form_data.address && form_data.address.length ) {
                var query =  ( query && query.length ) ? query + '&address=' + form_data.address : '?address=' + form_data.address;
            }
            if( form_data.zip && form_data.zip.length ) {
                var query =  ( query && query.length ) ? query + '&zip=' + form_data.zip : '?zip=' + form_data.zip;
            }
            if( form_data.fax && form_data.fax.length ) {
                var query =  ( query && query.length ) ? query + '&fax=' + form_data.fax : '?fax=' + form_data.fax;
            }
            if( form_data.email && form_data.email.length ) {
                var query =  ( query && query.length ) ? query + '&email=' + form_data.email : '?email=' + form_data.email;
            }
            if( form_data.website && form_data.website.length ) {
                var query =  ( query && query.length ) ? query + '&website=' + form_data.website : '?website=' + form_data.website;
            }
            if( form_data.phone && form_data.phone.length ) {
                var query =  ( query && query.length ) ? query + '&phone=' + form_data.phone : '?phone=' + form_data.phone;
            }
            if( form_data.custom_field && form_data.custom_field.length ) {
                var query =  ( query && query.length ) ? query + '&custom_field=' + form_data.custom_field : '?custom_field=' + form_data.custom_field;
            }

            var newurl = query ? newurl + query : newurl;

            window.history.pushState({path:newurl},'',newurl);
        }
    }

    function getURLParameter(url, name) {
        return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
    }

    /* Directorist instant search */
    $('body').on("submit", ".directorist-instant-search .directorist-advanced-filter__form", function( e ) {
        e.preventDefault();

        let tag = [];
        let price = [];
        let custom_field = {};

        $(this).find('input[name^="in_tag["]:checked').each(function(index, el) {
            tag.push($(el).val())
        });

        $(this).find('input[name^="price["]').each(function(index, el) {
            price.push($(el).val())
        });

        $(this).find('[name^="custom_field"]').each(function(index, el) {
            var test    = $(el).attr('name');
            var type    = $(el).attr('type');
            var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');
            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function() {
                    value = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function() {
                    var checkValue = [];
                    value = $(this).val();
                    checkValue.push(value);
                    custom_field[post_id] = checkValue;
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let view_href   = $(".directorist-viewas-dropdown .directorist-dropdown__links--single.active").attr('href');
        let view_as     = ( view_href && view_href.length ) ? view_href.match( /view=.+/ ) : '';
        let view        = ( view_as && view_as.length ) ? view_as[0].replace( /view=/, '' ) : '';
        let type_href   = $('.directorist-type-nav__list .current a').attr('href');
        let type        = ( type_href && type_href.length ) ? type_href.match( /directory_type=.+/ ) : '';
        let directory_type = getURLParameter( type_href, 'directory_type' );

        var data = {
            action  : 'directorist_instant_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            in_tag  : tag,
            price   : price,
            custom_field : custom_field,
        };

        var fields = {
            q       : $(this).find('input[name="q"]').val(),
            in_cat  : $(this).find('.bdas-category-search, .directorist-category-select').val(),
            in_loc  : $(this).find('.bdas-category-location, .directorist-location-select').val(),
            price_range : $(this).find("input[name='price_range']:checked").val(),
            search_by_rating: $(this).find('select[name=search_by_rating]').val(),
            address : $(this).find('input[name="address"]').val(),
            zip     : $(this).find('input[name="zip"]').val(),
            fax     : $(this).find('input[name="fax"]').val(),
            email   : $(this).find('input[name="email"]').val(),
            website  : $(this).find('input[name="website"]').val(),
            phone   : $(this).find('input[name="phone"]').val(),
        };

        if( fields.address && fields.address.length ) {
            fields.cityLat = $(this).find('#cityLat').val();
            fields.cityLng = $(this).find('#cityLng').val();
            fields.miles   = $(this).find('.atbdrs-value').val();
        }

        var form_data = {
            ...data,
            ...fields
        };

        const allFieldsAreEmpty    = Object.values( fields ).every( item => ! item );
        const tagFieldEmpty        = data.in_tag.every( item => ! item );
        const priceFieldEmpty      = data.price.every( item => ! item );
        const customFieldsAreEmpty = Object.values( data.custom_field ).every( item => ! item );

        if ( ! allFieldsAreEmpty || ! tagFieldEmpty || ! priceFieldEmpty || ! customFieldsAreEmpty ) {

            if( view && view.length ) {
                form_data.view = view
            }

            if( directory_type && directory_type.length ) {
                form_data.directory_type = directory_type;
            }

            update_instant_search_url( form_data );

            $.ajax({
                url: atbdp_public_data.ajaxurl,
                type: "POST",
                data: form_data,
                beforeSend: function () {
                    $('.directorist-instant-search .directorist-advanced-filter__form .directorist-btn-sm'). attr("disabled", true);
                    $('.directorist-archive-contents').children('div:last-child').addClass('atbdp-form-fade');
                    const advance_filter = $('.directorist-instant-search .directorist-advanced-filter')[0];
                    $(advance_filter).removeClass('directorist-advanced-filter--show')
                    $(advance_filter).hide();
                    $(document).scrollTop( $(".directorist-instant-search").offset().top );
                },
                success: function( html ) {
                    if( html.search_result ) {
                        $('.directorist-header-found-title span').text( html.count );
                        $('.directorist-archive-contents').children('div:last-child').replaceWith( html.search_result );
                        $('.directorist-archive-contents').children('div:last-child').removeClass('atbdp-form-fade');
                        $('.directorist-instant-search .directorist-advanced-filter__form .directorist-btn-sm'). attr("disabled", false)
                        window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
                    }
                }
            });

        }
    });

    // Directorist type changes
    $('body').on("click", ".directorist-instant-search .directorist-type-nav__link", function( e ) {
        e.preventDefault();
        let type_href   = $(this).attr('href');
        let type        = type_href.match( /directory_type=.+/ );
        //let directory_type = ( type && type.length ) ? type[0].replace( /directory_type=/, '' ) : '';
        let directory_type = getURLParameter( type_href, 'directory_type' );
        var form_data = {
            action  : 'directorist_instant_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            directory_type    : directory_type,
        };

        update_instant_search_url( form_data );

        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                $('.directorist-archive-contents').addClass('atbdp-form-fade');
            },
            success: function( html ) {
                if( html.directory_type ) {
                    $('.directorist-archive-contents').replaceWith( html.directory_type );
                    $('.directorist-archive-contents').removeClass('atbdp-form-fade');
                    window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
                }
                let events = [
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

    $('body').on("click", ".disabled-link", function( e ) {
        e.preventDefault();

    })

    // Directorist view as changes
    $('body').on("click", ".directorist-instant-search .directorist-viewas-dropdown .directorist-dropdown__links--single", function( e ) {
        e.preventDefault();
        let tag          = [];
        let price        = [];
        let custom_field = {};

        $('input[name^="in_tag["]:checked').each(function(index, el) {
            tag.push($(el).val())
        });

        $('input[name^="price["]').each(function(index, el) {
            price.push($(el).val())
        });

        $('[name^="custom_field"]').each(function(index, el) {
            var test    = $(el).attr('name');
            var type    = $(el).attr('type');
            var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');

            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function() {
                    value = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function() {
                    var checkValue = [];
                    value = $(this).val();
                    checkValue.push(value);
                    custom_field[post_id] = checkValue;
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let sort_href   = $(".directorist-sortby-dropdown .directorist-dropdown__links--single.active").attr('data-link');
        let sort_by     = ( sort_href && sort_href.length ) ? sort_href.match( /sort=.+/ ) : '';
        let sort        = ( sort_by && sort_by.length ) ? sort_by[0].replace( /sort=/, '' ) : '';
        let view_href   = $(this).attr('href');
        let view        = view_href.match( /view=.+/ );
        let type_href   = $('.directorist-type-nav__list .current a').attr('href');
        let type        = ( type_href && type_href.length ) ? type_href.match( /directory_type=.+/ ) : '';
        let directory_type = getURLParameter( type_href, 'directory_type' );
        let page_no     = $(".page-numbers.current").text();

        $(".directorist-viewas-dropdown .directorist-dropdown__links--single").removeClass('active');
        $(this).addClass("active");
        var form_data = {
            action  : 'directorist_instant_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            view    : ( view && view.length ) ? view[0].replace( /view=/, '' ) : '',
            q       : $('input[name="q"]').val(),
            in_cat  : $('.bdas-category-search, .directorist-category-select').val(),
            in_loc  : $('.bdas-category-location, .directorist-location-select').val(),
            in_tag  : tag,
            price   : price,
            price_range : $("input[name='price_range']:checked").val(),
            search_by_rating: $('select[name=search_by_rating]').val(),
            cityLat : $('#cityLat').val(),
            cityLng : $('#cityLng').val(),
            miles   : $('.atbdrs-value').val(),
            address : $('input[name="address"]').val(),
            zip     : $('input[name="zip"]').val(),
            fax     : $('input[name="fax"]').val(),
            email   : $('input[name="email"]').val(),
            website   : $('input[name="website"]').val(),
            phone   : $('input[name="phone"]').val(),
            custom_field : custom_field,
        };

        if( page_no && page_no.length ) {
            form_data.paged = page_no;
        }

        if( directory_type && directory_type.length ) {
            form_data.directory_type = directory_type;
        }

        if( sort && sort.length ) {
            form_data.sort = sort
        }

        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                $('.directorist-instant-search .directorist-viewas-dropdown .directorist-dropdown__links--single').addClass("disabled-link");
                $('.directorist-instant-search .directorist-dropdown__links-js a').removeClass('directorist-dropdown__links--single');
                $('.directorist-archive-contents').children('div:last-child').addClass('atbdp-form-fade');
                $('.directorist-dropdown__links').hide();
                const advance_filter = $('.directorist-instant-search .directorist-advanced-filter')[0];
                $(advance_filter).removeClass('directorist-advanced-filter--show')
                $(advance_filter).hide();
                $(document).scrollTop( $(".directorist-instant-search").offset().top );
            },
            success: function( html ) {
                if( html.view_as ) {
                    $('.directorist-header-found-title span').text( html.count );
                    $('.directorist-archive-contents').children('div:last-child').replaceWith( html.view_as );
                    $('.directorist-archive-contents').children('div:last-child').removeClass('atbdp-form-fade');
                    $('.directorist-instant-search .directorist-viewas-dropdown .directorist-dropdown__links--single').removeClass("disabled-link");
                    $('.directorist-instant-search .directorist-dropdown__links-js a').addClass('directorist-dropdown__links--single');
                }
                window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
            }
        });
    });

    $('.directorist-instant-search .directorist-dropdown__links--single-js').off( 'click' );

    // Directorist sort by changes
    $('body').on("click", ".directorist-instant-search .directorist-sortby-dropdown .directorist-dropdown__links--single-js", function( e ) {
        e.preventDefault();
        let tag          = [];
        let price        = [];
        let custom_field = {};

        $('input[name^="in_tag["]:checked').each(function(index, el) {
            tag.push($(el).val())
        });

        $('input[name^="price["]').each(function(index, el) {
            price.push($(el).val())
        });

        $('[name^="custom_field"]').each(function(index, el) {
            var test    = $(el).attr('name');
            var type    = $(el).attr('type');
            var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');
            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function() {
                    value = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function() {
                    var checkValue = [];
                    value = $(this).val();
                    checkValue.push(value);
                    custom_field[post_id] = checkValue;
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let view_href   = $(".directorist-viewas-dropdown .directorist-dropdown__links--single.active").attr('href');
        let view_as     = ( view_href && view_href.length ) ? view_href.match( /view=.+/ ) : '';
        let view        = ( view_as && view_as.length ) ? view_as[0].replace( /view=/, '' ) : '';
        let sort_href   = $(this).attr('data-link');
        let sort_by     = sort_href.match( /sort=.+/ );
        let type_href   = $('.directorist-type-nav__list .current a').attr('href');
        let type        = ( type_href && type_href.length ) ? type_href.match( /directory_type=.+/ ) : '';
        let directory_type = getURLParameter( type_href, 'directory_type' );

        $(".directorist-sortby-dropdown .directorist-dropdown__links--single").removeClass('active');
        $(this).addClass("active");

        var form_data = {
            action  : 'directorist_instant_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            sort    : ( sort_by && sort_by.length ) ? sort_by[0].replace( /sort=/, '' ) : '',
            q       : $('input[name="q"]').val(),
            in_cat  : $('.bdas-category-search, .directorist-category-select').val(),
            in_loc  : $('.bdas-category-location, .directorist-location-select').val(),
            in_tag  : tag,
            price   : price,
            price_range : $("input[name='price_range']:checked").val(),
            search_by_rating: $('select[name=search_by_rating]').val(),
            cityLat : $('#cityLat').val(),
            cityLng : $('#cityLng').val(),
            miles   : $('.atbdrs-value').val(),
            address : $('input[name="address"]').val(),
            zip     : $('input[name="zip"]').val(),
            fax     : $('input[name="fax"]').val(),
            email   : $('input[name="email"]').val(),
            website   : $('input[name="website"]').val(),
            phone   : $('input[name="phone"]').val(),
            custom_field : custom_field,
            view : view,
        };

        if( directory_type && directory_type.length ) {
            form_data.directory_type = directory_type;
        }

        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                $('.directorist-instant-search .directorist-sortby-dropdown .directorist-dropdown__links--single-js').addClass("disabled-link");
                $('.directorist-instant-search .directorist-dropdown__links-js a').removeClass('directorist-dropdown__links--single-js');
                $('.directorist-archive-contents').children('div:last-child').addClass('atbdp-form-fade');
                $('.directorist-dropdown__links').hide();
                const advance_filter = $('.directorist-instant-search .directorist-advanced-filter')[0];
                $(advance_filter).removeClass('directorist-advanced-filter--show')
                $(advance_filter).hide();
                $(document).scrollTop( $(".directorist-instant-search").offset().top );
            },
            success: function( html ) {
                if( html.view_as ) {
                    $('.directorist-header-found-title span').text( html.count );
                    $('.directorist-archive-contents').children('div:last-child').replaceWith( html.view_as );
                    $('.directorist-archive-contents').children('div:last-child').removeClass('atbdp-form-fade');
                    $('.directorist-instant-search .directorist-sortby-dropdown .directorist-dropdown__links--single-js').removeClass("disabled-link");
                    $('.directorist-instant-search .directorist-dropdown__links-js a').addClass('directorist-dropdown__links--single-js');
                }
                window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
            }
        });
    });

    // Directorist pagination
    $('body').on("click", ".directorist-instant-search .directorist-pagination .page-numbers", function( e ) {
        e.preventDefault();
        let tag          = [];
        let price        = [];
        let custom_field = {};

        $('input[name^="in_tag["]:checked').each(function(index, el) {
            tag.push($(el).val())
        });

        $('input[name^="price["]').each(function(index, el) {
            price.push($(el).val())
        });

        $('[name^="custom_field"]').each(function(index, el) {
            var test    = $(el).attr('name');
            var type    = $(el).attr('type');
            var post_id = test.replace(/(custom_field\[)/, '').replace(/\]/, '');
            if ('radio' === type) {
                $.each($("input[name='custom_field[" + post_id + "]']:checked"), function() {
                    value = $(this).val();
                    custom_field[post_id] = value;
                });
            } else if ('checkbox' === type) {
                post_id = post_id.split('[]')[0];
                $.each($("input[name='custom_field[" + post_id + "][]']:checked"), function() {
                    var checkValue = [];
                    value = $(this).val();
                    checkValue.push(value);
                    custom_field[post_id] = checkValue;
                });
            } else {
                var value = $(el).val();
                custom_field[post_id] = value;
            }
        });

        let sort_href   = $(".directorist-sortby-dropdown .directorist-dropdown__links--single.active").attr('data-link');
        let sort_by     = ( sort_href && sort_href.length ) ? sort_href.match( /sort=.+/ ) : '';
        let sort        = ( sort_by && sort_by.length ) ? sort_by[0].replace( /sort=/, '' ) : '';
        let view_href   = $(".directorist-viewas-dropdown .directorist-dropdown__links--single.active").attr('href');
        let view_as     = view_href.match( /view=.+/ );
        let view        = ( view_as && view_as.length ) ? view_as[0].replace( /view=/, '' ) : '';
        let type_href   = $('.directorist-type-nav__list .current a').attr('href');
        let type        = ( type_href && type_href.length ) ? type_href.match( /directory_type=.+/ ) : '';
        let directory_type = getURLParameter( type_href, 'directory_type' );

        $(".directorist-pagination .page-numbers").removeClass('current');
        $(this).addClass("current");
        var paginate_link   = $(this).attr('href');
        var page            = paginate_link.match( /page\/.+/ );
        var page_value      = ( page && page.length ) ? page[0].replace( /page\//, '' ) : '';
        var page_no         = ( page_value && page_value.length ) ? page_value.replace( /\//, '' ) : '';
        if( ! page_no ){
            var page       = paginate_link.match( /paged=.+/ );
            var page_no    = ( page && page.length ) ? page[0].replace( /paged=/, '' ) : '';
        }

        var form_data = {
            action  : 'directorist_instant_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            view    : ( view && view.length ) ? view[0].replace( /view=/, '' ) : '',
            q       : $('input[name="q"]').val(),
            in_cat  : $('.bdas-category-search, .directorist-category-select').val(),
            in_loc  : $('.bdas-category-location, .directorist-location-select').val(),
            in_tag  : tag,
            price   : price,
            price_range : $("input[name='price_range']:checked").val(),
            search_by_rating: $('select[name=search_by_rating]').val(),
            cityLat : $('#cityLat').val(),
            cityLng : $('#cityLng').val(),
            miles   : $('.atbdrs-value').val(),
            address : $('input[name="address"]').val(),
            zip     : $('input[name="zip"]').val(),
            fax     : $('input[name="fax"]').val(),
            email   : $('input[name="email"]').val(),
            website   : $('input[name="website"]').val(),
            phone   : $('input[name="phone"]').val(),
            custom_field : custom_field,
            view    : view,
            paged   : page_no,
        };

        update_instant_search_url( form_data );

        if( directory_type && directory_type.length ) {
            form_data.directory_type = directory_type;
        }

        if( sort && sort.length ) {
            form_data.sort = sort
        }

        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                $('.directorist-archive-contents').children('div:last-child').addClass('atbdp-form-fade');
            },
            success: function( html ) {
                if( html.view_as ) {
                    $('.directorist-header-found-title span').text( html.count );
                    $('.directorist-archive-contents').children('div:last-child').replaceWith( html.view_as );
                    $('.directorist-archive-contents').children('div:last-child').removeClass('atbdp-form-fade');
                    $(document).scrollTop( $(".directorist-instant-search").offset().top );
                }
                window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
            }
        });
    });

})(jQuery);