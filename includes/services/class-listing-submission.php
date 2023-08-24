<?php
/**
 * Directorist Listing Submission class.
 *
 */
namespace Directorist\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Listing_Entry {

	public function process( array $input, $directory_id = 0, $target_group = 0, $source_group = 0 ) {
		if ( ! isset( $_POST ) ) {
			$_POST = $input;
		} else {
			$_POST = array_merge_recursive( $_POST, $input );
		}

	}

	public static function validate() {

	}
}
