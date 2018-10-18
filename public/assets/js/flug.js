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
            var acadp_report_abuse_submitted = false;

            $( '#acadp-report-abuse-form' ).validator({
                disable : false
            }).on( 'submit', function( e ) {

                if( acadp_report_abuse_submitted ) return false;
                acadp_report_abuse_submitted = true;

                // Check for errors
                if( ! e.isDefaultPrevented() ) {

                    e.preventDefault();


                    // Post via AJAX
                    var data = {
                        'action'  : 'acadp_public_report_abuse',
                        'post_id' : $( '#acadp-post-id' ).val(),
                        'message' : $( '#acadp-report-abuse-message' ).val()
                    };

                    $.post( public_report.ajax_url, data, function( response ) {
                        if( 1 == response.error ) {
                            $( '#acadp-report-abuse-message-display' ).addClass('text-danger').html( response.message );
                        } else {
                            $( '#acadp-report-abuse-message' ).val('');
                            $( '#acadp-report-abuse-message-display' ).addClass('text-success').html( response.message );
                        };


                        acadp_report_abuse_submitted = false; // Re-enable the submit event
                    }, 'json' );

                };

            });


        };

        // Report abuse [on modal closed]
        $('#acadp-report-abuse-modal').on( 'hidden.bs.modal', function( e ) {

            $( '#acadp-report-abuse-message' ).val('');
            $( '#acadp-report-abuse-message-display' ).html('');

        });





        // Alert users to login (only if applicable)
        $( '.acadp-require-login' ).on( 'click', function( e ) {

            e.preventDefault();
            alert( public_report.login_alert_message );

        });






    });

})( jQuery );




