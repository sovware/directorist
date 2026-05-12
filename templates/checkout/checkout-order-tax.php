<?php
    defined( 'ABSPATH' ) || exit;

    use Directorist\Enums\Order\TaxType as OrderTaxType;

    /**
     * @var ?string $tax_type
     * @var ?float $tax_rate
     * @var float $amount
     */
    $tax_amount = directorist_compute_fixed_or_percent_amount( $tax_type, $tax_rate, $amount );
?>

<?php if ( $tax_amount > 0 ) : ?>
<tr class="atbdp_ch_subtotal directorist-checkout-tax directorist-row--order-tax" data-order-tax-type="<?php echo esc_attr( $tax_type ); ?>" data-order-tax-rate="<?php echo esc_attr( $tax_rate ); ?>" data-order-tax-amount="<?php echo esc_attr( $tax_amount ); ?>">
    <td colspan="2" class="">
        <span class="directorist-summery-label directorist-row-label--order-tax-rate">
            <?php esc_html_e( 'Tax', 'directorist-pricing-plans' ); ?>
            <?php
                if ( OrderTaxType::PERCENT === $tax_type ) {
                    echo sprintf(
                        /* translators: %s: tax rate percentage */
                        esc_html__( '( %s%% )', 'directorist-pricing-plans' ),
                        esc_html( $tax_rate )
                    );
                } else {
                    echo sprintf(
                        /* translators: %s: formatted tax amount */
                        esc_html__( '( %s )', 'directorist-pricing-plans' ),
                        directorist_price( $tax_rate )
                    );
                }
            ?>
        </span>
    </td>
    <td class="directorist-text-right">
        <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount directorist-row-value--order-tax-amount">
            <?php echo wp_kses_post( directorist_price( $tax_amount ) );?>
        </div>
    </td>
</tr>
<?php endif; ?>
