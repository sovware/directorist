<?php
/**
 * Installation class.
 *
 * @package     Directorist
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ATBDP_Installation' ) ) :

/**
 * Directorist installation class.
 */
class ATBDP_Installation {

	/**
	 * DB updates and callbacks that need to be run per version.
	 *
	 * @since 7.1.0
	 * @var array
	 */
	private static $db_updates = array(
		'7.1.0' => [
			'directorist_710_migrate_reviews_table_to_comments_table',
			'directorist_710_migrate_posts_table_to_comments_table',
			'directorist_710_review_rating_clear_transients',
			'directorist_710_update_db_version',
		],
		'7.10.0' => [
			'directorist_7100_clean_falsy_never_expire_meta',
			'directorist_7100_migrate_expired_meta_to_expired_status',
			// 'directorist_7100_clean_listing_status_expired_meta', // Use this in future version to cleanup old data.
			'directorist_7100_update_db_version',
		],
		'7.11.0' => [
			'directorist_711_merge_dashboard_login_registration_page',
			'directorist_711_update_db_version',
		],
		'7.12.3' => [
			'directorist_7123_remove_upload_files_cap',
			'directorist_7123_update_db_version',
		]
	);

	/**
	 * Background update class.
	 *
	 * @since 7.1.0
	 * @var object
	 */
	private static $background_updater;

	/**
	 *It installs the required features or options for the plugin to run properly.
		* @link https://codex.wordpress.org/Function_Reference/register_post_type
		* @return void
		*/
	public static function install() {
		require_once ATBDP_CLASS_DIR . 'class-custom-post.php'; // include custom post class
		require_once ATBDP_CLASS_DIR . 'class-roles.php'; // include custom roles and Caps

		$ATBDP_Custom_Post = new ATBDP_Custom_Post();
		$ATBDP_Custom_Post->register_new_post_types();

		flush_rewrite_rules(); // lets flash the rewrite rules as we have registered the custom post

		// Add custom ATBDP_Roles & Capabilities
		if ( ! get_option( 'atbdp_roles_mapped' ) ) {
			$roles = new ATBDP_Roles;
			$roles->add_caps();
		}

		// Insert atbdp_roles_mapped option to the db to prevent mapping meta cap
		add_option( 'atbdp_roles_mapped', true );

		$atbdp_option = get_option( 'atbdp_option' );
		$atpdp_setup_wizard = apply_filters( 'atbdp_setup_wizard', true );

		if( ! $atbdp_option && $atpdp_setup_wizard ) {
			update_option( 'directorist_merge_dashboard_login_reg_page', true );
			set_transient( '_directorist_setup_page_redirect', true, 30 );
		}

		self::maybe_update_db_version();
	}

	public static function init() {
		add_action( 'init', [ __CLASS__, 'init_background_updater' ], 5 );
		add_action( 'admin_init', [ __CLASS__, 'install_actions' ] );
	}

	/**
	 * Init background updates
	 *
	 * @since 7.1.0
	 */
	public static function init_background_updater() {
		include_once ATBDP_INC_DIR . 'classes/class-background-updater.php';
		self::$background_updater = new \Directorist\Background_Updater();
	}

	/**
	 * Install actions when a update button is clicked within the admin area.
	 *
	 * This function is hooked into admin_init to affect admin only.
	 *
	 * @since 7.1.0
	 */
	public static function install_actions() {
		if ( ! empty( $_GET['do_update_directorist'] ) ) { // WPCS: input var ok.
			check_admin_referer( 'directorist_db_update', 'directorist_db_update_nonce' );
			self::update();
		}

		if ( ! empty( $_GET['force_update_directorist'] ) ) { // WPCS: input var ok.
			check_admin_referer( 'directorist_force_db_update', 'directorist_force_db_update_nonce' );
			$blog_id = get_current_blog_id();

			// Used to fire an action added in WP_Background_Process::_construct() that calls WP_Background_Process::handle_cron_healthcheck().
			// This method will make sure the database updates are executed even if cron is disabled. Nothing will happen if the updates are already running.
			do_action( 'wp_' . $blog_id . '_directorist_updater_cron' );

			wp_safe_redirect( admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-settings' ) );
			exit;
		}
	}

	/**
	 * Get list of DB update callbacks.
	 *
	 * @since 7.1.0
	 * @return array
	 */
	public static function get_db_update_callbacks() {
		return self::$db_updates;
	}

	/**
	 * Push all needed DB updates to the queue for processing.
	 *
	 * @since 7.1.0
	 */
	private static function update() {
		$current_db_version = get_option( 'directorist_db_version' );
		$update_queued      = false;

		foreach ( self::get_db_update_callbacks() as $version => $update_callbacks ) {
			if ( version_compare( $current_db_version, $version, '<' ) ) {
				foreach ( $update_callbacks as $update_callback ) {
					self::$background_updater->push_to_queue( $update_callback );
					$update_queued = true;
				}
			}
		}

		if ( $update_queued ) {
			self::$background_updater->save()->dispatch();
		}
	}

	/**
	 * Update DB version to current.
	 *
	 * @since 7.1.0
	 * @param string|null $version New Directorist DB version or null.
	 */
	public static function update_db_version( $version = null ) {
		delete_option( 'directorist_db_version' );
		add_option( 'directorist_db_version', is_null( $version ) ? ATBDP_VERSION : $version );
	}

	/**
	 * See if we need to show or run database updates during install.
	 *
	 * @since 7.1.0
	 */
	private static function maybe_update_db_version() {
		// Probably new installation, so add current db version.
		if ( ! get_option( 'directorist_setup_wizard_completed' ) && ! get_option( 'directorist_db_version', null ) ) {
			self::update_db_version();
		}

		if ( self::needs_db_update() ) {
			if ( apply_filters( 'directorist/updater/enable_db_auto_update', false ) ) {
				self::init_background_updater();
				self::update();
			}
		} else {
			self::update_db_version();
		}
	}

	/**
	 * Is a DB update needed?
	 *
	 * @since 7.1.0
	 * @return boolean
	 */
	public static function needs_db_update() {
		$current_db_version = get_option( 'directorist_db_version', null );
		$updates            = self::get_db_update_callbacks();

		return ( is_null( $current_db_version ) || version_compare( $current_db_version, max( array_keys( $updates ) ), '<' ) );
	}

}

ATBDP_Installation::init();

endif;
