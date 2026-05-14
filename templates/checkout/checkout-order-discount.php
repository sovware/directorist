<?php
    defined( 'ABSPATH' ) || exit;

    use Directorist\Enums\Order\DiscountType;
?>

<?php if ( $amount > 0 ) : ?>
<tr class="atbdp_ch_subtotal directorist-checkout-discount directorist-row--order-discount" data-order-discount-type="<?php echo esc_attr( $type ); ?>" data-order-discount-rate="<?php echo esc_attr( $rate ); ?>" data-order-discount-amount="<?php echo esc_attr( $amount ); ?>">
    <td colspan="2" class="">
        <span class="directorist-summery-label directorist-row-label--order-discount-rate">
            <?php esc_html_e( 'Discount', 'directorist-pricing-plans' ); ?>
            <?php
                if ( DiscountType::PERCENT === $type ) {
                    echo sprintf(
                        /* translators: %s: discount rate percentage */
                        esc_html__( '( %s%% )', 'directorist-pricing-plans' ),
                        esc_html( $rate )
                    );
                } else {
                    echo sprintf(
                        /* translators: %s: formatted discount amount */
                        esc_html__( '( %s )', 'directorist-pricing-plans' ),
                        directorist_price( $rate )
                    );
                }
            ?>
        </span>
    </td>
    <td class="directorist-text-right">
        <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount directorist-row-value--order-discount-amount">
            <?php echo '-' . wp_kses_post( directorist_price( $amount ) );?>
        </div>
    </td>
</tr>
<?php endif; ?>
