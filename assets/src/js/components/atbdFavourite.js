;(function ($) {
    
    // Add or Remove from favourites
    $('#atbdp-favourites').on('click', function (e) {

        var data = {
            'action': 'atbdp_public_add_remove_favorites',
            'post_id': $("a.atbdp-favourites").data('post_id')
        };
        $.post(atbdp_public_data.ajaxurl, data, function (response) {
            $('#atbdp-favourites').html(response);
        });
    });

})(jQuery);