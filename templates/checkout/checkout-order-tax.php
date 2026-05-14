<?php
    defined( 'ABSPATH' ) || exit;

    use Directorist\Enums\Order\TaxType as OrderTaxType;
?>

<?php if ( $amount > 0 ) : ?>
<tr class="atbdp_ch_subtotal directorist-checkout-tax directorist-row--order-tax" data-order-tax-type="<?php echo esc_attr( $type ); ?>" data-order-tax-rate="<?php echo esc_attr( $rate ); ?>" data-order-tax-amount="<?php echo esc_attr( $amount ); ?>">
    <td colspan="2" class="">
        <span class="directorist-summery-label directorist-row-label--order-tax-rate">
            <?php esc_html_e( 'Tax', 'directorist-pricing-plans' ); ?>
            <?php
                if ( OrderTaxType::PERCENT === $type ) {
                    echo sprintf(
                        /* translators: %s: tax rate percentage */
                        esc_html__( '( %s%% )', 'directorist-pricing-plans' ),
                        esc_html( $rate )
                    );
                } else {
                    echo sprintf(
                        /* translators: %s: formatted tax amount */
                        esc_html__( '( %s )', 'directorist-pricing-plans' ),
                        directorist_price( $rate )
                    );
                }
            ?>
        </span>
    </td>
    <td class="directorist-text-right">
        <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount directorist-row-value--order-tax-amount">
            <?php echo wp_kses_post( directorist_price( $amount ) );?>
        </div>
    </td>
</tr>
<?php endif; ?>
