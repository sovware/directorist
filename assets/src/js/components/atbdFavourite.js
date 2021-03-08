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

    $('.directorist-favourite-remove-btn').each(function () {
      
        $(this).on('click', function (event) {
            event.preventDefault();

            var data = {
                'action': 'atbdp-favourites-all-listing',
                'post_id': $(this).data('listing_id')
            };

            $(".directorist-favorite-tooltip").hide();
            $.post(atbdp_public_data.ajaxurl, data, function (response) {
                
                var post_id = data['post_id'].toString();
                var staElement = $('#directorist_favourite_'+ post_id);

                 if('false' === response){
                    staElement.remove();
                }
            });

        })
    });

})(jQuery);