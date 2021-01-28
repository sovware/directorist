;(function ($) {
    // Modal
    $( '.atbdp-toggle-modal' ).on( 'click', function( e ) {
        e.preventDefault();

        var data_target = $( this ).data( 'target' );

        $( data_target ).toggleClass( 'show' );
    });

    // Recovery Password Modal
    $("#recover-pass-modal").hide();

    $(".atbdp_recovery_pass").on("click", function (e) {
        e.preventDefault();
        $("#recover-pass-modal").slideToggle().show();
    });

    // Report abuse [on modal closed]
    $('#atbdp-report-abuse-modal').on('hidden.bs.modal', function (e) {

        $('#atbdp-report-abuse-message').val('');
        $('#atbdp-report-abuse-message-display').html('');

    });

    // Contact form [on modal closed]
    $('#atbdp-contact-modal').on('hidden.bs.modal', function (e) {

        $('#atbdp-contact-message').val('');
        $('#atbdp-contact-message-display').html('');

    });
    
})(jQuery);