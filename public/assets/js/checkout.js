(function ( $ ) {
    // Update checkout pricing on product item change
    var checkout_price_item = $( '.atbdp-checkout-price-item' );
    checkout_price_item.on( 'change', function() {
        var checkout_net_price_area        = $( '#atbdp_checkout_total_amount' );
        var checkout_net_hidden_price_area = $( '#atbdp_checkout_total_amount_hidden' );
        var pricing_state                  = get_checkout_pricing_state( checkout_price_item );
        
        checkout_net_price_area.html( get_currency_format( pricing_state.net_price ) );
        checkout_net_hidden_price_area.val( pricing_state.net_price );

        update_payment_methods( pricing_state );
    });

    // get_checkout_pricing_state
    function get_checkout_pricing_state( price_elm ) {
        var addition_price      = 0;
        var substruction_price  = 0;
        var checkout_price_item = price_elm;

        var total_pricing_element = checkout_price_item.length;
        var selected_pricing_element = 0;

        checkout_price_item.each( function( index ) {
            var price_item = checkout_price_item[ index ];
            var price_type = $( checkout_price_item[ index ] ).data( 'price-type' );
            price_type = ( ! price_type ) ? 'addition' : price_type;

            var price = price_item.value;
            price = ( isNaN( price_item.value ) ) ? 0 : Number( price );

            if ( $( price_item ).is(':checked') && 'addition' === price_type ) {
                addition_price = addition_price + price;
                selected_pricing_element++;
            }

            if ( $( price_item ).is(':checked') && 'substruction' === price_type ) {
                substruction_price = substruction_price + price;
            }
        });

        var net_price = addition_price - substruction_price;

        return {
            total_pricing_element: total_pricing_element,
            selected_pricing_element: selected_pricing_element,
            net_price: net_price,
        };
    }

    // update_payment_methods
    function update_payment_methods( pricing_state ) {
        let net_price = pricing_state.net_price;
        
        if ( ! pricing_state.selected_pricing_element ) {
            $( '#directorist_payment_gateways, #atbdp_checkout_submit_btn' ).hide();
            return;
        }

        if ( net_price > 0 ) {
            $( '#directorist_payment_gateways' ).show();
            $( '#atbdp_checkout_submit_btn' ).val( atbdp_checkout.payNow ).show();
            $( '#atbdp_checkout_submit_btn_label' ).val( atbdp_checkout.payNow );
        } else {
            $( '#directorist_payment_gateways' ).hide();
            $( '#atbdp_checkout_submit_btn' ).val( atbdp_checkout.completeSubmission ).show();
            $( '#atbdp_checkout_submit_btn_label' ).val( atbdp_checkout.completeSubmission );
        }

        console.log( { pricing_state } );
    }

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