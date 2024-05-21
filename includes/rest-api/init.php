<?php
/**
 * Init REST api.
 */
namespace wpWax\Directorist\RestApi;

defined( 'ABSPATH' ) || die();

require_once trailingslashit( __DIR__ ) . 'functions.php';
require_once trailingslashit( __DIR__ ) . 'class-datetime.php';
require_once trailingslashit( __DIR__ ) . 'filter-functions.php';

function register_controllers() {
	$dir = trailingslashit( __DIR__ );

	// Base controller.
	require_once $dir . 'Version1/class-abstract-controller.php';

	// Listings
	require_once $dir . 'Version1/class-abstract-posts-controller.php';
	require_once $dir . 'Version1/class-listings-controller.php';

	$listings_v1 = new \Directorist\Rest_Api\Controllers\Version1\Listings_Controller();
	$listings_v1->register_routes();

	// Listings actions
	require_once $dir . 'Version1/class-listings-actions-controller.php';

	$listings_actions = new \Directorist\Rest_Api\Controllers\Version1\Listings_Actions_Controller();
	$listings_actions->register_routes();

	// Listing reviews
	require_once $dir . 'Version1/class-listing-reviews-controller.php';

	$listing_reviews = new \Directorist\Rest_Api\Controllers\Version1\Listing_Reviews_Controller();
	$listing_reviews->register_routes();

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

	// Users favorites controller.
	require_once $dir . 'Version1/class-users-favorites-controller.php';

	$user_favorites = new \Directorist\Rest_Api\Controllers\Version1\User_Favorites_Controller();
	$user_favorites->register_routes();

	// Users account controller.
	require_once $dir . 'Version1/class-users-account-controller.php';

	$users_account = new \Directorist\Rest_Api\Controllers\Version1\Users_Account_Controller();
	$users_account->register_routes();

	// Directory types
	require_once $dir . 'Version1/class-directories-controller.php';

	$directories = new \Directorist\Rest_Api\Controllers\Version1\Directories_Controller();
	$directories->register_routes();

	// Directory builder
	require_once $dir . 'Version1/class-builder-controller.php';

	$builder = new \Directorist\Rest_Api\Controllers\Version1\Builder_Controller();
	$builder->register_routes();

	// Plans controller
	require_once $dir . 'Version1/class-plans-controller.php';

	$plans = new \Directorist\Rest_Api\Controllers\Version1\Plans_Controller();
	$plans->register_routes();

	// Temporary Media Upload controller
	require_once $dir . 'Version1/class-temporary-media-upload-controller.php';

	$temp_media_upload = new \Directorist\Rest_Api\Controllers\Version1\Temporary_Media_Upload_Controller();
	$temp_media_upload->register_routes();

	// Orders controller
	require_once $dir . 'Version1/class-orders-controller.php';

	$orders = new \Directorist\Rest_Api\Controllers\Version1\Orders_Controller();
	$orders->register_routes();

	// V2 Listings
	require_once $dir . 'Version2/class-listings-controller.php';

	$listings_v2 = new \Directorist\Rest_Api\Controllers\Version2\Listings_Controller();
	$listings_v2->register_routes();
}
add_action( 'rest_api_init', __NAMESPACE__ . '\\register_controllers' );
