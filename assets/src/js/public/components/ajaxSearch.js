;(function ($) {
    let tag = '';
    let price = [];
    let custom_field = {};
    
    $.each($("input[name='in_tag[]']:checked"), function() {
        tag = $(this).val();
    });

    $('input[name^="price["]').each(function(index, el) {
        price.push($(el).val())
    });

    $('[name^="custom_field"]').each(function(index, el) {
        var test = $(el).attr('name');
        var type = $(el).attr('type');
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

    function directorist_ajax_search_seo( form_data ) {
        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?q=' + form_data.q;
            window.history.pushState({path:newurl},'',newurl);
        }
    }

    /* Directorist ajax search */
    $('body').on("submit", ".directorist-ajax-search .directorist-advanced-filter__form", function( e ) {
        e.preventDefault();
        
        let view_href = $(".directorist-viewas-dropdown .directorist-dropdown__links--single.active").attr('href');
        let view_as = view_href.match( /view=.+/ );
        let view    = ( view_as && view_as.length ) ? view_as[0].replace( /view=/, '' ) : '';

        var form_data = {
            action  : 'directorist_ajax_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            q       : $('input[name="q"]').val(),
            in_cat  : $('.bdas-category-search').val(),
            in_loc  : $('.bdas-category-location').val(),
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

        if( view && view.length ) {
            form_data.view = view
        }

        directorist_ajax_search_seo( form_data );
        
        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                $('.directorist-archive-contents').children('div:last-child').addClass('atbdp-form-fade');
            },
            success: function( html ) {
                
                if( html.search_result ) {
                    $('.directorist-header-found-title span').text( html.count );
                    $('.directorist-archive-contents').children('div:last-child').empty().append( html.search_result );
                    $('.directorist-archive-contents').children('div:last-child').removeClass('atbdp-form-fade');
                    window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
                }
            }
        });
    });

    // Directorist type changes
    $('body').on("click", ".directorist-ajax-search .directorist-type-nav__link", function( e ) {
        e.preventDefault();
        let type_href = $(this).attr('href');
        let type        = type_href.match( /directory_type=.+/ );
        var form_data = {
            action  : 'directorist_ajax_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            directory_type    : ( type && type.length ) ? type[0].replace( /directory_type=/, '' ) : '',
        };
        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                $('.directorist-archive-contents').addClass('atbdp-form-fade');
            },
            success: function( html ) {
                if( html.directory_type ) {
                    $('.directorist-archive-contents').empty().append( html.directory_type );
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
    
    // Directorist view as changes  
    $('body').on("click", ".directorist-ajax-search .directorist-viewas-dropdown .directorist-dropdown__links--single", function( e ) {
        e.preventDefault();
        let sort_href = $(".directorist-sortby-dropdown .directorist-dropdown__links--single.active").attr('data-link');
        let sort_by = ( sort_href && sort_href.length ) ? sort_href.match( /sort=.+/ ) : '';
        let sort    = ( sort_by && sort_by.length ) ? sort_by[0].replace( /sort=/, '' ) : '';
        let view_href = $(this).attr('href');
        let view = view_href.match( /view=.+/ );

        let page_no = $(".page-numbers.current").text();

        $(".directorist-viewas-dropdown .directorist-dropdown__links--single").removeClass('active');
        $(this).addClass("active");
        var form_data = {
            action  : 'directorist_ajax_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            view    : ( view && view.length ) ? view[0].replace( /view=/, '' ) : '',
            q       : $('input[name="q"]').val(),
            in_cat  : $('.bdas-category-search').val(),
            in_loc  : $('.bdas-category-location').val(),
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
                    $('.directorist-archive-contents').children('div:last-child').empty().append( html.view_as );
                    $('.directorist-archive-contents').children('div:last-child').removeClass('atbdp-form-fade');
                }
                window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
                window.dispatchEvent(new CustomEvent( 'directorist-on-changed-map-view'));
            }
        });
    });
    
    $('.directorist-ajax-search .directorist-dropdown__links--single-js').off( 'click' );

    // Directorist sort by changes  
    $('body').on("click", ".directorist-ajax-search .directorist-sortby-dropdown .directorist-dropdown__links--single-js", function( e ) {
        e.preventDefault();
        let view_href = $(".directorist-viewas-dropdown .directorist-dropdown__links--single.active").attr('href');
        let view_as = view_href.match( /view=.+/ );
        let view    = ( view_as && view_as.length ) ? view_as[0].replace( /view=/, '' ) : '';
        let sort_href = $(this).attr('data-link');
        let sort_by = sort_href.match( /sort=.+/ );

        $(this).addClass("active");
        
        var form_data = {
            action  : 'directorist_ajax_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            sort    : ( sort_by && sort_by.length ) ? sort_by[0].replace( /sort=/, '' ) : '',
            q       : $('input[name="q"]').val(),
            in_cat  : $('.bdas-category-search').val(),
            in_loc  : $('.bdas-category-location').val(),
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
            view : view
        };
        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            beforeSend: function () {
                $('.directorist-archive-contents').children('div:last-child').addClass('atbdp-form-fade');
            },
            success: function( html ) {

                if( html.view_as ) {
                    $('.directorist-archive-contents').children('div:last-child').empty().append( html.view_as );
                    $('.directorist-archive-contents').children('div:last-child').removeClass('atbdp-form-fade');
                }
                window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
            }
        });
    });

    // Directorist pagination
    $('body').on("click", ".directorist-pagination .page-numbers", function( e ) {
        e.preventDefault();
        
        let sort_href = $(".directorist-sortby-dropdown .directorist-dropdown__links--single.active").attr('data-link');
        let sort_by = ( sort_href && sort_href.length ) ? sort_href.match( /sort=.+/ ) : '';
        let sort    = ( sort_by && sort_by.length ) ? sort_by[0].replace( /sort=/, '' ) : '';
        let view_href = $(".directorist-viewas-dropdown .directorist-dropdown__links--single.active").attr('href');
        let view_as = view_href.match( /view=.+/ );
        let view    = ( view_as && view_as.length ) ? view_as[0].replace( /view=/, '' ) : '';
        
        $(".directorist-pagination .page-numbers").removeClass('current');
        $(this).addClass("current");
        var paginate_link   = $(this).attr('href');
        var page         = paginate_link.match( /page\/.+/ );
        var page_value            = ( page && page.length ) ? page[0].replace( /page\//, '' ) : '';
        var page_no            = ( page_value && page_value.length ) ? page_value.replace( /\//, '' ) : '';
        
        if( ! page_no ){
            var page         = paginate_link.match( /paged=.+/ );
            var page_no    = ( page && page.length ) ? page[0].replace( /paged=/, '' ) : '';
        }
        
        var form_data = {
            action  : 'directorist_ajax_search',
            _nonce  : atbdp_public_data.ajax_nonce,
            view    : ( view && view.length ) ? view[0].replace( /view=/, '' ) : '',
            q       : $('input[name="q"]').val(),
            in_cat  : $('.bdas-category-search').val(),
            in_loc  : $('.bdas-category-location').val(),
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
            paged   : page_no
        };

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
                    $('.directorist-archive-contents').children('div:last-child').empty().append( html.view_as );
                    $('.directorist-archive-contents').children('div:last-child').removeClass('atbdp-form-fade');
                }
                window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
            }
        });
    });
    

})(jQuery);