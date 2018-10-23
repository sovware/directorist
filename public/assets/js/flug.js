(function( $ ) {
     /**
     * Called when the page has loaded.
     *
     * @since    1.0.0
     */
    $(function() {

        // Validate ACADP forms
        if( $.fn.validator ) {

            // Validate report abuse form
            var atbdp_report_abuse_submitted = false;

            $( '#atbdp-report-abuse-form' ).validator({
                disable : false
            }).on( 'submit', function( e ) {

                if( atbdp_report_abuse_submitted ) return false;
                atbdp_report_abuse_submitted = true;
                // Check for errors
                if( ! e.isDefaultPrevented() ) {
                    e.preventDefault();

                    // Post via AJAX
                    var data = {
                        'action'  : 'atbdp_public_report_abuse',
                        'post_id' : $( '#atbdp-post-id' ).val(),
                        'message' : $( '#atbdp-report-abuse-message' ).val()
                    };

                    $.post( public_report.ajaxurl, data, function( response ) {
                        if( 1 == response.error ) {
                            $( '#atbdp-report-abuse-message-display' ).addClass('text-danger').html( response.message );
                        } else {
                            $( '#atbdp-report-abuse-message' ).val('');
                            $( '#atbdp-report-abuse-message-display' ).addClass('text-success').html( response.message );
                        };


                        atbdp_report_abuse_submitted = false; // Re-enable the submit event
                    }, 'json' );

                };

            });

            // Validate contact form
            var atbdp_contact_submitted = false;

            $( '#atbdp-contact-form' ).validator({
                disable : false
            }).on( 'submit', function( e ) {

                if( atbdp_contact_submitted ) return false;

                // Check for errors
                if( ! e.isDefaultPrevented() ) {

                    e.preventDefault();

                    atbdp_contact_submitted = true;


                    $( '#atbdp-contact-message-display' ).append('<div class="atbdp-spinner"></div>');

                    // Post via AJAX
                    var data = {
                        'action'  : 'atbdp_public_send_contact_email',
                        'post_id' : $( '#atbdp-post-id' ).val(),
                        'name'    : $( '#atbdp-contact-name' ).val(),
                        'email'   : $( '#atbdp-contact-email' ).val(),
                        'message' : $( '#atbdp-contact-message' ).val(),
                    };

                    $.post( public_report.ajaxurl, data, function( response ) {
                        if( 1 == response.error ) {
                            $( '#atbdp-contact-message-display' ).addClass('text-danger').html( response.message );
                        } else {
                            $( '#atbdp-contact-message' ).val('');
                            $( '#atbdp-contact-message-display' ).addClass('text-success').html( response.message );
                        };

                    }, 'json' );

                } else {
                    atbdp_contact_submitted = false;
                };

            });


        };

        // Report abuse [on modal closed]
        $('#atbdp-report-abuse-modal').on( 'hidden.bs.modal', function( e ) {

            $( '#atbdp-report-abuse-message' ).val('');
            $( '#atbdp-report-abuse-message-display' ).html('');

        });

        // Contact form [on modal closed]
        $('#atbdp-contact-modal').on( 'hidden.bs.modal', function( e ) {

            $( '#atbdp-contact-message' ).val('');
            $( '#atbdp-contact-message-display' ).html('');

        });



        // Alert users to login (only if applicable)
        $( '.atbdp-require-login' ).on( 'click', function( e ) {

            e.preventDefault();
            alert( public_report.login_alert_message );

        });

    });

})( jQuery );




