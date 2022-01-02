<?php
/**
 * @author wpWax
 *
 * Avoiding PHP fatal errors when using deprecated models
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Deprecated_Model {

	public static $instance = null;

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __set($name, $value) {

	}

	public function __get($name) {

	}

	public function __call($name, $arguments) {

	}

	public static function __callStatic($name, $arguments){

	}
}

class Directorist_Account {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\Account' );
	}
}

class Directorist_All_Authors {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\All_Authors' );
	}
}

class Directorist_Listing_Author {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\Author' );
	}
}

class Directorist_Listing_Dashboard {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\Dashboard' );
	}
}

class Directorist_Listing_Form {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\Listing_Form' );
	}
}

class Directorist_Listing_Taxonomy {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\Listing_Taxonomy' );
	}
}

class Directorist_Listings {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\Listings' );
	}
}

class Directorist_Listing_Search_Form {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\Search_Form' );
	}
}

class Directorist_Single_Listing {
	use Deprecated_Model;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.1.0', 'wpWax\Directorist\Model\Single_Listing' );
	}
}