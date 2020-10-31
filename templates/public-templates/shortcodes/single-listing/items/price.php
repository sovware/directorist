<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

if ( $listing->has_price_range() ) {
	echo $listing->price_range_html();
}
elseif( $listing->price_html() ) {
	echo $listing->price_html();
}