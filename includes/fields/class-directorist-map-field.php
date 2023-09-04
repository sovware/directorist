<?php
/**
 * Directorist Map Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Map_Field extends Base_Field {

	public $type = 'map';

	public function get_value( $posted_data ) {
		if ( empty( $posted_data['manual_lat'] ) && empty( $posted_data['manual_lng'] ) && ! isset( $posted_data['hide_map'] ) ) {
			return array();
		}

		return array(
			'hide_map'   => (bool) sanitize_text_field( directorist_get_var( $posted_data['hide_map'] ) ),
			'manual_lat' => sanitize_text_field( directorist_get_var( $posted_data['manual_lat'] ) ),
			'manual_lng' => sanitize_text_field( directorist_get_var( $posted_data['manual_lng'] ) )
		);
	}
}

Fields::register( new Map_Field() );
