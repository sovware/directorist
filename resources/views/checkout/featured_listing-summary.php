<?php

defined( "ABSPATH" ) || exit;

/**
 * @var \WP_Post $listing
 * @var \WP_REST_Request $request
*/

?>
<input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing->ID ); ?>">
<tr>
    <td>Featured Listing</td>
    <td><?php echo esc_html( $listing->post_title ); ?></td>
</tr>
<tr class="atbdp_ch_subtotal">
    <td colspan="2" class="">
        <span class="directorist-summery-label"><?php esc_html_e( 'Subtotal', 'directorist' ); ?></span>
    </td>
    <td class="directorist-text-right">
        <div id="atbdp_checkout_subtotal_amount" class="directorist-summery-amount">
            <?php
            echo wp_kses_post( directorist_price( $subtotal ) );
            ?>
        </div>
    </td>
</tr>
