(function ($) {
    /*CHECKOUT RELATED STUFFS*/
    // When any item changes in the checkout form page, we will update the tottal price.
    // cash the nonce
    var nonce = $('#checkout_nonce').val();
    $( '.atbdp_checkout_item_field' ).on( 'change', function() {
        var total_amount = 0,
            item   = 0;
        //calculate the amount from all the checkbox and the radio inputs that are checked.
        $( "#directorist-checkout-table input[type='checkbox']:checked, #directorist-checkout-table input[type='radio']:checked" ).each(function() {
            total_amount += parseFloat( $( this ).data('price') );
            ++item;
        });


        $( '#directorist_payment_gateways, #atbdp_checkout_submit_btn' ).show();

        if( 0 == item ) {
            $( '#atbdp_checkout_total_amount' ).html( '0.00' );
            $( '#directorist_payment_gateways, #atbdp_checkout_submit_btn' ).hide();
            return;
        };

        data = 'amount=' + total_amount;
        atbdp_do_ajax($( '#atbdp_checkout_total_amount' ), 'atbdp_format_total_amount', data, function(response) {

         $( '#atbdp_checkout_total_amount').html( response );

         var amount = parseFloat( $( '#atbdp_checkout_total_amount' ).html() );

         if( amount > 0 ) {
         $( '#directorist_payment_gateways' ).show();
         $( '#atbdp_checkout_submit_btn' ).val( atbdp_checkout.payNow ).show();
         } else {
         $( '#directorist_payment_gateways' ).hide();
         $( '#atbdp_checkout_submit_btn' ).val( atbdp_checkout.completeSubmission ).show();
         }

         });

    }).trigger('change');


    /*This function handles all ajax request*/
    function atbdp_do_ajax( ElementToShowLoadingIconAfter, ActionName, arg, CallBackHandler){
        var data;
        if(ActionName) data = "action=" + ActionName;
        if(arg)    data = arg + "&action=" + ActionName;
        if(arg && !ActionName) data = arg;

        var n = data.search(atbdp_checkout.nonceName);
        if(n<0){
            data = data + "&"+atbdp_checkout.nonceName+"=" + atbdp_checkout.nonce;
        }

        jQuery.ajax({
            type: "post",
            url: atbdp_checkout.ajaxurl,
            data: data,
            beforeSend: function() { jQuery("<span class='atbdp_ajax_loading'></span>").insertAfter(ElementToShowLoadingIconAfter); },
            success: function( data ) {
                jQuery(".atbdp_ajax_loading").remove();
                CallBackHandler(data);
            }
        });
    }
})(jQuery);