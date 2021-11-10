<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$id = get_the_ID();

if ( !Helper::has_price_range( $id ) && !Helper::has_price( $id ) ) {
	return;
}
?>

<span class="directorist-info-item directorist-pricing-meta">
	<?php
	if ( 'range' === Helper::pricing_type( $id ) ) {
		Helper::price_range_template( $id, $args );
	}
	elseif ( !$listings->is_disable_price ) {
		Helper::price_template( $id, $args );
	}
	?>
</span>