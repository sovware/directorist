<?php
/**
 * Directorist Pricing Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pricing_Field extends Base_Field {

	public $type = 'pricing';

	public function get_value( $posted_data ) {
		if ( $this->get_price_type_prop() !== 'both' ) {
			$posted_data['atbd_listing_pricing'] = $this->get_price_type_prop();
		}

		if ( ! isset( $posted_data['atbd_listing_pricing'] ) && ( isset( $posted_data['price'] ) || isset( $posted_data['price_range'] ) ) ) {
			return array();
		}

		return array(
			'price_type'  => sanitize_text_field( directorist_get_var( $posted_data['atbd_listing_pricing'] ) ),
			'price'       => round( (float) directorist_get_var( $posted_data['price'], 0 ), 2 ),
			'price_range' => sanitize_text_field( directorist_get_var( $posted_data['price_range'] ) )
		);
	}

	public function validate( $posted_data ) {
		$value = $this->get_value( $posted_data );

		if ( ! empty( $value['price_type'] ) && ! in_array( $value['price_type'], $this->get_price_types(), true ) ) {
			$this->add_error( __( 'Invalid price type.' . $value['price_type'], 'directorist' ) );
		}

		if ( $value['price_type'] === 'range' && ! empty( $value['price_range'] ) && ! in_array( $value['price_range'], $this->get_price_ranges(), true ) ) {
			$this->add_error( __( 'Invalid price range.', 'directorist' ) );
		}

		if ( $this->has_error() ) {
			return false;
		}

		return true;
	}

	protected function get_price_types() {
		return array( 'price', 'range' );
	}

	protected function get_price_ranges() {
		return array( 'skimming', 'moderate', 'economy', 'bellow_economy' );
	}

	protected function get_price_type_prop() {
		$pricing_type = $this->__get( 'pricing_type' );

		if ( $pricing_type === 'price_unit' ) {
			return 'price';
		} elseif ( $pricing_type === 'price_range' ) {
			return 'range';
		} else {
			return 'both';
		}
	}
}

Fields::register( new Pricing_Field() );
