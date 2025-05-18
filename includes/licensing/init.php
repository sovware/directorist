<?php
/**
 * Init Licensing.
 */
namespace Directorist\Licensing;

defined( 'ABSPATH' ) || die();

class Init {
	public function __construct() {
		require_once trailingslashit( __DIR__ ) . 'functions.php';
		require_once trailingslashit( __DIR__ ) . 'class-controllers.php';
		require_once trailingslashit( __DIR__ ) . 'class-routes.php';

		add_action( 'admin_menu', [$this, 'add_menu_page'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );

		new Routes();
	}

	public function enqueue_assets() {

		$url = ATBDP_URL . 'includes/licensing/assets/';

		wp_register_style( 'directorist-licensing-css', $url . 'style.css', [], '1.0' );
		wp_register_script( 'directorist-licensing-js', $url . 'script.js', [], '1.0' );

		wp_enqueue_style( 'directorist-licensing-css' );
		wp_enqueue_script( 'directorist-licensing-js' );
	}

	public function add_menu_page() {
		add_submenu_page(
			'edit.php?post_type=at_biz_dir',
			'Licensing',
			'Licensing',
			'manage_options',
			'directorist-licensing',
			[$this, 'directorist_licensing'],
			12
		);
	}

	public function directorist_licensing() {
		include 'views.php';
	}
}

new Init();