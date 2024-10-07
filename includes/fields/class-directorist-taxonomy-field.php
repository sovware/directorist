<?php
/**
 * Directorist Taxonomy Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Taxonomy_Field extends Base_Field {

	public $type = 'taxonomy';

	abstract protected function get_taxonomy() : string;

	abstract public function user_can_create() : bool;

	public function user_can_select_multiple() : bool {
		return (bool) ( $this->__get( 'type' ) === 'multiple' );
	}

	public function get_value( $posted_data ) {
		if ( ! isset( $posted_data['tax_input'] ) ) {
			return null;
		}

		if ( ! in_array( $this->get_taxonomy(), array( ATBDP_TAGS, ATBDP_LOCATION, ATBDP_CATEGORY ), true ) ) {
			return null;
		}

		if ( ! isset( $posted_data['tax_input'][ $this->get_taxonomy() ] ) ) {
			return null;
		}

		$terms = (array) directorist_get_var( $posted_data['tax_input'][ $this->get_taxonomy() ], array() );
		$terms = array_map( 'directorist_sanitize_term_item', $terms );
		$terms = array_filter( $terms );

		return $terms;
	}

	public function validate( $posted_data ) {
		$items       = $this->get_value( $posted_data );
		$max_limit   = (int) $this->__get( 'max' );
		$total_items = count( $items );

		if ( $max_limit != -1 && $max_limit > 0 && $total_items > $max_limit ) {
			$this->add_error( sprintf( _n( 'Only %1$s item is allowed.', 'Only %1$s items are allowed.', $max_limit, 'directorist' ), $max_limit ) );
			return false;
		}

		return true;
	}
}
