;(function ($) {
    // Recovery Password Modal
    $("#recover-pass-modal").hide();

    $(".atbdp_recovery_pass").on("click", function (e) {
        e.preventDefault();
        $("#recover-pass-modal").slideToggle().show();
    });

    // Contact form [on modal closed]
    $('#atbdp-contact-modal').on('hidden.bs.modal', function (e) {

        $('#atbdp-contact-message').val('');
        $('#atbdp-contact-message-display').html('');

    });

    // Template Restructured
    // Modal
    let directoristModal = document.querySelector('.directorist-modal-js');
    $( 'body' ).on( 'click', '.directorist-btn-modal-js', function( e ) {
        e.preventDefault();
        var data_target = $(this).attr("data-directorist_target");
        document.querySelector(`.${data_target}`).classList.add('directorist-show');
    });

    $('body').on('click', '.directorist-modal-close-js', function(e){
        e.preventDefault();
        $(this).closest('.directorist-modal-js').removeClass('directorist-show');
    });

    $(document).bind('click', function(e) {
        if(e.target == directoristModal){
            directoristModal.classList.remove('directorist-show');
        }
    });

})(jQuery);