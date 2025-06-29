<?php
/**
 * Directorist Locations Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Locations_Field extends Taxonomy_Field {

	public $type = 'locations';

	function get_taxonomy() : string {
		return ATBDP_LOCATION;
	}

	public function user_can_create() : bool {
		return (bool) $this->create_new_loc;
	}
}

Fields::register( new Locations_Field() );
