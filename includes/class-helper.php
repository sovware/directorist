<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Helper {

	use URI_Helper;
	use Markup_Helper;


	public static function price_range_template( $price_range ) {
		$currency = get_directorist_option( 'g_currency', 'USD' );
		$currency = atbdp_currency_symbol( $currency );

		switch ( $price_range ) {
			case 'skimming':
			$active_items = 4;
			break;

			case 'moderate':
			$active_items = 3;
			break;

			case 'economy':
			$active_items = 2;
			break;

			case 'bellow_economy':
			$active_items = 1;
			break;
			
			default:
			$active_items = 4;
			break;
		}

		self::get_template( 'global/price-range', compact( 'active_items', 'currency' ) );
	}

	public static function formatted_price( $price ) {
		$allow_decimal = get_directorist_option('allow_decimal', 1);
		$c_position    = get_directorist_option('g_currency_position');
		$currency      = get_directorist_option('g_currency', 'USD');
		$symbol        = atbdp_currency_symbol($currency);
		$before        = '';
		$after         = '';

		if ('after' == $c_position) {
			$after = $symbol;
		}
		else {
			$before = $symbol;
		}

		$price = $before . atbdp_format_amount( $price, $allow_decimal ) . $after;
		return $price;
	}
	
}