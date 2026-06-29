<?php

defined( "ABSPATH" ) || exit;

/**
 * @var \WP_Post $listing
 * @var \WP_REST_Request $request
*/

?>
<input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing->ID ); ?>">
<tr>
    <td colspan="2">
        <span class="directorist-summery-label">
            <?php esc_html_e( 'Featured Listing', 'directorist' ); ?>
        </span>
    </td>
    <td class="directorist-text-right">
        <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount">
            <?php echo esc_html( $listing->post_title ); ?>
        </div>
    </td>
</tr>

<?php directorist_template_render( 'checkout/checkout-order-sub-total', [ 'sub_total' => $subtotal ] ) ?>
