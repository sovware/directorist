<?php
/**
 * Directorist Social Info Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Social_Info_Field extends Base_Field {

	public $type = 'social_info';

	public function get_value( $posted_data ) {
		if ( ! isset( $posted_data['social'] ) || ! is_array( $posted_data['social'] ) ) {
			return array();
		}

		return $posted_data['social'];
	}

	public function validate( $posted_data ) {
		$items = $this->get_value( $posted_data );

		$items = array_filter( $items, static function( $item ) {
			return ! ( empty( $item['id'] ) || empty( $item['url'] ) );
		} );

		if ( ! count( $items ) ) {
			$this->add_error( __( 'Invalid social info.', 'directorist' ) );
			return false;
		}

		return true;
	}

	public function sanitize( $posted_data ) {
		$_items = $this->get_value( $posted_data );

		$items = array();

		foreach ( $_items as $item ) {
			if ( empty( $item['id'] ) || empty( $item['url'] ) ) {
				continue;
			}

			$items[] = array(
				'id'  => $item['id'],
				'url' => sanitize_url( $item['url'] )
			);
		}

		return $items;
	}
}

Fields::register( new Social_Info_Field() );
