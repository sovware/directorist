<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$id = get_the_ID();
$price = get_post_meta( $id, '_price', true );
$price_range = get_post_meta( $id, '_price_range', true );
$pricing_type = get_post_meta( $id, '_atbd_listing_pricing', true );
?>

<span class="directorist-info-item directorist-pricing-meta">
	<?php
	if ( !empty( $price_range ) && ( 'range' === $pricing_type ) ) {
		Helper::price_range_template( $price_range );
	}
	else {
		if ( !$listings->is_disable_price ) {
			printf( '<span class="directorist-listing-price">%s</span>', Helper::formatted_price( $price ) );
		}
	}
	?>
</span>