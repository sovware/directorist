<?php

defined( "ABSPATH" ) || exit;

use Directorist\Enums\Order\TaxType as OrderTaxType;

/**
 * @var stdClass $order
 */
?>
<input type="hidden" name="order_id" value="<?php echo esc_attr( $order->id ); ?>">

<tr class="atbdp_ch_subtotal directorist-checkout-subtotal" data-subtotal="<?php echo esc_attr( $order->sub_total ); ?>">
    <td colspan="2" class="">
        <span class="directorist-summery-label"><?php esc_html_e( 'Subtotal', 'directorist-pricing-plans' ); ?></span>
    </td>
    <td class="directorist-text-right">
        <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount">
            <?php echo wp_kses_post( directorist_price( $order->sub_total ) ); ?>
        </div>
    </td>
</tr>

<?php if ( floatval( $order->tax_rate ) > 0 ) : ?>
<tr class="atbdp_ch_subtotal directorist-checkout-tax" data-tax="<?php echo esc_attr( $order->tax_rate ); ?>">
    <td colspan="2" class="">
        <span class="directorist-summery-label">
            <?php esc_html_e( 'Tax', 'directorist-pricing-plans' ); ?>
            <?php
                if ( OrderTaxType::PERCENT === $order->tax_type ) {
                    echo sprintf(
                        /* translators: %s: tax rate percentage */
                        esc_html__( '( %s%% )', 'directorist-pricing-plans' ),
                        esc_html( $order->tax_rate )
                    );
                } else {
                    echo sprintf(
                        /* translators: %s: formatted tax amount */
                        esc_html__( '( %s )', 'directorist-pricing-plans' ),
                        directorist_price( $order->tax_rate )
                    );
                }
            ?>
        </span>
    </td>
    <td class="directorist-text-right">
        <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount">
            <?php echo wp_kses_post( directorist_price( $order->tax_rate ) );?>
        </div>
    </td>
</tr>
<?php endif; ?>

