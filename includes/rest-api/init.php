<?php
/**
 * Init REST api.
 */
namespace wpWax\Directorist\RestApi;

defined( 'ABSPATH' ) || die();

require_once trailingslashit( __DIR__ ) . 'functions.php';
require_once trailingslashit( __DIR__ ) . 'class-datetime.php';

function register_controllers() {
	$dir = trailingslashit( __DIR__ );

	// require_once $dir . 'class-abstract-controller.php';
	// require_once $dir . 'class-listings-rest-controller.php';

	// $listings = new Listings_REST_Controller();
	// $listings->register_routes();

	// Base controller.
	require_once $dir . 'Version1/class-abstract-controller.php';

	// Taxonomies controllers.
	require_once $dir . 'Version1/class-abstract-terms-controller.php';
	require_once $dir . 'Version1/class-categories-controller.php';
	require_once $dir . 'Version1/class-tags-controller.php';
	require_once $dir . 'Version1/class-locations-controller.php';

	$categories = new \Directorist\Rest_Api\Controllers\Version1\Categories_Controller();
	$categories->register_routes();

	$tags = new \Directorist\Rest_Api\Controllers\Version1\Tags_Controller();
	$tags->register_routes();

	$locations = new \Directorist\Rest_Api\Controllers\Version1\Locations_Controller();
	$locations->register_routes();

	// Users controllers.
	require_once $dir . 'Version1/class-users-controller.php';

	$users = new \Directorist\Rest_Api\Controllers\Version1\Users_Controller();
	$users->register_routes();
}
add_action( 'rest_api_init', __NAMESPACE__ . '\\register_controllers' );
