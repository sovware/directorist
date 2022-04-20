<?php
/**
 * Plugin Name: Directorist - Business Directory Plugin
 * Plugin URI: https://wpwax.com
 * Description: A comprehensive solution to create professional looking directory site of any kind. Like Yelp, Foursquare, etc.
 * Version: 7.2.1
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
	 * Enqueue_Assets Object.
	 *
	 * @var object|Enqueue_Assets
	 * @since 7.0
	 */
	public $enqueue_assets;

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
	 * ATBDP_Single_Templates Object.
	 *
	 * @var ATBDP_Single_Templates
	 * @since 5.0.5
	 */
	public $ATBDP_Single_Templates;

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
			self::$instance = new Directorist_Base;
			self::$instance->setup_constants();

			add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
			add_action('plugins_loaded', array(self::$instance, 'add_polylang_swicher_support') );
			add_action('widgets_init', array(self::$instance, 'register_widgets'));

			add_action( 'template_redirect', [ self::$instance, 'check_single_listing_page_restrictions' ] );
			add_action( 'atbdp_show_flush_messages', [ self::$instance, 'show_flush_messages' ] );

			self::$instance->includes();

			self::$instance->custom_post = new ATBDP_Custom_Post; // create custom post
			self::$instance->taxonomy = new ATBDP_Custom_Taxonomy;

			add_action('init', array( self::$instance, 'on_install_update_actions' ) );

			self::$instance->enqueue_assets = new Directorist\Enqueue_Assets;

			// ATBDP_Listing_Type_Manager
			self::$instance->multi_directory_manager = new Directorist\Multi_Directory_Manager;
			self::$instance->multi_directory_manager->run();

			self::$instance->settings_panel = new ATBDP_Settings_Panel;
			self::$instance->settings_panel->run();

			self::$instance->hooks = new ATBDP_Hooks;
			self::$instance->metabox = new ATBDP_Metabox;
			self::$instance->ajax_handler = new ATBDP_Ajax_Handler;
			self::$instance->helper = new ATBDP_Helper;
			self::$instance->listing = new ATBDP_Listing;
			self::$instance->user = new ATBDP_User;
			self::$instance->roles = new ATBDP_Roles;
			if( class_exists( 'ATBDP_Gateway' ) ) {
				self::$instance->gateway = new ATBDP_Gateway;
			}
			self::$instance->order = new ATBDP_Order;
			self::$instance->shortcode = new \Directorist\ATBDP_Shortcode;
			self::$instance->email = new ATBDP_Email;
			self::$instance->seo = new ATBDP_SEO;
			// self::$instance->validator = new ATBDP_Validator;
			// self::$instance->ATBDP_Single_Templates = new ATBDP_Single_Templates;
			self::$instance->tools = new ATBDP_Tools;
			self::$instance->announcement = new ATBDP_Announcement;

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
			new ATBDP_Rewrite;
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
			//fire up one time compatibility increasing function.
			if (get_option('atbdp_meta_version') < 1) {
				add_action('init', array(self::$instance, 'add_custom_meta_keys_for_old_listings'));
			}


			// init offline gateway
			new ATBDP_Offline_Gateway;
			// Init Cron jobs to run some periodic tasks
			new ATBDP_Cron;
			// add upgrade feature
			new ATBDP_Upgrade;
			// add uninstall menu
			add_filter('atbdp_settings_menus', array(self::$instance, 'add_uninstall_menu'));

			self::init_hooks();

			// Initialize appsero tracking
			self::$instance->init_appsero();

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
		$restricted_for_logged_in_user = get_directorist_option( 'restrict_single_listing_for_logged_in_user', false );
		$current_user_id = get_current_user_id();

		if ( is_singular( ATBDP_POST_TYPE ) && ! empty( $restricted_for_logged_in_user ) && empty( $current_user_id ) ) {

			atbdp_auth_guard();
			die;
		}
	}

	// add_polylang_swicher_support
	public function add_polylang_swicher_support() {
		add_filter('pll_the_language_link', function($url, $current_lang) {
			// Adjust the category link
			$category_url = $this->get_polylang_swicher_link_for_term([
				'term_type'            => 'category',
				'term_default_page_id' => get_directorist_option('single_category_page'),
				'term_query_var'       => ( ! empty( $_GET['category'] ) ) ? $_GET['category'] : get_query_var('atbdp_category'),
				'current_lang'         => $current_lang,
				'url'                  => $url,
			]);

			if ( ! empty( $category_url ) ) { return $category_url; }

			// Adjust the location link
			$location_url = $this->get_polylang_swicher_link_for_term([
				'term_type'            => 'location',
				'term_default_page_id' => get_directorist_option('single_location_page'),
				'term_query_var'       => ( ! empty( $_GET['location'] ) ) ? $_GET['location'] : get_query_var('atbdp_location'),
				'current_lang'         => $current_lang,
				'url'                  => $url,
			]);

			if ( ! empty( $location_url ) ) { return $location_url; }

			return $url;
		}, 10, 2);
	}

	// get_polylang_swicher_link_for_term
	public function get_polylang_swicher_link_for_term( $args ) {
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

	function autoload( $dir = '' ) {
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

		self::require_files([
			ATBDP_INC_DIR . 'class-helper',
			ATBDP_INC_DIR . 'helper-functions',
			ATBDP_INC_DIR . 'template-functions',
			ATBDP_INC_DIR . 'custom-actions',
			ATBDP_INC_DIR . 'custom-filters',
			ATBDP_INC_DIR . 'elementor/init',
			ATBDP_INC_DIR . 'system-status/class-system-status',
			ATBDP_INC_DIR . 'gutenberg/init',
			ATBDP_INC_DIR . 'review/init',
			ATBDP_INC_DIR . 'rest-api/init',
		]);

		load_dependencies('all', ATBDP_INC_DIR . 'data-store/');
		load_dependencies('all', ATBDP_INC_DIR . 'model/');
		load_dependencies('all', ATBDP_INC_DIR . 'hooks/');
		load_dependencies('all', ATBDP_INC_DIR . 'modules/');
		load_dependencies('all', ATBDP_INC_DIR . 'modules/multi-directory-setup/');

		load_dependencies('all', ATBDP_CLASS_DIR); // load all php files from ATBDP_CLASS_DIR

		/*Load gateway related stuff*/
		load_dependencies('all', ATBDP_INC_DIR . 'gateways/');
		/*Load payment related stuff*/
		load_dependencies('all', ATBDP_INC_DIR . 'payments/');
		load_dependencies('all', ATBDP_INC_DIR . 'checkout/');


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
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'directorist'), '1.0');
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
		_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'directorist'), '1.0');
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
				'before_widget' => '<div class="widget atbd_widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<div class="atbd_widget_title"><h4>',
				'after_title' => '</h4></div>',
			));
		}

		register_widget('BD_Popular_Listing_Widget');
		register_widget('BD_video_Widget');
		register_widget('BD_contact_form_Widget');
		register_widget('BD_Submit_Item_Widget');
		register_widget('BD_Login_Form_Widget');
		register_widget('BD_Categories_Widget');
		register_widget('BD_Locations_Widget');
		register_widget('BD_Tags_Widget');
		register_widget('BD_Search_Widget');
		register_widget('BD_Map_Widget');
		// register_widget('BD_All_Map_Widget');
		register_widget('BD_Similar_Listings_Widget');
		register_widget('BD_Author_Info_Widget');
		register_widget('BD_Featured_Listings_Widget');

	}

	public function load_textdomain()
	{

		load_plugin_textdomain('directorist', false, ATBDP_LANG_DIR);
		if ( get_transient( '_directorist_setup_page_redirect' ) ) {
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
	public function load_template($name, $args = array(), $return_path = false)
	{
		global $post;
		$path = ATBDP_VIEWS_DIR . $name . '.php';
		if ($return_path) return $path;
		include($path);
	}

	public function add_custom_directorist_pages()
	{
		$create_permission = apply_filters('atbdp_create_required_pages', true);
		if ($create_permission){
			atbdp_create_required_pages();
		}
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

	/**
	 * It displays popular listings
	 * @param int $count [optional] Number of popular listing to show. Default 5.
	 * If the count is more than one then it uses it, else the function will use the value from the settings page.
	 * Count variable is handy if we want to show different number of popular listings on different pages. For example, on different widgets place
	 * @todo Try to move popular listings related functionalities to a dedicated listing related class that handles popular listings, related listings etc. when have time.
	 */
	public function show_popular_listing($count = 5)
	{
		$popular_listings = $this->get_popular_listings($count);

		if ($popular_listings->have_posts()) { ?>
			<div class="atbd_categorized_listings">
				<ul class="listings">
					<?php foreach ($popular_listings->posts as $pop_post) {
						// get only one parent or high level term object
						$top_category = ATBDP()->taxonomy->get_one_high_level_term($pop_post->ID, ATBDP_CATEGORY);
						$listing_img = get_post_meta($pop_post->ID, '_listing_img', true);
						$listing_prv_img = get_post_meta($pop_post->ID, '_listing_prv_img', true);
						$cats = get_the_terms($pop_post->ID, ATBDP_CATEGORY);
						$post_link = get_the_permalink( $pop_post->ID );
						?>
						<li>
							<div class="atbd_left_img">
								<?php
								$disable_single_listing = get_directorist_option('disable_single_listing');
								if (empty($disable_single_listing)){
								?>
								<a href="<?php echo esc_url( $post_link ); ?>">
									<?php
									}
									$default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
									if (!empty($listing_prv_img)) {
										echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="' . esc_html($pop_post->post_title) . '">';
									} elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
										echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="' . esc_html($pop_post->post_title) . '">';
									} else {
										echo '<img src="' . $default_image . '" alt="' . esc_html($pop_post->post_title) . '">';
									}
									if (empty($disable_single_listing)) {
										echo '</a>';
									}
									?>
							</div>
							<div class="atbd_right_content">
								<div class="cate_title">
									<h4>
										<?php
										if (empty($disable_single_listing)) {
											?>
											<a href="<?php echo esc_url($post_link); ?>"><?php echo esc_html($pop_post->post_title); ?></a>
											<?php
										} else {
											echo esc_html($pop_post->post_title);
										} ?>
									</h4>
								</div>

								<?php if (!empty($cats)) {
									$totalTerm = count($cats);
									?>

									<p class="directory_tag">
										<span class="<?php atbdp_icon_type(true); ?>-tags"></span>
										<span>
												<a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]); ?>">
																	 <?php echo esc_html($cats[0]->name); ?>
												</a>
											<?php
											if ($totalTerm > 1) {
												?>
												<span class="atbd_cat_popup">  +<?php echo $totalTerm - 1; ?>
													<span class="atbd_cat_popup_wrapper">
																	<?php
																	$output = array();
																	foreach (array_slice($cats, 1) as $cat) {
																		$link = ATBDP_Permalink::atbdp_get_category_page($cat);
																		$space = str_repeat(' ', 1);
																		$output [] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
																	} ?>
														<span><?php echo join($output) ?></span>
																</span>
															</span>
											<?php } ?>

										</span>
									</p>
								<?php }
								ATBDP()->show_static_rating($pop_post);
								?>
							</div>
						</li>
					<?php } // ends the loop
					?>

				</ul>
			</div> <!--ends .categorized_listings-->
		<?php }

	}

	/**
	 * It gets the popular listings of the given listing/post
	 *
	 * @param int $count [optional] Number of popular listing to show.  If the count is more than one then it uses it,
	 *                   else the function will use the value from the settings page.
	 *                   Count variable is handy if we want to show different number of popular listings on different pages.
	 *                   For example, on different widgets place. Default 5.
	 * @return WP_Query It returns the popular listings if found.
	 */
	public function get_popular_listings( $count = 5, $listing_id = 0 ) {
		$count           = intval( $count > 0 ? $count : 5 );
		$view_to_popular = get_directorist_option( 'views_for_popular' );

		/**
		 * It filters the number of the popular listing to display
		 * @since 1.0.0
		 * @param int $count The number of popular listing  to show
		 */
		$count = apply_filters( 'atbdp_popular_listing_number', $count );

		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $count,
		);

		$has_featured               = (bool) get_directorist_option( 'enable_featured_listing' );
		$has_featured               = $has_featured || is_fee_manager_active();
		$listing_popular_by         = get_directorist_option( 'listing_popular_by' );
		$average_review_for_popular = (int) get_directorist_option( 'average_review_for_popular', 4 );
		$view_to_popular            = (int) get_directorist_option( 'views_for_popular' );

		$meta_queries = array();

		if ( $has_featured ) {
			if ( 'average_rating' === $listing_popular_by ) {
				$meta_queries['_rating'] = array(
					'key'     => directorist_get_rating_field_meta_key(),
					'value'   => $average_review_for_popular,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);
			} elseif ( 'view_count' === $listing_popular_by ) {
				$meta_queries['views'] = array(
					'key'     => directorist_get_listing_views_count_meta_key(),
					'value'   => $view_to_popular,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);

				$args['orderby'] = array(
					'_featured' => 'DESC',
					'views'     => 'DESC',
				);
			} else {
				$meta_queries['views'] = array(
					'key'     => directorist_get_listing_views_count_meta_key(),
					'value'   => $view_to_popular,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);

				$meta_queries['_rating'] = array(
					'key'     => directorist_get_rating_field_meta_key(),
					'value'   => $average_review_for_popular,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);

				$args['orderby'] = array(
					'_featured' => 'DESC',
					'views'     => 'DESC',
				);
			}
		} else {
			if ( 'average_rating' === $listing_popular_by ) {
				$meta_queries['_rating'] = array(
					'key'     => directorist_get_rating_field_meta_key(),
					'value'   => $average_review_for_popular,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);
			} elseif ( 'view_count' === $listing_popular_by ) {
				$meta_queries['views'] = array(
					'key'     => directorist_get_listing_views_count_meta_key(),
					'value'   => $view_to_popular,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);

				$args['orderby'] = array(
					'views' => 'DESC',
				);
			} else {
				$meta_queries['views'] = array(
					'key'     => directorist_get_listing_views_count_meta_key(),
					'value'   => $view_to_popular,
					'type'    => 'NUMERIC',
					'compare' => '>=',
				);

				$meta_queries['_rating'] = array(
					'key'     => directorist_get_rating_field_meta_key(),
					'value'   => $average_review_for_popular,
					'type'    => 'NUMERIC',
					'compare' => '<=',
				);

				$args['orderby'] = array(
					'views' => 'DESC',
				);
			}
		}

		if ( count( $meta_queries ) ) {
			$meta_queries['relation'] = 'AND';
			$args['meta_query'] = $meta_queries;
		}

		return new WP_Query( apply_filters( 'atbdp_popular_listing_args', $args ) );
	}

	/**
	 * It displays static rating of the given post
	 * @param object|WP_Post $post The current post object
	 */
	public function show_static_rating($post)
	{
		if ( ! directorist_is_review_enabled() ) {
			return;
		}

		if ( empty( $post ) || ! ( $post instanceof \WP_Post ) || $post->post_type !== ATBDP_POST_TYPE ) {
			return;
		}

		$average = directorist_get_listing_rating( $post->ID );
		?>
		<div class="atbd_rated_stars">
			<?php echo ATBDP()->review->print_static_rating($average); ?>
		</div>
		<?php
	}

	/**
	 * It gets the related listings of the given listing/post
	 * @param object|WP_Post $post The WP Post Object of whose related listing we would like to show
	 * @return object|WP_Query It returns the related listings if found.
	 */
	public function get_related_listings($post)
	{
		$rel_listing_num = get_directorist_option('rel_listing_num', 2);
		$atbd_cats = get_the_terms($post, ATBDP_CATEGORY);
		$atbd_tags = get_the_terms($post, ATBDP_TAGS);
		// get the tag ids of the listing post type
		$atbd_cats_ids = array();
		$atbd_tags_ids = array();

		if (!empty($atbd_cats)) {
			foreach ($atbd_cats as $atbd_cat) {
				$atbd_cats_ids[] = $atbd_cat->term_id;
			}
		}
		if (!empty($atbd_tags)) {
			foreach ($atbd_tags as $atbd_tag) {
				$atbd_tags_ids[] = $atbd_tag->term_id;
			}
		}
		$relationship = get_directorist_option('rel_listings_logic','OR');
		$args = array(
			'post_type' => ATBDP_POST_TYPE,
			'tax_query' => array(
				'relation' => $relationship,
				array(
					'taxonomy' => ATBDP_CATEGORY,
					'field' => 'term_id',
					'terms' => $atbd_cats_ids,
				),
				array(
					'taxonomy' => ATBDP_TAGS,
					'field' => 'term_id',
					'terms' => $atbd_tags_ids,
				),
			),
			'posts_per_page' => (int)$rel_listing_num,
			'post__not_in' => array($post->ID),
		);

		$meta_queries = array();
		$meta_queries[] = array(
			'relation' => 'OR',
			array(
				'key' => '_expiry_date',
				'value' => current_time('mysql'),
				'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
				'type' => 'DATETIME'
			),
			array(
				'key' => '_never_expire',
				'value' => 1,
			)
		);

		$meta_queries = apply_filters('atbdp_related_listings_meta_queries', $meta_queries);
		$count_meta_queries = count($meta_queries);
		if ($count_meta_queries) {
			$args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
		}

		//return new WP_Query(apply_filters('atbdp_related_listing_args', $args));

	}

	/**
	 * It gets the related listings widget of the given listing/post
	 * @param object|WP_Post $post The WP Post Object of whose related listing we would like to show
	 * @return object|WP_Query It returns the related listings if found.
	 */
	public function get_related_listings_widget($post, $count)
	{
		$directory_type = get_the_terms( get_the_ID(), ATBDP_TYPE );
		$type_id        = ! empty( $directory_type ) ? $directory_type[0]->term_id : '';
		$same_author    = get_directorist_type_option( $type_id, 'listing_from_same_author', false );
		$rel_listing_num = !empty($count) ? $count : 5;
		$atbd_cats = get_the_terms($post, ATBDP_CATEGORY);
		$atbd_tags = get_the_terms($post, ATBDP_TAGS);
		// get the tag ids of the listing post type
		$atbd_cats_ids = array();
		$atbd_tags_ids = array();

		if (!empty($atbd_cats)) {
			foreach ($atbd_cats as $atbd_cat) {
				$atbd_cats_ids[] = $atbd_cat->term_id;
			}
		}
		if (!empty($atbd_tags)) {
			foreach ($atbd_tags as $atbd_tag) {
				$atbd_tags_ids[] = $atbd_tag->term_id;
			}
		}
		$args = array(
			'post_type' => ATBDP_POST_TYPE,
			'tax_query' => array(
				'relation' => 'OR',
				array(
					'taxonomy' => ATBDP_CATEGORY,
					'field' => 'term_id',
					'terms' => $atbd_cats_ids,
				),
				array(
					'taxonomy' => ATBDP_TAGS,
					'field' => 'term_id',
					'terms' => $atbd_tags_ids,
				),
			),
			'posts_per_page' => (int)$rel_listing_num,
			'post__not_in' => array($post->ID),
		);
		if( ! empty( $same_author ) ){
			$args['author']  = get_post_field( 'post_author', get_the_ID() );
		}

		return new WP_Query(apply_filters('atbdp_related_listing_args', $args));

	}

	public function add_custom_meta_keys_for_old_listings()
	{
		// get all the listings that does not have any of the following meta key missing
		// loop through then and find which one does not contain a meta key
		// if they return false then add new meta keys to them
		$args = array(
			'post_type' => ATBDP_POST_TYPE,
			'post_status' => 'any',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => '_featured',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key' => '_expiry_date',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key' => '_never_expire',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key' => '_listing_status',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key' => '_price',
					'compare' => 'NOT EXISTS',
				),
			)

		);
		$listings = new WP_Query($args);

		foreach ($listings->posts as $l) {
			$ft = get_post_meta($l->ID, '_featured', true);
			$ep = get_post_meta($l->ID, '_expiry_date', true);
			$np = get_post_meta($l->ID, '_never_expire', true);
			$ls = get_post_meta($l->ID, '_listing_status', true);
			$pr = get_post_meta($l->ID, '_price', true);
			$exp_d = calc_listing_expiry_date();
			if (empty($ft)) {
				update_post_meta($l->ID, '_featured', 0);
			}
			if (empty($ep)) {
				update_post_meta($l->ID, '_expiry_date', $exp_d);
			}
			if (empty($np)) {
				update_post_meta($l->ID, '_never_expire', 0);
			}
			if (empty($ls)) {
				update_post_meta($l->ID, '_listing_status', 'post_status');
			}
			if (empty($pr)) {
				update_post_meta($l->ID, '_price', 0);
			}
		}
		// update db version to avoid duplication
		update_option('atbdp_meta_version', 1);

	}

	/**
	 * Parse the video URL and determine it's valid embeddable URL for usage.
	 */
	public function atbdp_parse_videos($url)
	{
		$embeddable_url = '';
		// Check for YouTube
		$is_youtube = preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url);

		if ($is_youtube) {
			$pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
			preg_match($pattern, $url, $matches);
			if (count($matches) && strlen($matches[7]) == 11) {
				$embeddable_url = 'https://www.youtube.com/embed/' . $matches[7];
			}
		}

		// Check for Vimeo
		$is_vimeo = preg_match('/vimeo\.com/i', $url);

		if ($is_vimeo) {
			$pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
			preg_match($pattern, $url, $matches);
			if (count($matches)) {
				$embeddable_url = 'https://player.vimeo.com/video/' . $matches[2];
			}
		}

		// Return
		return $embeddable_url;

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
		if ( ! class_exists( '\Appsero\Client' ) ) {
			require_once ATBDP_INC_DIR . 'modules/appsero/src/Client.php';
		}

		$client = new \Appsero\Client( 'd9f81baf-2b03-49b1-b899-b4ee71c1d1b1', 'Directorist – Business Directory & Classified Listings WordPress Plugin', __FILE__ );

		// Active insights
		$client->insights()->init();
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