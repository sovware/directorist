<?php
/**
 * Directorist Tags Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tags_Field extends Taxonomy_Field {

	public $type = 'tags';

	function get_taxonomy() : string {
		return ATBDP_TAGS;
	}

	public function user_can_create() : bool {
		return (bool) $this->allow_new;
	}
}

Fields::register( new Tags_Field() );
