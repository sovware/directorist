<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$id = $listing->id;

if ( !Helper::has_price_range( $id ) && !Helper::has_price( $id ) ) {
	return;
}

if ( 'range' === Helper::pricing_type( $id ) ) {
	Helper::price_range_template( $id );
}
else {
	Helper::price_template( $id );
}