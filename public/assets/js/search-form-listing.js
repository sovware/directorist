(function ($) {
    //Advance search
    // Populate atbdp child terms dropdown
    $('.bdas-terms').on('change', 'select', function (e) {

        e.preventDefault();

        var $this = $(this);
        var taxonomy = $this.data('taxonomy');
        var parent = $this.data('parent');
        var value = $this.val();
        var classes = $this.attr('class');

        $this.closest('.bdas-terms').find('input.bdas-term-hidden').val(value);
        $this.parent().find('div:first').remove();

        if (parent != value) {
            $this.parent().append('<div class="bdas-spinner"></div>');

            var data = {
                'action': 'bdas_public_dropdown_terms',
                'taxonomy': taxonomy,
                'parent': value,
                'class': classes,
                'security': atbdp_search.ajaxnonce
            };

            $.post(atbdp_search.ajax_url, data, function (response) {
                $this.parent().find('div:first').remove();
                $this.parent().append(response);
            });
        }
        ;

    });

    // load custom fields of the selected category in the search form
    $('body').on('change', '.bdas-category-search, #at_biz_dir-category', function () {
        var $search_elem = $(this).closest('form').find(".atbdp-custom-fields-search");

        if ($search_elem.length) {

            $search_elem.html('<div class="atbdp-spinner"></div>');

            var data = {
                'action': 'atbdp_custom_fields_search',
                'term_id': $(this).val(),
                'security': atbdp_search.ajaxnonce
            };

            $.post(atbdp_search.ajax_url, data, function (response) {
                $search_elem.html(response);
                var item = $('.custom-control').closest('.bads-custom-checks');
                item.each(function (index, el) {
                    var count = 0;
                    var abc = $(el)[0];
                    var abc2 = $(abc).children('.custom-control');
                    if (abc2.length <= 4) {
                        $(abc2).closest('.bads-custom-checks').next('a.more-or-less').hide();
                    }
                    $(abc2).slice(4, abc2.length).hide();

                });
            });

        }
        ;

    });


    $('.address_result').hide();
    if (atbdp_search_listing.i18n_text.select_listing_map === 'google') {
        function initialize() {
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                document.getElementById('cityLat').value = place.geometry.location.lat();
                document.getElementById('cityLng').value = place.geometry.location.lng();
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    } else if (atbdp_search_listing.i18n_text.select_listing_map === 'openstreet') {
        $('#address, #q_addressss,.atbdp-search-address').on('keyup', function (event) {
            event.preventDefault();
            var search = $(this).val();
            $(this).parent().next('.address_result').css({'display': 'block'});
            if (search === "") {
                $(this).parent().next('.address_result').css({'display': 'none'});
            }

            var res = "";
            $.ajax({
                url: `https://nominatim.openstreetmap.org/?q=%27+${search}+%27&format=json`,
                type: 'POST',
                data: {},
                success: function (data) {
                    //console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        res += '<li><a href="#" data-lat=' + data[i].lat + ' data-lon=' + data[i].lon + '>' + data[i].display_name + '</a></li>'
                    }
                    $(event.target).parent().next('.address_result').html('<ul>' + res + '</ul>');

                }
            });
        });
        //hide address result when click outside the input field
        $(document).on("click", function (e) {
            if(!($(e.target).closest("#address, #q_addressss,.atbdp-search-address").length)){
                $('.address_result').hide();
            }
        });

        $('body').on('click', '.address_result ul li a', function (event) {
            event.preventDefault();
            let text = $(this).text(),
                lat = $(this).data('lat'),
                lon = $(this).data('lon');

            $('#cityLat').val(lat);
            $('#cityLng').val(lon);

            $('#address, #q_addressss,.atbdp-search-address').val(text);
            $('.address_result').hide();
        });
    }
    if ($('#address, #q_addressss,.atbdp-search-address').val() === "") {
        $(this).parent().next('.address_result').css({'display': 'none'});
    }
})(jQuery);
