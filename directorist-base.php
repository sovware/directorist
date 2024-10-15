<?php
/**
 * Plugin Name: Directorist - Business Directory Plugin
 * Plugin URI: https://wpwax.com
 * Description: A comprehensive solution to create professional looking directory site of any kind. Like Yelp, Foursquare, etc.
 * Version: 8.0.0 - RC 5
 * Author: wpWax
 * Author URI: https://wpwax.com
 * Text Domain: directorist
 * Domain Path: /languages
 */

// prevent direct access to the file
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Main Directorist_Base Class.
 *
 * @since 1.0
 */
final class Directorist_Base
{
	/** Singleton *************************************************************/

	/**
	 * @var Directorist_Base The one true Directorist_Base
	 * @since 1.0
	 */
	private static $instance;

	/**
	 * ATBDP_Metabox Object.
	 *
	 * @var object|ATBDP_Metabox
	 * @since 1.0
	 */
	public $metabox;

	/**
	 * ATBDP_Custom_Post Object.
	 *
	 * @var object|ATBDP_Custom_Post
	 * @since 1.0
	 */
	public $custom_post;

	/**
	 * ATBDP_Custom_Taxonomy Object.
	 *
	 * @var object|ATBDP_Custom_Taxonomy
	 * @since 1.0
	 */
	public $taxonomy;

	/**
	 * ATBDP_Ajax_Handler Object.
	 *
	 * @var object|ATBDP_Ajax_Handler
	 * @since 1.0
	 */
	public $ajax_handler;

	/**
	 * ATBDP_Shortcode Object.
	 *
	 * @var object|ATBDP_Shortcode
	 * @since 1.0
	 */
	public $shortcode;

	/**
	 * ATBDP_Helper Object.
	 *
	 * @var object|ATBDP_Helper
	 * @since 1.0
	 */
	public $helper;

	/**
	 * ATBDP_Listing Object.
	 *
	 * @var object|ATBDP_Listing
	 * @since 1.0
	 */
	public $listing;

	/**
	 * ATBDP_User Object.
	 *
	 * @var object|ATBDP_User
	 * @since 1.0
	 */
	public $user;

	/**
	 * ATBDP_Roles Object.
	 *
	 * @var object|ATBDP_Roles
	 * @since 3.0
	 */
	public $roles;

	/**
	 * ATBDP_Gateway Object.
	 *
	 * @var ATBDP_Gateway
	 * @since 3.1.0
	 */
	public $gateway;

	/**
	 * ATBDP_Order Object.
	 *
	 * @var ATBDP_Order
	 * @since 3.1.0
	 */
	public $custom_field;

	/**
	 * ATBDP_Custom_Field Object.
	 *
	 * @var ATBDP_Custom_Field
	 * @since 3.1.6
	 */
	public $order;

	/**
	 * ATBDP_Email Object.
	 *
	 * @var ATBDP_Email
	 * @since 3.1.0
	 */
	public $email;

	/**
	 * ATBDP_SEO Object.
	 *
	 * @var ATBDP_SEO
	 * @since 4.7.0
	 */
	public $seo;

	/**
	 * ATBDP_Tools Object.
	 *
	 * @var ATBDP_Tools
	 * @since 4.7.2
	 */
	public $tools;

	/**
     * Insights class
     *
     * @var Insights
	 * @since 8.0
     */
    public $insights = null;

	/**
	 * ATBDP_Single_Templates Object.
	 *
	 * @var ATBDP_Single_Templates
	 * @since 5.0.5
	 */
	public $ATBDP_Single_Templates;

	public $multi_directory_manager;
	public $settings_panel;
	public $hooks;
	public $announcement;
	public $review;

	public $background_image_process = null;

	/**
	 * Main Directorist_Base Instance.
	 *
	 * Insures that only one instance of Directorist_Base exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @static
	 * @static_var array $instance
	 * @uses Directorist_Base::setup_constants() Setup the constants needed.
	 * @uses Directorist_Base::includes() Include the required files.
	 * @uses Directorist_Base::load_textdomain() load the language files.
	 * @see  ATBDP()
	 * @return object|Directorist_Base The one true Directorist_Base
	 */
	public static function instance()
	{
		if (!isset(self::$instance) && !(self::$instance instanceof Directorist_Base)) {
			self::$instance = new Directorist_Base();
			self::$instance->setup_constants();

			add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
			add_action('plugins_loaded', array(self::$instance, 'add_polylang_swicher_support') );
			add_action('widgets_init', array(self::$instance, 'register_widgets'));
			add_filter('widget_display_callback', array(self::$instance, 'custom_widget_body_wrapper'), 10, 3);
			add_action('after_setup_theme', array(self::$instance, 'add_image_sizes'));

			add_action( 'template_redirect', [ self::$instance, 'check_single_listing_page_restrictions' ] );
			add_action( 'atbdp_show_flush_messages', [ self::$instance, 'show_flush_messages' ] );

			self::$instance->includes();

			self::$instance->custom_post = new ATBDP_Custom_Post(); // create custom post
			self::$instance->taxonomy = new ATBDP_Custom_Taxonomy();

			add_action('init', array( self::$instance, 'on_install_update_actions' ) );

			Directorist\Asset_Loader\Asset_Loader::init();

			// ATBDP_Listing_Type_Manager
			self::$instance->multi_directory_manager = new Directorist\Multi_Directory\Multi_Directory_Manager();
			self::$instance->multi_directory_manager->run();

			self::$instance->settings_panel = new ATBDP_Settings_Panel();
			self::$instance->settings_panel->run();

			self::$instance->hooks = new ATBDP_Hooks();
			self::$instance->metabox = new ATBDP_Metabox();
			self::$instance->ajax_handler = new ATBDP_Ajax_Handler();
			self::$instance->helper = new ATBDP_Helper();
			self::$instance->listing = new ATBDP_Listing();
			self::$instance->user = new ATBDP_User();
			self::$instance->roles = new ATBDP_Roles();
			if( class_exists( 'ATBDP_Gateway' ) ) {
				self::$instance->gateway = new ATBDP_Gateway();
			}
			self::$instance->order = new ATBDP_Order();
			self::$instance->shortcode = new \Directorist\ATBDP_Shortcode();
			self::$instance->email = new ATBDP_Email();
			self::$instance->seo = new ATBDP_SEO();
			// self::$instance->validator = new ATBDP_Validator;
			// self::$instance->ATBDP_Single_Templates = new ATBDP_Single_Templates;
			self::$instance->tools = new ATBDP_Tools();
			self::$instance->announcement = new ATBDP_Announcement();

			self::$instance->background_image_process = new \Directorist\Background_Image_Process();

			// Load widgets
			Directorist\Widgets\Init::instance();

			// Load widgets
			Directorist\Widgets\Init::instance();

			/*Extensions Link*/
			/*initiate extensions link*/

			if( is_admin() ){
				new ATBDP_Extensions();
			}

			/**
			 * Deprected review rating class.
			 * Will be removed in future.
			 */
			include_once ATBDP_INC_DIR . 'review/class-bc-review-rating.php';
			self::$instance->review = new ATBDP_Review_Rating();

			//activate rewrite api
			new ATBDP_Rewrite();
			//map custom capabilities
			add_filter('map_meta_cap', array(self::$instance->roles, 'meta_caps'), 10, 4);
			//add dtbdp custom body class
			add_filter('body_class', array(self::$instance, 'atbdp_body_class'), 99);

			// Attempt to create listing related custom pages with plugin's custom shortcode to give user best experience.
			// we can check the database if our custom pages have been installed correctly or not here first.
			// This way we can minimize the adding of our custom function to the WordPress hooks.

			if (get_option('atbdp_pages_version') < 1) {
				add_action('wp_loaded', array(self::$instance, 'add_custom_directorist_pages'));
			}

			// init offline gateway
			new ATBDP_Offline_Gateway();
			// Init Cron jobs to run some periodic tasks
			new ATBDP_Cron();
			// add upgrade feature
			new ATBDP_Upgrade();
			// add uninstall menu
			add_filter('atbdp_settings_menus', array(self::$instance, 'add_uninstall_menu'));
			add_filter( 'display_post_states', array(self::$instance, 'add_page_states'), 10, 2 );
			self::init_hooks();

			// Initialize appsero tracking
			self::$instance->init_appsero();

			// Register blocks
			self::$instance->init_blocks();

			/**
			 * Fire loaded action hook once everything is loaded.
			 *
			 * Call anything safely once Directorist is fully loaded with all functionalites.
			 * For example, all the Directorist extensions can use this hook to load safely.
			 * Usage:
			 * add_action( 'directorist_loaded', static function( $instance ) {
			 *     $instance->{any prop or method}
			 * } );
			 *
			 * @since 7.2.0
			 *
			 * @param object Instance of Directorist_Base
			 */
			do_action( 'directorist_loaded', self::$instance );
		}

		return self::$instance;
	}

	// on_install_update_actions
	public function on_install_update_actions() {
		$install_event_key = get_directorist_option( 'directorist_installed_event_key', '', true );

		// Execute directorist_installed hook if plugin gets installed first time
		if ( empty( $install_event_key ) ) {
			update_directorist_option( 'directorist_installed_event_key', ATBDP_VERSION );
			update_directorist_option( 'directorist_updated_event_key', ATBDP_VERSION );

			do_action( 'directorist_installed' );
			return;
		}

		// Prevent executing directorist_updated hook if plugin is not updated
		$update_event_key = get_directorist_option( 'directorist_updated_event_key', '', true );
		if ( $update_event_key === ATBDP_VERSION ) {
			return;
		}

		// Execute directorist_updated hook if plugin gets updated
		do_action( 'directorist_updated' );
		update_directorist_option( 'directorist_updated_event_key', ATBDP_VERSION );
	}

	// show_flush_messages
	public function show_flush_messages() {
		atbdp_get_flush_messages();
	}

	// check_single_listing_page_restrictions
	public function check_single_listing_page_restrictions() {
		if ( is_user_logged_in() || ! is_singular( ATBDP_POST_TYPE ) ) {
			return;
		}

		$is_logged_in_users_only = (bool) get_directorist_option( 'restrict_single_listing_for_logged_in_user', false );
		if ( ! $is_logged_in_users_only ) {
			return;
		}

		atbdp_auth_guard();
	}

	// add_polylang_swicher_support
	public function add_polylang_swicher_support() {
		add_filter('pll_the_language_link', function($url, $current_lang) {
			// Adjust the category link
			$category_url = $this->get_polylang_swicher_link_for_term([
				'term_type'            => 'category',
				'term_default_page_id' => get_directorist_option('single_category_page'),
				'term_query_var'       => ( ! empty( $_GET['category'] ) ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : get_query_var('atbdp_category'),
				'current_lang'         => $current_lang,
				'url'                  => $url,
			]);

			if ( ! empty( $category_url ) ) { return $category_url; }

			// Adjust the location link
			$location_url = $this->get_polylang_swicher_link_for_term([
				'term_type'            => 'location',
				'term_default_page_id' => get_directorist_option('single_location_page'),
				'term_query_var'       => ( ! empty( $_GET['location'] ) ) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : get_query_var('atbdp_location'),
				'current_lang'         => $current_lang,
				'url'                  => $url,
			]);

			if ( ! empty( $location_url ) ) { return $location_url; }

			return $url;
		}, 10, 2);
	}

	// get_polylang_swicher_link_for_term
	public function get_polylang_swicher_link_for_term( $args ) {
		if ( ! function_exists( 'pll_get_post_language' ) ) {
			return;
		}

		$default = [
			'term_type'            => '',
			'term_query_var'       => '',
			'term_default_page_id' => '',
			'current_lang'         => '',
			'url'                  => '',
		];

		$args = array_merge( $default, $args );

		if ( empty( $args[ 'term_query_var' ] ) ) { return false; }

		// Get language slug of the default page
		$page_lang = pll_get_post_language( $args[ 'term_default_page_id' ] );

		// If current lang slug != default page
		// modyfy the url
		if ( $args[ 'current_lang' ] !== $page_lang ) {
			return $args['url'] ."?". $args['term_type'] ."=". $args['term_query_var'];
		}

		if ( $args[ 'current_lang' ] === $page_lang  ) {
			return $args['url'] . $args['term_query_var'];
		}

		return false;
	}

	/**
	 * Init Hooks
	 *
	 * @access private
	 * @since 6.4.5
	 * @return void
	 */
	public static function init_hooks()
	{
		ATBDP_Cache_Helper::reset_cache();
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function setup_constants()
	{
		// test
		require_once plugin_dir_path(__FILE__) . '/config.php'; // loads constant from a file so that it can be available on all files.
	}

	private function autoload( $dir = '' ) {
		if ( !file_exists( $dir ) ) return;
		foreach ( scandir( $dir ) as $file ) {
			if ( preg_match( "/.php$/i", $file ) ) {
				require_once( $dir . $file );
			}
		}
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.0
	 * @return void
	 */
	private function includes()
	{
		$this->autoload( ATBDP_INC_DIR . 'helpers/' );
		$this->autoload( ATBDP_INC_DIR . 'asset-loader/' );
		$this->autoload( ATBDP_INC_DIR . 'widgets/' );

		self::require_files([
			ATBDP_INC_DIR . 'directorist-core-functions',
			ATBDP_INC_DIR . 'directorist-directory-functions',
			ATBDP_INC_DIR . 'class-helper',
			ATBDP_INC_DIR . 'helper-functions',
			ATBDP_INC_DIR . 'template-functions',
			ATBDP_INC_DIR . 'custom-actions',
			ATBDP_INC_DIR . 'custom-filters',
			ATBDP_INC_DIR . 'elementor/init',
			ATBDP_INC_DIR . 'system-status/class-system-status',
			ATBDP_INC_DIR . 'review/init',
			ATBDP_INC_DIR . 'rest-api/init',
			ATBDP_INC_DIR . 'fields/init',
			ATBDP_INC_DIR . 'modules/multi-directory-setup/class-builder-data',
			ATBDP_INC_DIR . 'modules/multi-directory-setup/trait-multi-directory-helper',
			ATBDP_INC_DIR . 'modules/multi-directory-setup/class-multi-directory-migration',
			ATBDP_INC_DIR . 'modules/multi-directory-setup/class-multi-directory-manager',
		]);

		$this->autoload( ATBDP_INC_DIR . 'database/' );

		load_dependencies('all', ATBDP_INC_DIR . 'data-store/');
		load_dependencies('all', ATBDP_INC_DIR . 'model/');
		load_dependencies('all', ATBDP_INC_DIR . 'hooks/');
		load_dependencies('all', ATBDP_INC_DIR . 'modules/');

		load_dependencies('all', ATBDP_CLASS_DIR); // load all php files from ATBDP_CLASS_DIR

		/*Load gateway related stuff*/
		load_dependencies('all', ATBDP_INC_DIR . 'gateways/');
		/*Load payment related stuff*/
		load_dependencies('all', ATBDP_INC_DIR . 'payments/');
		load_dependencies('all', ATBDP_INC_DIR . 'checkout/');

		$this->autoload( ATBDP_INC_DIR . 'deprecated/' );
	}

	// require_files
	public static function require_files( array $files = [] ) {
		foreach ( $files as $file ) {
			if ( file_exists( "{$file}.php" ) ) {
				require_once "{$file}.php";
			}
		}
	}

	public static function prepare_plugin()
	{
		include ATBDP_INC_DIR . 'classes/class-installation.php';
		ATBDP_Installation::install();
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @access public
	 * @return void
	 */
	public function __clone()
	{
		// Cloning instances of the class is forbidden.
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'directorist'), '1.0'); // @codingStandardsIgnoreLine.
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 1.0
	 * @access public
	 * @return void
	 */
	public function __wakeup()
	{
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'directorist' ), '1.0'); // @codingStandardsIgnoreLine.
	}

	/**
	 * It registers widgets and sidebar support
	 *
	 * @since 1.0
	 * @access public
	 * @return void
	 */
	public function register_widgets()
	{
		if (!is_registered_sidebar('right-sidebar-listing')) {
			register_sidebar(array(
				'name' => apply_filters('atbdp_right_sidebar_name', __('Directorist - Listing Right Sidebar', 'directorist')),
				'id' => 'right-sidebar-listing',
				'description' => __('Add widgets for the right sidebar on single listing page', 'directorist'),
				'before_widget' => '<div class="directorist-card %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="directorist-card__header directorist-widget__header"><h3 class="directorist-card__header__title directorist-widget__header__title">',
				'after_title' => '</h3></div>',
			));
		}
	}

	public function custom_widget_body_wrapper( $instance, $widget, $args ) {
		// Check if this is the specific sidebar
		if ( $args['id'] === 'right-sidebar-listing' ) {
			// Create a wrapper for the widget body
			$widget_output = '';

			// Check and append before_widget if it exists
			if ( isset( $instance['before_widget'] ) ) {
				$widget_output .= $instance['before_widget'];
			}

			// Check and append before_title, title, and after_title if they exist
			if ( isset( $instance['before_title'] ) ) {
				$widget_output .= $instance['before_title'];
			}

			if ( isset( $instance['title'] ) ) {
				$widget_output .= $instance['title'];
			}

			if ( isset( $instance['after_title'] ) ) {
				$widget_output .= $instance['after_title'];
			}

			// Open custom body wrapper
			$widget_output .= '<div class="directorist-card__body">';

			// Check for actual widget content (may vary based on your widget implementation)
			if ( isset( $instance['content'] ) ) {
				$widget_output .= $instance['content'];
			}

			// Close custom body wrapper
			$widget_output .= '</div>';

			// Check and append after_widget if it exists
			if ( isset( $instance['after_widget'] ) ) {
				$widget_output .= $instance['after_widget'];
			}

			// Update instance output
			$instance['content'] = $widget_output;
		}

		return $instance;
	}


	public function add_image_sizes() {
		$current_preview_size = get_directorist_option( 'preview_image_quality', 'directorist_preview' );

		if ( $current_preview_size == 'directorist_preview' ) {
			$preview_size = directorist_default_preview_size();
			add_image_size( 'directorist_preview', $preview_size['width'], $preview_size['height'], $preview_size['crop'] );
		}
	}

	public function load_textdomain()
	{

		load_plugin_textdomain('directorist', false, ATBDP_LANG_DIR);
		if ( is_admin() && get_transient( '_directorist_setup_page_redirect' ) ) {
			directorist_redirect_to_admin_setup_wizard();
		}
	}

	/**
	 * It  loads a template file from the Default template directory.
	 * @todo; Improve this method in future so that it lets user/developers to change/override any templates this plugin uses
	 * @param string $name Name of the file that should be loaded from the template directory.
	 * @param array $args Additional arguments that should be passed to the template file for rendering dynamic  data.
	 * @param bool $return_path Whether to return the path instead of including it
	 * @return string|void
	 */
	public function load_template( $template_name, $args = array(), $return_path = false )
	{
		$path = ATBDP_VIEWS_DIR . $template_name . '.php';
		$path = apply_filters( 'directorist_admin_template', $path, $template_name, $args );

		if ( $return_path ) {
			return $path;
		}

		include($path);
	}

	public function add_custom_directorist_pages()
	{
		$create_permission = apply_filters('atbdp_create_required_pages', true);
		if ($create_permission){
			atbdp_create_required_pages();
		}
	}

	/**
	* Add a post display state for special Directorist pages in the page list table.
	*
	* @param array   $post_states An array of post display states.
	* @param WP_Post $post        The current post object.
	*/
	public function add_page_states( $post_states, $post ) {

		if ( get_directorist_option( 'add_listing_page' ) === $post->ID ) {
		   $post_states['directorist_add_listing'] = __( 'Directorist Add Listing', 'directorist' );
	   	}
		if ( get_directorist_option( 'all_listing_page' ) === $post->ID ) {
		   $post_states['directorist_all_listing'] = __( 'Directorist All Listings', 'directorist' );
	   	}
		if ( get_directorist_option( 'user_dashboard' ) === $post->ID ) {
		   $post_states['directorist_user_dashboard'] = __( 'Directorist Dashboard', 'directorist' );
	   	}
		if ( get_directorist_option( 'author_profile_page' ) === $post->ID ) {
		   $post_states['directorist_author_profile_page'] = __( 'Directorist Author Profile', 'directorist' );
	   	}
		if ( get_directorist_option( 'all_categories_page' ) === $post->ID ) {
		   $post_states['directorist_all_categories_page'] = __( 'Directorist All Categories', 'directorist' );
	   	}
		if ( get_directorist_option( 'single_category_page' ) === $post->ID ) {
		   $post_states['directorist_single_category_page'] = __( 'Directorist Single Category', 'directorist' );
	   	}
		if ( get_directorist_option( 'all_locations_page' ) === $post->ID ) {
		   $post_states['directorist_all_locations_page'] = __( 'Directorist All Locations', 'directorist' );
	   	}
		if ( get_directorist_option( 'single_location_page' ) === $post->ID ) {
		   $post_states['directorist_single_location_page'] = __( 'Directorist Single Location', 'directorist' );
	   	}
		if ( get_directorist_option( 'single_tag_page' ) === $post->ID ) {
		   $post_states['directorist_single_tag_page'] = __( 'Directorist Single Tag', 'directorist' );
	   	}
		if ( get_directorist_option( 'search_listing' ) === $post->ID ) {
		   $post_states['directorist_search_listing'] = __( 'Directorist Listing Search Form', 'directorist' );
	   	}
		if ( get_directorist_option( 'search_result_page' ) === $post->ID ) {
		   $post_states['directorist_search_result_page'] = __( 'Directorist Search Result', 'directorist' );
	   	}
		if ( get_directorist_option( 'checkout_page' ) === $post->ID ) {
		   $post_states['directorist_checkout_page'] = __( 'Directorist Checkout', 'directorist' );
	   	}
		if ( get_directorist_option( 'payment_receipt_page' ) === $post->ID ) {
		   $post_states['directorist_payment_receipt_page'] = __( 'Directorist Payment Receipt', 'directorist' );
	   	}
		if ( get_directorist_option( 'transaction_failure_page' ) === $post->ID ) {
		   $post_states['directorist_transaction_failure_page'] = __( 'Directorist Payment Failure', 'directorist' );
	   	}
		if ( get_directorist_option( 'privacy_policy' ) === $post->ID ) {
		   $post_states['directorist_privacy_policy'] = __( 'Directorist Privacy Policy', 'directorist' );
	   	}
		if ( get_directorist_option( 'terms_conditions' ) === $post->ID ) {
		   $post_states['directorist_terms_conditions'] = __( 'Directorist Terms & Conditions', 'directorist' );
	   	}
		if ( get_directorist_option( 'pricing_plans' ) === $post->ID ) {
		   $post_states['directorist_pricing_plans'] = __( 'Directorist Pricing Plan', 'directorist' );
	   	}
		if ( get_directorist_option( 'signin_signup_page' ) === $post->ID ) {
		   $post_states['directorist_signin_signup'] = __( 'Directorist Sign In', 'directorist' );
	   	}

	   return $post_states;
   }

	public function add_uninstall_menu($menus) {
		$menus['uninstall_menu'] = array(
			'title' => __('Uninstall', 'directorist'),
			'name' => 'uninstall_menu',
			'icon' => 'font-awesome:fa-window-close',
			'controls' => apply_filters('atbdp_uninstall_settings_controls', array(
				'currency_section' => array(
					'type' => 'section',
					'title' => __('Uninstall Settings', 'directorist'),
					'fields' => get_uninstall_settings_submenus(),
				),
			)),
		);
		$menus['csv_import'] = array(
			'title' => __('Listings Import', 'directorist'),
			'name' => 'csv_import',
			'icon' => 'font-awesome:fa-upload',
			'controls' => apply_filters('atbdp_csv_import_settings_controls', array(
				'currency_section' => array(
					'type' => 'section',
					'title' => __('Listings Import', 'directorist'),
					'fields' => get_csv_import_settings_submenus(),
				),
			)),
		);
		return $menus;
	}

	public function show_popular_listing() {
		_deprecated_function( 'ATBDP()->show_popular_listing', '7.2.0' );
		return;
	}

	public function show_static_rating($post) {
		if ( ! directorist_is_review_enabled() ) {
			return;
		}

		if ( empty( $post ) || ! ( $post instanceof \WP_Post ) || $post->post_type !== ATBDP_POST_TYPE ) {
			return;
		}

		$average = directorist_get_listing_rating( $post->ID );
		?>
		<div class="atbd_rated_stars">
			<?php echo wp_kses_post( ATBDP()->review->print_static_rating( $average ) ); ?>
		</div>
		<?php
	}

	/**
	 * Unused method
	 *
	 * @return object WP_Query
	 */
	public function get_related_listings($post) {
		_deprecated_function( __METHOD__, '7.4.3' );
		return new WP_Query();
	}

	public function get_related_listings_widget( $post, $count ) {
		_deprecated_function( __METHOD__, '7.3.1' );
	}

	/**
	 * Unused method
	 *
	 * @return object WP_Query
	 */
	public function add_custom_meta_keys_for_old_listings() {
		_deprecated_function( __METHOD__, '7.4.3' );
	}

	/**
	 * Deprecated: 7.8.0
	 *
	 * This function is deprecated since version 7.8.0. Please use parse_video() instead.
	 *
	 * @param string $url The URL to parse for videos.
	 * @return mixed The parsed video URL.
	 *
	 * @deprecated Use parse_video() for video parsing.
	 */
	public function atbdp_parse_videos( $url ) {
		_deprecated_function( __METHOD__, '7.8.0', 'Directorist\Helper::parse_video()' );

		return \Directorist\Helper::parse_video( $url );
	}

	public function atbdp_body_class($c_classes)
	{
		$c_classes[] = 'directorist-content-active';//class name goes here
		$c_classes[] = 'directorist-preload';//class name goes here

		return $c_classes;
	}

	/**
	 * Initialize appsero tracking.
	 *
	 * Removed custom plugins meta data field in 7.0.5.4
	 * since Appsero made this builtin.
	 *
	 * @see https://github.com/Appsero/client
	 *
	 * @return void
	 */
	public function init_appsero() {
		if ( ! class_exists( '\Directorist\Appsero\Client' ) ) {
			require_once ATBDP_INC_DIR . 'modules/appsero/src/Client.php';
		}

		$client = new \Directorist\Appsero\Client( 'd9f81baf-2b03-49b1-b899-b4ee71c1d1b1', 'Directorist', __FILE__ );
		$this->insights = $client->insights();

		// Active insights
		$client->set_textdomain( 'directorist' );
		$client->insights()->init();
	}

	public function init_blocks() {
		require_once ATBDP_DIR . 'blocks/init.php';
	}

} // ends Directorist_Base


/**
 * The main function for that returns Directorist_Base
 *
 * The main function responsible for returning the one true Directorist_Base
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @since 1.0
 * @return object|Directorist_Base The one true Directorist_Base Instance.
 */
function ATBDP()
{
	return Directorist_Base::instance();
}

ATBDP();
register_activation_hook(__FILE__, array('Directorist_Base', 'prepare_plugin'));