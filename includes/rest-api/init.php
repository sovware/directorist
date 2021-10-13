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

	// Base controller.
	require_once $dir . 'Version1/class-abstract-controller.php';

	// Listings
	require_once $dir . 'Version1/class-abstract-posts-controller.php';
	require_once $dir . 'Version1/class-listings-controller.php';

	$listings = new \Directorist\Rest_Api\Controllers\Version1\Listings_Controller();
	$listings->register_routes();

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

	// Users activities controller.
	require_once $dir . 'Version1/class-users-activities-controller.php';

	$user_activities = new \Directorist\Rest_Api\Controllers\Version1\User_Activities_Controller();
	$user_activities->register_routes();

	// Directory types
	require_once $dir . 'Version1/class-directories-controller.php';

	$directories = new \Directorist\Rest_Api\Controllers\Version1\Directories_Controller();
	$directories->register_routes();
}
add_action( 'rest_api_init', __NAMESPACE__ . '\\register_controllers' );
