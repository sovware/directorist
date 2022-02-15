;(function ($) {
        var tag = '';
        var price = [];
        var custom_field = {};

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

    /* Directorist ajax search */
    $('body').on("submit", ".directorist-advanced-filter__form", function( e ) {
        e.preventDefault();
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
            custom_field : custom_field
        };
        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            success: function( html ) {
                if( html.search_result ) {
                    $('.directorist-archive-contents').children('div:last-child').empty().append( html.search_result );
                    window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
                }
            }
        });
    });

    // Directorist type changes
    $('body').on("click", ".directorist-type-nav__link", function( e ) {
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
            success: function( html ) {
                if( html.directory_type ) {
                    $('.directorist-archive-contents').empty().append( html.directory_type );
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
    $('body').on("click", ".directorist-dropdown__links--single", function( e ) {
        e.preventDefault();
        let view_href = $(this).attr('href');
        let view = view_href.match( /view=.+/ );
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
            custom_field : custom_field
        };
        $.ajax({
            url: atbdp_public_data.ajaxurl,
            type: "POST",
            data: form_data,
            success: function( html ) {

                if( html.view_as ) {
                    $('.directorist-archive-contents').empty().append( html.view_as );
                    //$('.directorist-archive-contents').children('div:last-child').empty().append( html.view_as );
                    
                }
                window.dispatchEvent(new CustomEvent( 'directorist-reload-listings-map-archive'));
            }
        });
    })
    

})(jQuery);