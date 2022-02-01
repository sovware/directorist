<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !Helper::get_price_range() && !Helper::get_price() ) {
	return;
}
?>

<span class="directorist-info-item directorist-pricing-meta">
	<?php
	if ( 'range' === Helper::pricing_type() ) {
		Helper::price_range_template();
	}
	else {
		Helper::price_template();
	}
	?>
</span>