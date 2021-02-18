;(function ($) {
alert('dsfds');
    // Dashboard become an author
$('.atbdp-become-author').on('click', function(e){
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
        console.log(response);
        $('#atbdp-become-author-success').html(response);
        $('.atbdp-become-author').hide();
    });
});

})(jQuery);