(function ( $ ) {
    // Update checkout pricing on product item change
    var checkout_price_item = $( '.atbdp-checkout-price-item' );
    checkout_price_item.on( 'change', function() {
        var checkout_net_price_area        = $( '#atbdp_checkout_total_amount' );
        var checkout_net_hidden_price_area = $( '#atbdp_checkout_total_amount_hidden' );
        var pricing_statement              = get_pricing_statement( checkout_price_item );

        checkout_net_price_area.html( get_currency_format( pricing_statement.total_price ) );
        checkout_net_hidden_price_area.val( pricing_statement.total_price );

        update_payment_methods( pricing_statement );
    });

    // get_pricing_statement
    function get_pricing_statement( price_item_elm ) {
        var total_price   = 0;
        var total_product = 0;

        price_item_elm.each( function( index ) {
            var price_item = price_item_elm[ index ];
            var price = price_item.value;
            price = ( isNaN( price_item.value ) ) ? 0 : Number( price );

            if ( $( price_item ).is(':checked') ) {
                total_price = total_price + price;
                total_product++;
            }
        });

        return {
            total_product: total_product,
            total_price: total_price,
        };
    }

    // update_payment_methods
    function update_payment_methods( pricing_statement ) {
        if ( ! pricing_statement.total_product ) {
            $( '#directorist_payment_gateways, #atbdp_checkout_submit_btn' ).hide();
            return;
        }

        if ( pricing_statement.total_price > 0 ) {
            $( '#directorist_payment_gateways' ).show();
            $( '#atbdp_checkout_submit_btn' ).val( atbdp_checkout.payNow ).show();
            $( '#atbdp_checkout_submit_btn_label' ).val( atbdp_checkout.payNow );
        } else {
            $( '#directorist_payment_gateways' ).hide();
            $( '#atbdp_checkout_submit_btn' ).val( atbdp_checkout.completeSubmission ).show();
            $( '#atbdp_checkout_submit_btn_label' ).val( atbdp_checkout.completeSubmission );
        }
    }


    // Helpers
    // --------------------
    // get_currency_format
    function get_currency_format( number ) {
        number = number.toFixed( 2 );
        number = number_with_commas( number );

        return number;
    }

    // number_with_commas
    function number_with_commas( number ) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

})(jQuery);