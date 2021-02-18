;(function ($) {

    // Dashboard become an author
    $('.directorist-become-author').on('click', function(e){
        e.preventDefault();
        $(".directorist-become-author-modal").addClass("directorist-become-author-modal__show");
    });
    $('.directorist-become-author-modal__cancel').on('click', function(e){
        e.preventDefault();
        $(".directorist-become-author-modal").removeClass("directorist-become-author-modal__show");
    });
    $('.directorist-become-author-modal__approve').on('click', function(e){
        e.preventDefault();
        var userId = $(this).attr('data-userId');
        var nonce = $(this).attr('data-nonce');
        var data = {
            userId : userId,
            nonce  : nonce,
            action : "atbdp_become_author"
        };

        // Send the data
        $.post(atbdp_public_data.ajaxurl, data, function (response) {
            $('.directorist-become-author__loader').addClass('active');
            $('#directorist-become-author-success').html(response);
            $('.directorist-become-author').hide();
            $(".directorist-become-author-modal").removeClass("directorist-become-author-modal__show");
        });
    });

})(jQuery);