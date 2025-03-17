<?php
/**
 * Init Licensing.
 */
namespace Directorist\Licensing;

defined( 'ABSPATH' ) || die();

class Init {
	public function __construct() {
		// Load all required files in a proper sequence
		$this->load_dependencies();

		// Add WordPress Hooks
		add_action( 'admin_menu', [$this, 'add_menu_page'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );

		// Initialize Routes
		new Routes();
	}

	private function load_dependencies() {
		$files = [
			'functions.php', // General helper functions (No dependencies)
			'utils/init.php', // Initialization logic (No dependencies)

			// Licensing Core First (Base class must be first)
			'class-licensing.php', // Main Licensing class (MUST be first)

			// Licensing Components (These depend on `class-licensing.php`)
			'class-licensing-access.php', // Handles Access Keys
			'class-licensing-account.php', // Manages Account Info
			'class-licensing-plan.php', // Plan and Subscription Info
			'class-licensing-products.php', // Handles Licensing Products
			'class-licensing-overview.php', // Licensing Overview (depends on `products`)

			// Other Core Classes (These might depend on licensing)
			'class-repository.php', // Data repository (May use Licensing data)
			'class-extension-handler.php', // Extension Handler
			'class-controllers.php', // Business logic controllers (May call Repository)
			'class-routes.php', // API/route handling (Uses Controllers)
		];

		foreach ( $files as $file ) {
			require_once trailingslashit( __DIR__ ) . $file;
		}
	}

	public function enqueue_assets() {

		$url = ATBDP_URL . 'includes/licensing/assets/';

		wp_register_style( 'directorist-licensing-css', $url . 'style.css', [], '1.0' );
		wp_register_script( 'directorist-licensing-js', $url . 'script.js', [], '1.0' );

		wp_enqueue_style( 'directorist-licensing-css' );
		wp_enqueue_script( 'directorist-licensing-js' );

		wp_localize_script( 'directorist-licensing-js', 'directorist_licensing',
			[
				'root'  => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			]
		);
	}

	public function add_menu_page() {
		add_submenu_page(
			'edit.php?post_type=at_biz_dir',
			'Extensions & Templates (new)',
			'Extensions & Templates (new)',
			'manage_options',
			'directorist-licensing',
			[$this, 'directorist_licensing'],
			12
		);
	}

	public function directorist_licensing() {
		include 'views/main.php';
		// include 'legacy-views.php';
	}
}

new Init();