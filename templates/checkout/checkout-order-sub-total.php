<?php
    defined( 'ABSPATH' ) || exit;
    /**
     * @var float $sub_total
     */
?>

<tr class="atbdp_ch_subtotal directorist-checkout-subtotal directorist-row--order-sub-total" data-order-sub-total="<?php echo esc_attr( $sub_total ); ?>" data-subtotal="<?php echo esc_attr( $sub_total ); ?>">
    <td colspan="2">
        <span class="directorist-summery-label directorist-row-label--order-sub-total">
            <?php esc_html_e( 'Subtotal', 'directorist-pricing-plans' ); ?>
        </span>
    </td>
    <td class="directorist-text-right">
        <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount directorist-row-value--order-sub-total">
            <?php echo wp_kses_post( directorist_price( $sub_total ) ); ?>
        </div>
    </td>
</tr>
