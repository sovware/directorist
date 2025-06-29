<?php
/**
 * Directorist Categories Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Categories_Field extends Taxonomy_Field {

	public $type = 'categories';

	function get_taxonomy() : string {
		return ATBDP_CATEGORY;
	}

	public function user_can_create() : bool {
		return (bool) $this->create_new_cat;
	}
}

Fields::register( new Categories_Field() );
