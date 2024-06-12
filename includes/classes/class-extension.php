<?php
/**
 * ATBDP Extensions class
 *
 * This class is for interacting with Extensions eg. showing extensions lists
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Extensions
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access is not allowed.' );
}

if ( ! is_admin() ) {
	return;
}

use Directorist\Core\API;

if ( ! class_exists( 'ATBDP_Extensions' ) ) {

	/**
	 * Class ATBDP_Extensions
	 */
	class ATBDP_Extensions {
		public static $extensions_aliases = array();

		public $extensions          = array();
		public $themes              = array();
		public $required_extensions = array();

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 100 );
			add_action( 'admin_init', array( $this, 'setup_ajax_actions' ) );

			if ( ! empty( $_GET['page'] ) && ( 'atbdp-extension' === $_GET['page'] ) ) {
				add_action( 'admin_init', array( $this, 'initial_setup' ) );
			}
		}

		public function setup_ajax_actions() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Ajax
			add_action( 'wp_ajax_atbdp_authenticate_the_customer', array( $this, 'authenticate_the_customer' ) );
			add_action( 'wp_ajax_atbdp_download_file', array( $this, 'handle_file_download_request' ) );
			add_action( 'wp_ajax_atbdp_install_file_from_subscriptions', array( $this, 'handle_file_install_request_from_subscriptions' ) );
			add_action( 'wp_ajax_atbdp_plugins_bulk_action', array( $this, 'plugins_bulk_action' ) );
			add_action( 'wp_ajax_atbdp_activate_theme', array( $this, 'activate_theme' ) );
			add_action( 'wp_ajax_atbdp_activate_plugin', array( $this, 'activate_plugin' ) );
			add_action( 'wp_ajax_atbdp_update_plugins', array( $this, 'handle_plugins_update_request' ) );
			add_action( 'wp_ajax_atbdp_update_theme', array( $this, 'handle_theme_update_request' ) );
			add_action( 'wp_ajax_atbdp_refresh_purchase_status', array( $this, 'handle_refresh_purchase_status_request' ) );
			add_action( 'wp_ajax_atbdp_close_subscriptions_sassion', array( $this, 'handle_close_subscriptions_sassion_request' ) );

			// add_action( 'wp_ajax_atbdp_download_purchased_items', array($this, 'download_purchased_items') );
		}

		// initial_setup
		public function initial_setup() {
			$this->setup_extensions_alias();

			wp_update_plugins();

			// Apply hook to required extensions
			$this->required_extensions = apply_filters( 'directorist_required_extensions', array() );

			$this->setup_products_list();
		}

		// setup_extensions_alias
		public function setup_extensions_alias() {

			// Latest Key     => Deprecated key
			// Deprecated key => Latest Key
			self::$extensions_aliases = apply_filters(
				'directorist_extensions_aliases',
				array(
					'directorist-listings-with-map'        => 'directorist-listings-map',
					'directorist-listings-map'             => 'directorist-listings-with-map',

					'directorist-adverts-manager'          => 'directorist-ads-manager',
					'directorist-ads-manager'              => 'directorist-adverts-manager',

					'directorist-gallery'                  => 'directorist-image-gallery',
					'directorist-image-gallery'            => 'directorist-gallery',

					'directorist-slider-carousel'          => 'directorist-listings-slider-carousel',
					'directorist-listings-slider-carousel' => 'directorist-slider-carousel',

					'directorist-faqs'                     => 'directorist-listing-faqs',
					'directorist-listing-faqs'             => 'directorist-faqs',
				)
			);
		}

		// get_required_extension_list
		public function get_required_extension_list() {
			$required_extensions = array();

			foreach ( $this->required_extensions as $recommandation ) {

				if ( ! isset( $recommandation['extensions'] ) ) {
					continue;
				}

				if ( ! is_array( $recommandation['extensions'] ) ) {
					continue;
				}

				foreach ( $recommandation['extensions'] as $extension ) {
					$extension_alias = $this->get_extension_alias_key( $extension );

					if ( ! ( isset( $this->extensions[ $extension ] ) || isset( $this->extensions[ $extension_alias ] ) ) ) {
						continue;
					}

					if ( empty( $required_extensions[ $extension ] ) ) {
						$required_extensions[ $extension ] = array();
					}

					$required_extensions[ $extension ][] = $recommandation['ref'];
				}
			}

			return $required_extensions;
		}

		// prepare_the_final_requred_extension_list
		public function prepare_the_final_requred_extension_list( array $args = array() ) {
			$recommandation = array();

			$required_extensions_list = $this->get_required_extension_list();
			$purchased_extension_list = self::get_purchased_extension_list();
			$purchased_extensions     = ( ! empty( $purchased_extension_list ) && is_array( $purchased_extension_list ) ) ? array_keys( $purchased_extension_list ) : array();
			$plugin_dir_path          = trailingslashit( dirname( ATBDP_DIR ) );

			foreach ( $required_extensions_list as $extension => $recommanded_by ) {
				$extension_alias = $this->get_extension_alias_key( $extension );

				if ( $this->has_match_in_active_plugins( array( $extension, $extension_alias ) ) ) {
					continue;
				}

				$is_purchased       = ( in_array( $extension, $purchased_extensions ) ) ? true : false;
				$is_purchased_alias = ( in_array( $extension_alias, $purchased_extensions ) ) ? true : false;

				$is_installed = file_exists( $plugin_dir_path . $extension );
				$is_installed_alias = ( ! empty( $extension_alias ) && file_exists( $plugin_dir_path . $extension_alias ) ) ? true : false;

				$base = "{$extension}/{$extension}.php";

				if ( ! empty( $this->extensions[ $extension ] ) && ! empty( $this->extensions[ $extension ]['base'] ) ) {
					$base = $this->extensions[ $extension ]['base'];
				}

				if ( ! empty( $this->extensions[ $extension_alias ] ) && ! empty( $this->extensions[ $extension_alias ]['base'] ) ) {
					$base = $this->extensions[ $extension_alias ]['base'];
				}

				$recommandation[ $extension ]              = array();
				$recommandation[ $extension ]['ref']       = $recommanded_by;
				$recommandation[ $extension ]['base']      = $base;
				$recommandation[ $extension ]['purchased'] = ( $is_purchased || $is_purchased_alias ) ? true : false;
				$recommandation[ $extension ]['installed'] = ( $is_installed || $is_installed_alias ) ? true : false;
			}

			return $recommandation;
		}

		public function has_match_in_active_plugins( $plugin_name = '' ) {
			$match_found = false;

			$active_plugins = get_option( 'active_plugins', array() );

			if ( empty( $plugin_name ) ) {
				return false;
			}

			if ( empty( $active_plugins ) ) {
				return false;
			}

			if ( ! is_array( $active_plugins ) ) {
				return false;
			}

			foreach ( $active_plugins as $plugin_path ) {
				if ( empty( $plugin_name ) && ( false !== strpos( $plugin_path, $plugin_name ) ) ) {
					return true;
				}

				if ( is_array( $plugin_name ) ) {
					foreach ( $plugin_name as $plugin_key ) {
						if ( is_string( $plugin_key ) && ! empty( $plugin_key ) && false !== strpos( $plugin_path, $plugin_key ) ) {
							return true;
						}
					}
				}
			}

			return $match_found;
		}

		// get_the_products_list
		public function setup_products_list() {
			$products = API::get_products();

			$this->extensions = apply_filters( 'atbdp_extension_list', $products['extensions'] );
			$this->themes = apply_filters( 'atbdp_theme_list', $products['themes'] );
		}

		// exclude_purchased_extensions
		public function exclude_purchased_extensions( $extensions ) {
			$has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
			$is_logged_in              = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;

			if ( ! $is_logged_in ) {
				return $extensions;
			}

			$purchased_products = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );

			if ( empty( $purchased_products ) ) {
				return $extensions;
			}

			$purchased_extensions = ( ! empty( $purchased_products['plugins'] ) ) ? $purchased_products['plugins'] : '';

			if ( empty( $purchased_extensions ) ) {
				return $extensions;
			}

			$purchased_extensions_keys = ( is_array( $purchased_extensions ) ) ? array_keys( $purchased_extensions ) : array();
			$excluded_extensions       = $extensions;

			foreach ( $excluded_extensions as $extension_key => $extension ) {

				if ( ! in_array( $extension_key, $purchased_extensions_keys ) ) {
					continue;
				}

				$excluded_extensions[ $extension_key ]['active'] = false;
			}

			return $excluded_extensions;
		}

		// exclude_purchased_themes
		public function exclude_purchased_themes( $themes ) {
			$has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
			$is_logged_in              = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;

			if ( ! $is_logged_in ) {
				return $themes;
			}

			$purchased_products = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );

			if ( empty( $purchased_products ) ) {
				return $themes;
			}

			$purchased_themes = ( ! empty( $purchased_products['themes'] ) ) ? $purchased_products['themes'] : '';

			if ( empty( $purchased_themes ) ) {
				return $themes;
			}

			$purchased_themes_keys = is_array( $purchased_themes ) ? array_keys( $purchased_themes ) : array();
			$excluded_themes       = $themes;

			foreach ( $excluded_themes as $theme_key => $theme ) {

				if ( ! in_array( $theme_key, $purchased_themes_keys ) ) {
					continue;
				}

				$excluded_themes[ $theme_key ]['active'] = false;
			}

			return $excluded_themes;
		}

		// get_active_extensions
		public function get_active_extensions() {
			$active_extensions = array();

			foreach ( $this->extensions as $extension_slug => $extension ) {
				if ( empty( $extension['active'] ) ) {
					continue;
				}

				$active_extensions[ $extension_slug ] = $extension;
			}

			return $active_extensions;
		}

		// get_active_themes
		public function get_active_themes() {
			$active_themes = array();

			foreach ( $this->themes as $theme_slug => $theme ) {
				if ( empty( $theme['active'] ) ) {
					continue;
				}

				$active_themes[ $theme_slug ] = $theme;
			}

			return $active_themes;
		}

		// handle_plugins_update_request
		public function handle_plugins_update_request() {

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status            = array();
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			$plugin_key = ( isset( $_POST['plugin_key'] ) ) ? directorist_clean( wp_unslash( $_POST['plugin_key'] ) ) : '';
			$status     = $this->update_plugins( array( 'plugin_key' => $plugin_key ) );

			wp_send_json( $status );
		}

		// update_plugins
		public function update_plugins( array $args = array() ) {
			$default = array( 'plugin_key' => '' );
			$args    = array_merge( $default, $args );

			$status     = array( 'success' => true );
			$plugin_key = $args['plugin_key'];

			$plugin_updates       = get_site_transient( 'update_plugins' );
			$outdated_plugins     = $plugin_updates->response;
			$outdated_plugins_key = ( is_array( $outdated_plugins ) ) ? array_keys( $outdated_plugins ) : array();

			if ( empty( $outdated_plugins_key ) ) {
				$status['message'] = __( 'All plugins are up to date', 'directorist' );

				return array( 'status' => $status );
			}

			if ( ! empty( $plugin_key ) && ! in_array( $plugin_key, $outdated_plugins_key ) ) {
				$status['message'] = __( 'The plugin is up to date', 'directorist' );

				return array( 'status' => $status );
			}

			$plugins_available_in_subscriptions = self::get_purchased_extension_list();

			// Update single
			if ( ! empty( $plugin_key ) ) {
				$plugin_key  = self::filter_plugin_key_from_base_name( $plugin_key );
				$plugin_item = self::extract_plugin_from_list( $plugin_key, $plugins_available_in_subscriptions );
				$url         = self::get_file_download_link( $plugin_item, 'plugin' );

				$download_status = $this->download_plugin( array( 'url' => $url ) );

				if ( ! $download_status['success'] ) {
					$status['success'] = false;
					$status['message'] = __( 'The plugin could not update', 'directorist' );
					$status['log']     = $download_status['message'];
				} else {
					$status['success'] = true;
					$status['message'] = __( 'The plugin has been updated successfully', 'directorist' );
					$status['log']     = $download_status['message'];
				}

				return array( 'status' => $status );
			}

			// Update all
			$updated_plugins       = array();
			$update_failed_plugins = array();

			foreach ( $outdated_plugins as $plugin_base => $plugin ) {
				$plugin_key  = self::filter_plugin_key_from_base_name( $plugin_key );
				$plugin_item = self::extract_plugin_from_list( $plugin_key, $plugins_available_in_subscriptions );
				$url         = self::get_file_download_link( $plugin_item, 'plugin' );

				$download_status = $this->download_plugin( array( 'url' => $url ) );

				if ( ! $download_status['success'] ) {
					$update_failed_plugins[ $plugin_base ] = $plugin;
				} else {
					$updated_plugins[ $plugin_base ] = $plugin;
				}
			}

			$status['updated_plugins']       = $updated_plugins;
			$status['update_failed_plugins'] = $update_failed_plugins;

			if ( ! empty( $updated_plugins ) && ! empty( $update_failed_plugins ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Some of the plugin could not update', 'directorist' );
			}

			if ( empty( $update_failed_plugins ) ) {
				$status['success'] = true;
				$status['message'] = __( 'All the plugins are updated successfully', 'directorist' );
			}

			if ( empty( $updated_plugins ) ) {
				$status['success'] = true;
				$status['message'] = __( 'No plugins could not update', 'directorist' );
			}

			return array( 'status' => $status );
		}

		// extract_plugin_from_list
		public static function extract_plugin_from_list( $plugin_key = '', $list = array() ) {

			$plugin_item = array();
			$plugin_key  = ( is_string( $plugin_key ) ) ? $plugin_key : '';
			$list        = ( is_array( $list ) ) ? $list : array();

			$keys_in_list = array_keys( $list );

			if ( in_array( $plugin_key, $keys_in_list ) ) {
				$plugin_item = $list[ $plugin_key ];
			}

			$plugin_alias_key = self::get_extension_alias_key( $plugin_key );

			if ( in_array( $plugin_alias_key, $keys_in_list ) ) {
				$plugin_item = $list[ $plugin_alias_key ];
			}

			return $plugin_item;
		}

		// filter_plugin_key_from_base_name
		public static function filter_plugin_key_from_base_name( $plugin_key = '' ) {

			if ( ! is_string( $plugin_key ) ) {
				return '';
			}

			$plugin_key = preg_replace( '/\/.+/', '', $plugin_key );

			return $plugin_key;
		}

		// get_extension_alias_key
		public static function get_extension_alias_key( string $plugin_key = '' ) {
			$extensions_aliases      = self::$extensions_aliases;
			$extensions_aliases_keys = ( is_array( $extensions_aliases ) && ! empty( $extensions_aliases ) ) ? array_keys( $extensions_aliases ) : array();
			$plugin_alias_key        = in_array( $plugin_key, $extensions_aliases_keys ) ? $extensions_aliases[ $plugin_key ] : '';

			return $plugin_alias_key;
		}

		// plugins_bulk_action
		public function plugins_bulk_action() {
			$status = array( 'success' => true );

			if ( ! directorist_verify_nonce() ) {
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			$task         = ( isset( $_POST['task'] ) ) ? directorist_clean( wp_unslash( $_POST['task'] ) ) : '';
			$plugin_items = ( isset( $_POST['plugin_items'] ) ) ? directorist_clean( wp_unslash( $_POST['plugin_items'] ) ) : '';

			// Validation
			if ( empty( $task ) ) {
				$status['success'] = false;
				$status['message'] = 'No task found';
				wp_send_json( array( 'status' => $status ) );
			}

			if ( empty( $plugin_items ) ) {
				$status['success'] = false;
				$status['message'] = 'No plugin items found';
				wp_send_json( array( 'status' => $status ) );
			}

			// Activate
			if ( 'activate' === $task ) {
				foreach ( $plugin_items as $plugin ) {
					$activated = activate_plugin( $plugin );
					if( is_wp_error( $activated ) ) {
						$status['success'] = false;
						$status['message'] = 'Error in plugin activation';
						wp_send_json( array( 'status' => $status ) );
					}
				}
			}

			// Deactivate
			if ( 'deactivate' === $task ) {
				deactivate_plugins( $plugin_items );
			}

			// Uninstall
			if ( 'uninstall' === $task ) {
				delete_plugins( $plugin_items );
			}

			wp_send_json( array( 'status' => $status ) );
		}

		// activate_theme
		public function activate_theme() {
			$status           = array( 'success' => true );
			$theme_stylesheet = ( isset( $_POST['theme_stylesheet'] ) ) ? directorist_clean( wp_unslash( $_POST['theme_stylesheet'] ) ) : '';

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			if ( empty( $theme_stylesheet ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Theme\'s stylesheet is missing', 'directorist' );

				wp_send_json( array( 'status' => $status ) );
			}

			switch_theme( $theme_stylesheet );
			wp_send_json( array( 'status' => $status ) );
		}

		// activate_plugin
		public function activate_plugin() {
			$status     = array( 'success' => true );
			$plugin_key = ( isset( $_POST['item_key'] ) ) ? directorist_clean( wp_unslash( $_POST['item_key'] ) ) : '';

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			if ( empty( $plugin_key ) ) {
				$status['success'] = false;
				$status['log']     = array( '$plugin_key' => $plugin_key );
				$status['message'] = __( 'Please specefy which plugin to activate', 'directorist' );

				wp_send_json( array( 'status' => $status ) );
			}

			activate_plugin( $plugin_key );
			wp_send_json( array( 'status' => $status ) );
		}

		// handle_theme_update_request
		public function handle_theme_update_request() {

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status            = array();
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			$theme_stylesheet = ( isset( $_POST['theme_stylesheet'] ) ) ? directorist_clean( wp_unslash( $_POST['theme_stylesheet'] ) ) : '';

			$update_theme_status = $this->update_the_themes( array( 'theme_stylesheet' => $theme_stylesheet ) );
			wp_send_json( $update_theme_status );
		}

		// update_the_theme
		public function update_the_themes( array $args = array() ) {
			$default = array( 'theme_stylesheet' => '' );
			$args    = array_merge( $default, $args );

			$status = array( 'success' => true );

			$theme_stylesheet    = $args['theme_stylesheet'];
			$theme_updates       = get_site_transient( 'update_themes' );
			$outdated_themes     = $theme_updates->response;
			$outdated_themes_key = ( is_array( $outdated_themes ) ) ? array_keys( $outdated_themes ) : array();

			if ( empty( $outdated_themes_key ) ) {
				$status['message'] = __( 'All themes are up to date', 'directorist' );

				return array( 'status' => $status );
			}

			if ( ! empty( $theme_stylesheet ) && ! in_array( $theme_stylesheet, $outdated_themes_key ) ) {
				$status['message'] = __( 'The theme is up to date', 'directorist' );

				return array( 'status' => $status );
			}

			$themes_available_in_subscriptions      = self::get_purchased_theme_list();
			$themes_available_in_subscriptions_keys = ( is_array( $themes_available_in_subscriptions ) ) ? array_keys( $themes_available_in_subscriptions ) : array();

			// Check if stylesheet is present
			if ( ! empty( $theme_stylesheet ) ) {

				// Check if the the update is available
				if ( ! in_array( $theme_stylesheet, $outdated_themes_key ) ) {
					$status['success'] = false;
					$status['message'] = __( 'The theme is already upto date', 'directorist' );

					return array( 'status' => $status );
				}

				$theme_item = $themes_available_in_subscriptions[ $theme_stylesheet ];
				$url        = self::get_file_download_link( $theme_item, 'theme' );
				$url        = ( empty( $url ) && ! empty( $outdated_themes[ $theme_stylesheet ]['package'] ) ) ? $outdated_themes[ $theme_stylesheet ]['package'] : $url;

				$download_status = $this->download_theme( array( 'url' => $url ) );

				if ( ! $download_status['success'] ) {
					$status['success'] = false;
					$status['message'] = __( 'The theme could not update', 'directorist' );
					$status['log']     = $download_status['message'];
				} else {
					$status['success'] = true;
					$status['message'] = __( 'The theme has been updated successfully', 'directorist' );
					$status['log']     = $download_status['message'];
					wp_clean_themes_cache();
				};

				return array( 'status' => $status );
			}

			// Update all
			$updated_themes       = array();
			$update_failed_themes = array();

			foreach ( $outdated_themes as $theme_key => $theme ) {
				$url = '';

				if ( ! in_array( $theme_key, $themes_available_in_subscriptions_keys ) ) {
					continue;
				}

				$theme_item = $themes_available_in_subscriptions[ $theme_key ];
				$url        = self::get_file_download_link( $theme_item, 'theme' );

				$download_status = $this->download_theme( array( 'url' => $url ) );

				if ( ! $download_status['success'] ) {
					$update_failed_themes[ $theme_key ] = $theme;
				} else {
					$updated_themes[ $theme_key ] = $theme;
				}
			}

			$status['updated_themes']       = $updated_themes;
			$status['update_failed_themes'] = $update_failed_themes;

			if ( ! empty( $updated_themes ) && ! empty( $update_failed_themes ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Some of the theme could not update', 'directorist' );
			}

			if ( empty( $update_failed_themes ) ) {
				$status['success'] = true;
				$status['message'] = __( 'All the themes are updated successfully', 'directorist' );
			}

			if ( empty( $updated_themes ) ) {
				$status['success'] = true;
				$status['message'] = __( 'No themes could not update', 'directorist' );
			}

			return array( 'status' => $status );
		}

		/**
		 * Authenticate users as directorist customer.
		 *
		 * @return void
		 */
		public function authenticate_the_customer() {

			$status = array(
				'success' => true,
				'log' => array(),
			);

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status['success']                 = false;
				$status['log']['invalid_request'] = array(
					'type'    => 'error',
					'message' => 'Invalid request',
				);
			}

			// Get form data
			$username = ( isset( $_POST['username'] ) ) ? sanitize_user( $_POST['username'] ) : ''; // @codingStandardsIgnoreLine.
			$password = ( isset( $_POST['password'] ) ) ? urlencode( $_POST['password'] ) : ''; // @codingStandardsIgnoreLine.

			// Validate username
			if ( empty( $username ) && ! empty( $password ) ) {
				$status['success']                 = false;
				$status['log']['username_missing'] = array(
					'type'    => 'error',
					'message' => 'Username is required',
				);
			}

			// Validate password
			if ( empty( $password ) && ! empty( $username ) ) {
				$status['success']                 = false;
				$status['log']['password_missing'] = array(
					'type'    => 'error',
					'message' => 'Password is required',
				);
			}

			// Validate username && password
			if ( empty( $password ) && empty( $username ) ) {
				$status['success']                 = false;
				$status['log']['password_missing'] = array(
					'type'    => 'error',
					'message' => 'Username and Password is required',
				);
			}

			if ( ! $status['success'] ) {
				wp_send_json( array( 'status' => $status ) );
			}

			// Get licencing data
			$response = self::remote_authenticate_user(
				array(
					'user' => $username,
					'password' => $password,
				)
			);

			// Validate response
			if ( ! $response['success'] ) {
				$status['success']      = false;
				$default_status_message = ( isset( $response['message'] ) ) ? $response['message'] : '';

				if ( isset( $response['log'] ) && isset( $response['log']['errors'] ) && is_array( $response['log']['errors'] ) ) {
					foreach ( $response['log']['errors'] as $error_key => $error_value ) {
						$status['log'][ $error_key ] = array(
							'type'    => 'error',
							'message' => ( is_array( $error_value ) ) ? $error_value[0] : $error_value,
						);
					}
				} else {
					$status['log']['unknown_error'] = array(
						'type'    => 'error',
						'message' => ( ! empty( $default_status_message ) ) ? $default_status_message : __( 'Something went wrong', 'directorist' ),
					);
				}

				wp_send_json(
					array(
						'status' => $status,
						'response_body' => $response,
					)
				);
			}

			$previous_username = get_user_meta( get_current_user_id(), '_atbdp_subscribed_username', true );

			// Enable Sassion
			update_user_meta( get_current_user_id(), '_atbdp_subscribed_username', $username );
			update_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );

			$plugins_available_in_subscriptions = self::get_purchased_extension_list();
			$themes_available_in_subscriptions  = self::get_purchased_theme_list();
			$has_previous_subscriptions         = ( ! empty( $plugins_available_in_subscriptions ) || ! empty( $themes_available_in_subscriptions ) ) ? true : false;

			if ( $previous_username === $username && $has_previous_subscriptions ) {
				// Enable Sassion
				update_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
				$this->refresh_purchase_status( $args = array( 'password' => $password ) );

				wp_send_json(
					array(
						'status' => $status,
						'has_previous_subscriptions' => true,
					)
				);
			}

			delete_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions' );
			delete_user_meta( get_current_user_id(), '_themes_available_in_subscriptions' );

			$license_data = $response['license_data'];

			// Update user meta
			if ( ! empty( $license_data['themes'] ) ) {
				$themes_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data['themes'] );
				update_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', $themes_available_in_subscriptions );
			}

			if ( ! empty( $license_data['plugins'] ) ) {
				$plugins_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data['plugins'] );
				update_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', $plugins_available_in_subscriptions );
			}

			$status['success']                 = true;
			$status['log']['login_successful'] = array(
				'type'    => 'success',
				'message' => 'Login is successful',
			);

			wp_send_json(
				array(
					'status' => $status,
					'license_data' => $license_data,
				)
			);
		}

		// handle_refresh_purchase_status_request
		public function handle_refresh_purchase_status_request() {
			$status   = array( 'success' => true );

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			$password = ( isset( $_POST['password'] ) ) ? $_POST['password'] : ''; // @codingStandardsIgnoreLine.

			$status = $this->refresh_purchase_status( array( 'password' => $password ) );

			wp_send_json( $status );
		}

		// refresh_purchase_status
		public function refresh_purchase_status( array $args = array() ) {
			$status  = array( 'success' => true );
			$default = array( 'password' => '' );
			$args    = array_merge( $default, $args );

			if ( empty( $args['password'] ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Password is required', 'directorist' );

				return array( 'status' => $status );
			}

			$username = get_user_meta( get_current_user_id(), '_atbdp_subscribed_username', true );
			$password = $args['password'];

			if ( empty( $username ) ) {
				$status['success'] = false;
				$status['reload']  = true;
				$status['message'] = __( 'Sassion is destroyed, please sign-in again', 'directorist' );

				delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

				return array( 'status' => $status );
			}

			// Get licencing data
			$authentication = self::remote_authenticate_user(
				array(
					'user' => $username,
					'password' => $password,
				)
			);

			// Validate response
			if ( ! $authentication['success'] ) {
				$status['success'] = false;
				$status['message'] = $authentication['message'];

				return array(
					'status' => $status,
					'response_body' => $authentication,
				);
			}

			$license_data = $authentication['license_data'];

			// Update user meta
			if ( ! empty( $license_data['themes'] ) ) {
				$themes_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data['themes'] );
				update_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', $themes_available_in_subscriptions );
			}

			if ( ! empty( $license_data['plugins'] ) ) {
				$plugins_available_in_subscriptions = $this->prepare_available_in_subscriptions( $license_data['plugins'] );
				update_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', $plugins_available_in_subscriptions );
			}

			$status['success'] = true;
			$status['message'] = __( 'Your purchase has been refreshed successfuly', 'directorist' );

			return array( 'status' => $status );
		}

		// handle_close_subscriptions_sassion_request
		public function handle_close_subscriptions_sassion_request() {

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status            = array();
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			$hard_logout_state = ( isset( $_POST['hard_logout'] ) ) ? boolval( $_POST['hard_logout'] ) : false;
			$status            = $this->close_subscriptions_sassion( array( 'hard_logout' => $hard_logout_state ) );

			wp_send_json( $status );
		}

		// close_subscriptions_sassion
		public function close_subscriptions_sassion( array $args = array() ) {
			$default = array( 'hard_logout' => false );
			$args    = array_merge( $default, $args );

			$status = array( 'success' => true );
			delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

			if ( $args['hard_logout'] ) {
				delete_user_meta( get_current_user_id(), '_atbdp_subscribed_username' );
				delete_user_meta( get_current_user_id(), '_themes_available_in_subscriptions' );
				delete_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions' );
			}

			return $status;
		}

		// prepare_available_in_subscriptions
		public function prepare_available_in_subscriptions( array $products = array() ) {
			$available_in_subscriptions = array();

			if ( empty( $products ) ) {
				return $available_in_subscriptions;
			}

			foreach ( $products as $product ) {
				$product_key                              = $this->get_product_key_from_permalink( $product['permalink'] );
				$available_in_subscriptions[ $product_key ] = $product;
			}

			return $available_in_subscriptions;
		}

		// get_product_key_from_permalink
		public function get_product_key_from_permalink( string $permalink = '' ) {
			$product_key = str_replace( 'http://directorist.com/product/', '', $permalink );
			$product_key = str_replace( 'https://directorist.com/product/', '', $product_key );
			$product_key = str_replace( '/', '', $product_key );

			return $product_key;
		}

		// handle_license_activation_request
		public function handle_license_activation_request() {
			$status       = array( 'success' => true );
			$license_item = ( isset( $_POST['license_item'] ) ) ? directorist_clean( wp_unslash( $_POST['license_item'] ) ) : '';
			$product_type = ( isset( $_POST['product_type'] ) ) ? directorist_clean( wp_unslash( $_POST['product_type'] ) ) : '';

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status            = array();
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			if ( empty( $license_item ) ) {
				$status['success'] = false;
				$status['message'] = 'License item is missing';

				wp_send_json( array( 'status' => $status ) );
			}

			if ( empty( $product_type ) ) {
				$status['success'] = false;
				$status['message'] = 'Product type is required';

				wp_send_json( array( 'status' => $status ) );
			}

			$activation_status = $this->activate_license( $license_item, $product_type );
			$status['success'] = $activation_status['success'];

			wp_send_json(
				array(
					'status' => $status,
					'activation_status' => $activation_status,
				)
			);
		}

		// activate_license
		public function activate_license( $license_item, $product_type = '' ) {
			$status            = array( 'success' => true );
			$activation_status = self::remote_activate_license( $license_item );

			if ( empty( $activation_status['success'] ) ) {
				$status['success'] = false;
			}

			$status['response'] = $activation_status['response'];
			$product_type       = self::filter_product_type( $product_type );

			if ( $status['success'] && ( 'plugin' === $product_type || 'theme' === $product_type ) ) {
				$user_purchased = get_user_meta( get_current_user_id(), '_atbdp_purchased_products', true );

				if ( empty( $user_purchased ) ) {
					$user_purchased = array();
				}

				if ( empty( $user_purchased[ $product_type ] ) ) {
					$user_purchased[ $product_type ] = array();
				}

				$purchased_items = $user_purchased[ $product_type ];

				// Append new product
				$product_key                   = $this->get_product_key_from_permalink( $license_item['permalink'] );
				$purchased_items[ $product_key ] = $license_item;

				$user_purchased[ $product_type ] = $purchased_items;
				update_user_meta( get_current_user_id(), '_atbdp_purchased_products', $user_purchased );

				$status['purchased_products'] = $user_purchased;
			}

			return $status;
		}

		// handle_file_install_request_from_subscriptions
		public function handle_file_install_request_from_subscriptions() {
			$item_key = ( isset( $_POST['item_key'] ) ) ? directorist_clean( wp_unslash( $_POST['item_key'] ) ) : '';
			$type     = ( isset( $_POST['type'] ) ) ? directorist_clean( wp_unslash( $_POST['type'] ) ) : '';

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status            = array();
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			$installation_status = $this->install_file_from_subscriptions(
				array(
					'item_key' => $item_key,
					'type' => $type,
				)
			);
			wp_send_json( $installation_status );
		}

		// install_file_from_subscriptions
		public function install_file_from_subscriptions( array $args = array() ) {
			$default = array(
				'item_key' => '',
				'type' => '',
			);
			$args    = array_merge( $default, $args );

			$item_key = $args['item_key'];
			$type     = $args['type'];

			$status = array( 'success' => true );

			if ( empty( $item_key ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Item key is missing', 'directorist' );

				return array( 'status' => $status );
			}

			if ( empty( $type ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Type not specified', 'directorist' );

				return array( 'status' => $status );
			}

			if ( 'plugin' !== $type && 'theme' !== $type ) {
				$status['success'] = false;
				$status['message'] = __( 'Invalid type', 'directorist' );

				return array( 'status' => $status );
			}

			if ( 'theme' === $type ) {
				$available_in_subscriptions = self::get_purchased_theme_list();
			}

			if ( 'plugin' === $type ) {
				$available_in_subscriptions = self::get_purchased_extension_list();
			}

			if ( empty( $available_in_subscriptions ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Nothing available in subscriptions', 'directorist' );

				return array( 'status' => $status );
			}

			if ( empty( $available_in_subscriptions[ $item_key ] ) ) {
				$status['success'] = false;
				$status['message'] = __( 'The item is not available in your subscriptions', 'directorist' );

				return array( 'status' => $status );
			}

			$installing_file = $available_in_subscriptions[ $item_key ];

			$activatation_status = $this->activate_license( $installing_file, $type );
			$status['log']       = $activatation_status;

			if ( ! $activatation_status['success'] ) {
				$status['success'] = false;
				$status['message'] = __( 'The license is not valid, please check you subscription.', 'directorist' );

				return array( 'status' => $status );
			}

			$link          = $installing_file['download_link'];
			$download_args = array( 'url' => $link );

			if ( 'plugin' === $type ) {
				$download_status = $this->download_plugin( $download_args );
			}

			if ( 'theme' === $type ) {
				$download_status = $this->download_theme( $download_args );
			}

			if ( ! $download_status['success'] ) {
				return $download_status;
			}

			$status['success'] = true;
			$status['message'] = __( 'Installed Successfully', 'directorist' );

			return array( 'status' => $status );
		}

		// handle_plugin_download_request
		public function handle_file_download_request() {
			$status        = array( 'success' => true );

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			$download_item = ( isset( $_POST['download_item'] ) ) ? directorist_clean( wp_unslash( $_POST['download_item'] ) ) : '';
			$type          = ( isset( $_POST['type'] ) ) ? directorist_clean( wp_unslash( $_POST['type'] ) ) : '';

			if ( empty( $download_item ) ) {
				$status['success'] = false;
				$status['message'] = 'Download item is missing';

				wp_send_json( array( 'status' => $status ) );
			}

			if ( empty( $type ) ) {
				$status['success'] = false;
				$status['message'] = 'Type not specified';

				wp_send_json( array( 'status' => $status ) );
			}

			if ( 'plugin' !== $type || 'theme' !== $type ) {
				$status['success'] = false;
				$status['message'] = 'Invalid type';

				wp_send_json( array( 'status' => $status ) );
			}

			$activate_license = $this->activate_license( $download_item, $type );

			if ( ! $activate_license['success'] ) {
				$status['success'] = false;
				$status['message'] = __( 'Activation failed', 'directorist' );
				$status['ref']     = $activate_license;

				wp_send_json( array( 'status' => $status ) );
			}

			if ( empty( $download_item['download_link'] ) ) {
				$status['success'] = false;
				$status['message'] = 'Download Link not found';

				wp_send_json( array( 'status' => $status ) );
			}

			if ( ! is_string( $download_item['download_link'] ) ) {
				$status['success'] = false;
				$status['message'] = 'Download Link not found';

				wp_send_json( array( 'status' => $status ) );
			}

			$link          = $download_item['download_link'];
			$download_args = array( 'url' => $link );

			if ( 'plugin' === $type ) {
				$download_status = $this->download_plugin( $download_args );
			}

			if ( 'theme' === $type ) {
				$download_status = $this->download_theme( $download_args );
			}

			if ( ! $download_status['success'] ) {
				return $download_status;
			}

			$status['success'] = true;
			$status['message'] = __( 'Donloaded', 'directorist' );

			wp_send_json( array( 'status' => $status ) );
		}

		// download_plugin
		public function download_plugin( array $args = array() ) {
			$status = array( 'success' => false );

			$default = array(
				'url' => '',
				'init_wp_filesystem' => true,
			);
			$args    = array_merge( $default, $args );

			if ( empty( $args['url'] ) || ! self::is_varified_host( $args['url'] ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Invalid download link', 'directorist' );

				return $status;
			}

			global $wp_filesystem;

			if ( $args['init_wp_filesystem'] ) {

				if ( ! function_exists( 'WP_Filesystem' ) ) {
					include ABSPATH . 'wp-admin/includes/file.php';
				}

				WP_Filesystem();
			}

			$plugin_path = WP_CONTENT_DIR . '/plugins';
			$temp_dest   = "{$plugin_path}/atbdp-temp-dir";
			$file_url    = $args['url'];
			$file_name   = basename( $file_url );
			$tmp_file    = download_url( $file_url );

			if ( ! is_string( $tmp_file ) ) {
				$status['success']  = false;
				$status['tmp_file'] = $tmp_file;
				$status['file_url'] = $file_url;
				$status['message']  = 'Could not download the file';

				return $status;
			}

			// Make Temp Dir
			if ( $wp_filesystem->exists( $temp_dest ) ) {
				$wp_filesystem->delete( $temp_dest, true );
			}

			$wp_filesystem->mkdir( $temp_dest );

			if ( ! file_exists( $temp_dest ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Could not create temp directory', 'directorist' );

				return $status;
			}

			// Sets file temp destination.
			$file_path = "{$temp_dest}/{$file_name}";

			set_error_handler(
				function ( $errno, $errstr, $errfile, $errline ) {
					// error was suppressed with the @-operator
					if ( 0 === error_reporting() ) {
						  return false;
					}

					throw new ErrorException( $errstr, 0, $errno, $errfile, $errline );
				}
			);

			// Copies the file to the final destination and deletes temporary file.
			try {
				copy( $tmp_file, $file_path );
			} catch ( Exception $e ) {
				$status['success'] = false;
				$status['message'] = $e->getMessage();

				return $status;
			}

			@unlink( $tmp_file );
			unzip_file( $file_path, $temp_dest );

			if ( "{$plugin_path}/" !== $file_path || $file_path !== $plugin_path ) {
				@unlink( $file_path );
			}

			$extracted_file_dir = glob( "{$temp_dest}/*", GLOB_ONLYDIR );

			foreach ( $extracted_file_dir as $dir_path ) {
				$dir_name  = basename( $dir_path );
				$dest_path = "{$plugin_path}/{$dir_name}";

				// Delete Previous Files if Exists
				if ( $wp_filesystem->exists( $dest_path ) ) {
					$wp_filesystem->delete( $dest_path, true );
				}
			}

			copy_dir( $temp_dest, $plugin_path );
			$wp_filesystem->delete( $temp_dest, true );

			$status['success'] = true;
			$status['message'] = __( 'The plugin has been downloaded successfully', 'directorist' );

			return $status;
		}

		// download_theme
		public function download_theme( array $args = array() ) {
			$status = array( 'success' => false );

			$default = array(
				'url' => '',
				'init_wp_filesystem' => true,
			);
			$args    = array_merge( $default, $args );

			if ( empty( $args['url'] ) || ! self::is_varified_host( $args['url'] ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Invalid download link', 'directorist' );

				return $status;
			}

			global $wp_filesystem;

			if ( $args['init_wp_filesystem'] ) {

				if ( ! function_exists( 'WP_Filesystem' ) ) {
					include ABSPATH . 'wp-admin/includes/file.php';
				}

				WP_Filesystem();
			}

			$theme_path = WP_CONTENT_DIR . '/themes';
			$temp_dest  = "{$theme_path}/atbdp-temp-dir";
			$file_url   = $args['url'];
			$file_name  = basename( $file_url );
			$tmp_file   = download_url( $file_url );

			if ( ! is_string( $tmp_file ) ) {
				$status['success']  = false;
				$status['tmp_file'] = $tmp_file;
				$status['file_url'] = $file_url;
				$status['message']  = 'Could not download the file';

				return $status;
			}

			// Make Temp Dir
			if ( $wp_filesystem->exists( $temp_dest ) ) {
				$wp_filesystem->delete( $temp_dest, true );
			}

			$wp_filesystem->mkdir( $temp_dest );

			if ( ! file_exists( $temp_dest ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Could not create temp directory', 'directorist' );

				return $status;
			}

			// Sets file temp destination.
			$file_path = "{$temp_dest}/{$file_name}";

			set_error_handler(
				function ( $errno, $errstr, $errfile, $errline ) {
					// error was suppressed with the @-operator
					if ( 0 === error_reporting() ) {
						  return false;
					}

					throw new ErrorException( $errstr, 0, $errno, $errfile, $errline );
				}
			);

			// Copies the file to the final destination and deletes temporary file.
			try {
				copy( $tmp_file, $file_path );
			} catch ( Exception $e ) {
				$status['success'] = false;
				$status['message'] = $e->getMessage();

				return $status;
			}

			@unlink( $tmp_file );
			unzip_file( $file_path, $temp_dest );

			if ( "{$theme_path}/" !== $file_path || $file_path !== $theme_path ) {
				@unlink( $file_path );
			}

			$extracted_file_dir = glob( "{$temp_dest}/*", GLOB_ONLYDIR );
			$dir_path           = $extracted_file_dir[0];

			$dir_name  = basename( $dir_path );
			$dest_path = "{$theme_path}/{$dir_name}";
			$zip_files = glob( "{$dir_path}/*.zip" );

			// If has child theme
			if ( ! empty( $zip_files ) ) {
				$new_temp_dest = "{$temp_dest}/_temp_dest";
				$this->install_themes_from_zip_files( $zip_files, $new_temp_dest, $wp_filesystem );

				copy_dir( $new_temp_dest, $theme_path );
				$wp_filesystem->delete( $temp_dest, true );

				$status['success'] = false;
				$status['message'] = __( 'The theme has been downloaded successfully', 'directorist' );
			}

			// Delete Previous Files If Exists
			if ( $wp_filesystem->exists( $dest_path ) ) {
				$wp_filesystem->delete( $dest_path, true );
			}

			copy_dir( $temp_dest, $theme_path );
			$wp_filesystem->delete( $temp_dest, true );

			$status['success'] = true;
			$status['message'] = __( 'The theme has been downloaded successfully', 'directorist' );

			return $status;
		}

		// install_theme_from_zip
		public function install_themes_from_zip_files( $zip_files, $temp_dest, $wp_filesystem ) {
			$theme_path = WP_CONTENT_DIR . '/themes';

			foreach ( $zip_files as $zip ) {
				$file     = basename( $zip );
				$dir_name = str_replace( '.zip', '', $file );

				if ( preg_match( '/[-]child[.]zip$/', $file ) ) {
					$temp_dest_path = "{$temp_dest}/{$dir_name}";
					$main_dest_path = "{$theme_path}/{$dir_name}";

					// Skip if has child
					if ( $wp_filesystem->exists( $main_dest_path ) ) {
						continue;
					}

					$wp_filesystem->mkdir( $temp_dest_path );
					unzip_file( $zip, $temp_dest_path );
					// @unlink( $zip );

					continue;
				}

				$main_dest_path = "{$theme_path}/{$dir_name}";

				if ( $wp_filesystem->exists( $main_dest_path ) ) {
					$wp_filesystem->delete( $main_dest_path, true );
				}

				unzip_file( $zip, $temp_dest );
				// @unlink( $zip );
			}

		}

		// get_customers_purchased
		public function get_customers_purchased( $license_data ) {
			// Activate the licenses
			$activation_url = 'https://directorist.com';

			// Activate the Extensions
			$purchased_extensions_meta    = array();
			$purchased_extensions         = array();
			$invalid_purchased_extensions = array();

			if ( ! empty( $license_data['plugins'] ) ) {

				foreach ( $license_data['plugins'] as $extension ) {
					$license              = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $extension['license'];
					$extension['license'] = $license;

					$activation_status = self::remote_activate_license( $extension, 'plugin' );

					if ( empty( $activation_status['success'] ) ) {
						$invalid_purchased_extensions[] = array(
							'extension' => $extension,
							'response' => $activation_status['response'],
						);
						continue;
					}

					$purchased_extensions[] = $extension;

					// Store the ref for db
					$link    = $extension['permalink'];
					$ext_key = str_replace( 'http://directorist.com/product/', '', $link );
					$ext_key = str_replace( 'https://directorist.com/product/', '', $ext_key );
					$ext_key = str_replace( '/', '', $ext_key );

					$purchased_extensions_meta[ $ext_key ] = array(
						'item_id' => $extension['item_id'],
						'license' => $extension['license'],
						'license' => $extension['license'],
						'file'    => $extension['links'],
					);
				}
			}

			// Activate the Themes
			$purchased_themes_meta    = array();
			$purchased_themes         = array();
			$invalid_purchased_themes = array();

			if ( ! empty( $license_data['themes'] ) ) {

				foreach ( $license_data['themes'] as $theme ) {
					$license          = ( ! empty( $response_body['all_access'] ) ) ? $response_body['active_licenses'][0] : $theme['license'];
					$theme['license'] = $license;

					$activation_status = self::remote_activate_license( $theme );

					if ( empty( $activation_status['success'] ) ) {
						$invalid_purchased_themes[] = $theme;
						$invalid_purchased_themes[] = array(
							'extension' => $theme,
							'response' => $activation_status['response'],
						);
						continue;
					}

					$purchased_themes[] = $theme;

					// Store the ref for db
					$link      = $theme['permalink'];
					$theme_key = str_replace( 'http://directorist.com/product/', '', $link );
					$theme_key = str_replace( 'https://directorist.com/product/', '', $theme_key );
					$theme_key = str_replace( '/', '', $theme_key );

					$purchased_themes_meta[ $theme_key ] = array(
						'item_id' => $extension['item_id'],
						'license' => $extension['license'],
						'file'    => $extension['links'],
					);
				}
			}

			$customers_purchased = array(
				'extensions' => $purchased_extensions_meta,
				'themes'     => $purchased_themes_meta,
			);

			update_user_meta( get_current_user_id(), '_atbdp_purchased_products', $customers_purchased );

			$status['purchased_extensions']         = $purchased_extensions;
			$status['invalid_purchased_extensions'] = $invalid_purchased_extensions;

			$status['purchased_themes']         = $purchased_themes;
			$status['invalid_purchased_themes'] = $invalid_purchased_themes;

			$status['customers_purchased'] = $customers_purchased;

			return $status;
		}

		// download_purchased_items
		public function download_purchased_items() {
			$status = array(
				'success' => true,
				'log' => array(),
			);

			if ( ! directorist_verify_nonce( 'nonce', 'atbdp_nonce_action_js' ) ) {
				$status['success'] = false;
				$status['message'] = 'Invalid request';

				wp_send_json( array( 'status' => $status ) );
			}

			$cart = ( isset( $_POST['customers_purchased'] ) ) ? directorist_clean( wp_unslash( $_POST['customers_purchased'] ) ) : '';

			if ( empty( $cart ) ) {
				$status['success']                        = false;
				$status['log']['no_purchased_data_found'] = array(
					'type'    => 'error',
					'message' => 'No purchased data found',
				);
				wp_send_json( array( 'status' => $status ) );
			}

			// Download the extensions
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				include ABSPATH . 'wp-admin/includes/file.php';
			}

			WP_Filesystem();

			// Download Extenstions
			if ( ! empty( $cart['purchased_extensions'] ) ) {
				foreach ( $cart['purchased_extensions'] as $extension ) {
					$download_link = $extension['download_link'];
					if ( empty( $download_link ) ) {
						continue;
					}

					$this->download_plugin(
						array(
							'url' => $download_link,
							'init_wp_filesystem' => false,
						)
					);
				}
			}

			// Download Themes
			if ( ! empty( $cart['purchased_themes'] ) ) {
				foreach ( $cart['purchased_themes'] as $theme ) {
					$download_link = $extension['download_link'];
					if ( empty( $download_link ) ) {
						continue;
					}

					$this->download_theme(
						array(
							'url' => $download_link,
							'init_wp_filesystem' => false,
						)
					);
				}
			}

			$status['message'] = 'Download has been completed, redirecting...';

			wp_send_json( array( 'status' => $status ) );
		}

		/**
		 * It Adds menu item
		 */
		public function admin_menu() {
			add_submenu_page(
				'edit.php?post_type=at_biz_dir',
				__( 'Get Extensions', 'directorist' ),
				__( 'Themes & Extensions', 'directorist' ),
				'manage_options',
				'atbdp-extension',
				array( $this, 'show_extension_view' )
			);
		}

		// get_extensions_overview
		public function get_extensions_overview() {
			// Get Extensions Details
			$plugin_updates       = get_site_transient( 'update_plugins' );
			$outdated_plugins     = $plugin_updates->response;
			$outdated_plugins_key = ( is_array( $outdated_plugins ) ) ? array_keys( $outdated_plugins ) : array();
			$official_extensions  = is_array( $this->extensions ) ? array_keys( $this->extensions ) : array();

			$all_installed_plugins_list = get_plugins();
			$installed_extensions       = array();
			$total_active_extensions    = 0;
			$total_outdated_extensions  = 0;

			foreach ( $all_installed_plugins_list as $plugin_base => $plugin_data ) {

				$folder_base = strtok( $plugin_base, '/' );

				if ( preg_match( '/^directorist-/', $plugin_base ) && in_array( $folder_base, $official_extensions ) ) {
					$installed_extensions[ $plugin_base ] = $plugin_data;

					if ( is_plugin_active( $plugin_base ) ) {
						$total_active_extensions++;
					}

					if ( in_array( $plugin_base, $outdated_plugins_key ) ) {
						$total_outdated_extensions++;
					}
				}
			}

			// ---
			$extensions_available_in_subscriptions = $this->get_extensions_available_in_subscriptions(
				array(
					'installed_extensions' => $installed_extensions,
				)
			);

			// ---
			$extensions_promo_list = $this->get_extensions_promo_list(
				array(
					'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
					'installed_extensions'                  => $installed_extensions,
				)
			);

			$required_extensions_list = $this->prepare_the_final_requred_extension_list(
				array(
					'installed_extension_list'              => $installed_extensions,
					'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
				)
			);

			$total_installed_ext_list             = count( $installed_extensions );
			$total_ext_available_in_subscriptions = count( $extensions_available_in_subscriptions );
			$total_available_extensions           = $total_installed_ext_list + $total_ext_available_in_subscriptions;

			$overview = array(
				'outdated_plugin_list'                  => is_array( $outdated_plugins ) ? $outdated_plugins : [],
				'outdated_plugins_key'                  => $outdated_plugins_key,
				'all_installed_plugins_list'            => $all_installed_plugins_list,
				'installed_extension_list'              => $installed_extensions,
				'total_active_extensions'               => $total_active_extensions,
				'total_outdated_extensions'             => $total_outdated_extensions,
				'extensions_promo_list'                 => $extensions_promo_list,
				'extensions_available_in_subscriptions' => $extensions_available_in_subscriptions,
				'total_available_extensions'            => $total_available_extensions,
				'required_extensions'                   => $required_extensions_list,
			);

			return $overview;
		}

		// get_extensions_available_in_subscriptions
		public function get_extensions_available_in_subscriptions( array $args = array() ) {
			$installed_extensions      = ( ! empty( $args['installed_extensions'] ) ) ? $args['installed_extensions'] : array();
			$installed_extensions_keys = $this->get_sanitized_extensions_keys( $installed_extensions );

			$extensions_available_in_subscriptions = self::get_purchased_extension_list();
			$extensions_available_in_subscriptions = ( is_array( $extensions_available_in_subscriptions ) ) ? $extensions_available_in_subscriptions : array();

			if ( ! empty( $extensions_available_in_subscriptions ) && is_array( $extensions_available_in_subscriptions ) ) {

				foreach ( $extensions_available_in_subscriptions as $base => $args ) {
					$base_alias       = $this->get_extension_alias_key( $base );
					$plugin_key       = preg_replace( '/(directorist-)/', '', $base );
					$plugin_alias_key = preg_replace( '/(directorist-)/', '', $base_alias );

					$is_in_installed_extensions       = in_array( $plugin_key, $installed_extensions_keys ) ? true : false;
					$is_in_installed_extensions_alias = in_array( $plugin_alias_key, $installed_extensions_keys ) ? true : false;

					if ( $is_in_installed_extensions || $is_in_installed_extensions_alias ) {
						unset( $extensions_available_in_subscriptions[ $base ] );
					}
				}
			}

			return $extensions_available_in_subscriptions;
		}

		// get_extensions_promo_list
		public function get_extensions_promo_list( array $args = array() ) {
			$installed_extensions      = ( ! empty( $args['installed_extensions'] ) ) ? $args['installed_extensions'] : array();
			$installed_extensions_keys = $this->get_sanitized_extensions_keys( $installed_extensions );

			$extensions_available_in_subscriptions      = ( ! empty( $args['extensions_available_in_subscriptions'] ) ) ? $args['extensions_available_in_subscriptions'] : array();
			$extensions_available_in_subscriptions_keys = is_array( $extensions_available_in_subscriptions ) ? array_keys( $extensions_available_in_subscriptions ) : array();

			// Filter extensions available in subscriptions
			$promo_extensions = $this->get_active_extensions();

			if ( ! empty( $promo_extensions ) && is_array( $installed_extensions_keys ) ) {

				foreach ( $promo_extensions as $_extension_base => $_extension_args ) {
					$extension_base_alias = $this->get_extension_alias_key( $_extension_base );
					$ext_key              = preg_replace( '/(directorist-)/', '', $_extension_base );
					$ext_alias_key        = preg_replace( '/(directorist-)/', '', $extension_base_alias );

					// Exclude Installed Extensions
					$in_installed_extensions       = in_array( $ext_key, $installed_extensions_keys ) ? true : false;
					$in_installed_extensions_alias = in_array( $ext_alias_key, $installed_extensions_keys ) ? true : false;

					if ( $in_installed_extensions || $in_installed_extensions_alias ) {
						unset( $promo_extensions[ $_extension_base ] );
					}

					// Exclude Subscripted Extensions
					$is_available_in_subscriptions       = in_array( $_extension_base, $extensions_available_in_subscriptions_keys ) ? true : false;
					$is_available_in_subscriptions_alias = in_array( $extension_base_alias, $extensions_available_in_subscriptions_keys ) ? true : false;

					if ( $is_available_in_subscriptions || $is_available_in_subscriptions_alias ) {
						unset( $promo_extensions[ $_extension_base ] );
					}
				}
			}

			return $promo_extensions;
		}

		// get_sanitized_extensions_keys
		public function get_sanitized_extensions_keys( array $extensions_list = array() ) {
			$extensions_keys = ( is_array( $extensions_list ) ) ? array_keys( $extensions_list ) : array();

			if ( ! empty( $extensions_keys ) && is_array( $extensions_keys ) ) {

				foreach ( $extensions_keys as $index => $key ) {
					$new_key = preg_replace( '/\/.+/', '', $key );
					$new_key = preg_replace( '/(directorist-)/', '', $new_key );

					$extensions_keys[ $index ] = $new_key;
				}
			}

			return $extensions_keys;
		}

		// get_themes_overview
		public function get_themes_overview() {
			// Check form theme update
			$current_theme = wp_get_theme();
			get_theme_update_available( $current_theme->stylesheet );

			$sovware_themes       = ( is_array( $this->themes ) ) ? array_keys( $this->themes ) : array();
			$theme_updates        = get_site_transient( 'update_themes' );
			$outdated_themes      = $theme_updates->response;
			$outdated_themes_keys = ( is_array( $outdated_themes ) ) ? array_keys( $outdated_themes ) : array();

			$all_themes            = wp_get_themes();
			$active_theme_slug     = get_option( 'stylesheet' );
			$installed_theme_list  = array();
			$total_active_themes   = 0;
			$total_outdated_themes = 0;

			foreach ( $all_themes as $theme_base => $theme_data ) {

				if ( in_array( $theme_base, $sovware_themes ) ) {
					$customizer_link = "customize.php?theme={$theme_data->stylesheet}&return=%2Fwp-admin%2Fthemes.php";
					$customizer_link = admin_url( $customizer_link );

					$installed_theme_list[ $theme_base ] = array(
						'name'            => $theme_data->name,
						'version'         => $theme_data->version,
						'thumbnail'       => $theme_data->get_screenshot(),
						'customizer_link' => $customizer_link,
						'has_update'      => ( in_array( $theme_data->stylesheet, $outdated_themes_keys ) ) ? true : false,
						'stylesheet'      => $theme_data->stylesheet,
					);

					if ( $active_theme_slug === $theme_base ) {
						$total_active_themes++;
					}

					if ( in_array( $theme_base, $outdated_themes_keys ) ) {
						$total_outdated_themes++;
					}
				}
			}

			$installed_themes_keys = ( is_array( $installed_theme_list ) ) ? array_keys( $installed_theme_list ) : array();

			// Themes available in subscriptions
			$themes_available_in_subscriptions = self::get_purchased_theme_list();
			$themes_available_in_subscriptions = ( ! empty( $themes_available_in_subscriptions ) && is_array( $themes_available_in_subscriptions ) ) ? $themes_available_in_subscriptions : array();

			if ( ! empty( $themes_available_in_subscriptions ) ) {

				foreach ( $themes_available_in_subscriptions as $base => $args ) {
					$item = $themes_available_in_subscriptions[ $base ];

					// Merge Local Theme Info
					if ( ! empty( $this->themes[ $base ] ) ) {
						$item = array_merge( $this->themes[ $base ], $item );
					}

					// Merge Local Theme Info
					if ( in_array( $base, $installed_themes_keys ) ) {
						$item = array_merge( $installed_theme_list[ $base ], $item );
					}

					$is_installed         = ( in_array( $base, $installed_themes_keys ) ) ? true : false;
					$item['is_installed'] = $is_installed;

					$themes_available_in_subscriptions[ $base ] = $item;
				}
			}

			// total_available_themes
			$total_available_themes = count( $themes_available_in_subscriptions );

			// themes_promo_list
			$themes_promo_list = $this->get_themes_promo_list(
				array(
					'installed_theme_list'              => $installed_theme_list,
					'themes_available_in_subscriptions' => $themes_available_in_subscriptions,
				)
			);

			// current_active_theme_info
			$current_active_theme_info = $this->get_current_active_theme_info(
				array(
					'outdated_themes_keys' => $outdated_themes_keys,
					'installed_theme_list' => $installed_theme_list,
				)
			);
			$current_active_theme_info['stylesheet'];

			$themes_available_in_subscriptions_keys = array_keys( $themes_available_in_subscriptions );

			if ( in_array( $current_active_theme_info['stylesheet'], $themes_available_in_subscriptions_keys ) ) {
				unset( $themes_available_in_subscriptions[ $current_active_theme_info['stylesheet'] ] );
			}

			$overview = array(
				'total_active_themes'               => $total_active_themes,
				'total_outdated_themes'             => $total_outdated_themes,
				'installed_theme_list'              => $installed_theme_list,
				'current_active_theme_info'         => $current_active_theme_info,
				'themes_promo_list'                 => $themes_promo_list,
				'themes_available_in_subscriptions' => $themes_available_in_subscriptions,
				'total_available_themes'            => $total_available_themes,
			);

			return $overview;
		}

		// get_current_active_theme_info
		public function get_current_active_theme_info( array $args = array() ) {
			// Get Current Active Theme Info
			$current_active_theme = wp_get_theme();
			$customizer_link      = "customize.php?theme={$current_active_theme->stylesheet}&return=%2Fwp-admin%2Fthemes.php";
			$customizer_link      = admin_url( $customizer_link );

			// Check form theme update
			$has_update = isset( $args['installed_theme_list'][ $current_active_theme->stylesheet ] ) ? $args['installed_theme_list'][ $current_active_theme->stylesheet ]['has_update'] : '';

			$active_theme_info = array(
				'name'            => $current_active_theme->name,
				'version'         => $current_active_theme->version,
				'thumbnail'       => $current_active_theme->get_screenshot(),
				'customizer_link' => $customizer_link,
				'has_update'      => $has_update,
				'stylesheet'      => $current_active_theme->stylesheet,
			);

			return $active_theme_info;
		}

		// get_themes_promo_list
		public function get_themes_promo_list( array $args = array() ) {
			$installed_theme_list  = ( ! empty( $args['installed_theme_list'] ) ) ? $args['installed_theme_list'] : array();
			$installed_themes_keys = $this->get_sanitized_themes_keys( $installed_theme_list );

			$themes_available_in_subscriptions      = ( ! empty( $args['themes_available_in_subscriptions'] ) ) ? $args['themes_available_in_subscriptions'] : array();
			$themes_available_in_subscriptions_keys = is_array( $themes_available_in_subscriptions ) ? array_keys( $themes_available_in_subscriptions ) : array();

			// Filter all active themes
			$themes_promo_list = $this->get_active_themes();

			if ( ! empty( $themes_promo_list ) ) {

				foreach ( $themes_promo_list as $_theme_base => $_extension_args ) {

					// Exclude Installed Themes
					if ( in_array( $_theme_base, $installed_themes_keys ) ) {
						unset( $themes_promo_list[ $_theme_base ] );
					}

					// Exclude Subscripted Themes
					if ( in_array( $_theme_base, $themes_available_in_subscriptions_keys ) ) {
						unset( $themes_promo_list[ $_theme_base ] );
					}
				}
			}

			return $themes_promo_list;
		}

		// get_sanitized_themes_keys
		public function get_sanitized_themes_keys( array $theme_list = array() ) {
			$theme_keys = ( is_array( $theme_list ) ) ? array_keys( $theme_list ) : array();

			return $theme_keys;
		}

		// remote_activate_license
		public static function remote_activate_license( $license_item = array() ) {
			$status = array( 'success' => false );

			if ( ! is_array( $license_item ) ) {
				$status['message'] = __( 'Nothing to activate', 'directorist' );

				return $status;
			}

			if ( isset( $license_item['skip_licencing'] ) && ! empty( $license_item['skip_licencing'] ) ) {
				$status['success'] = true;

				return $status;
			}

			$item_id = ( ! empty( $license_item['item_id'] ) ) ? $license_item['item_id'] : 0;
			$license = ( ! empty( $license_item['license'] ) ) ? $license_item['license'] : '';

			$activation_url = 'https://directorist.com';
			$query_args     = array(
				'edd_action' => 'activate_license',
				'url'        => home_url(),
				'item_id'    => $item_id,
				'license'    => $license,
			);

			try {
				$response = wp_remote_get(
					$activation_url,
					array(
						'timeout'   => 15,
						'sslverify' => false,
						'body'      => $query_args,
					)
				);

				$response_status = json_decode( $response['body'], true );
			} catch ( Exception $e ) {
				$status['success']  = false;
				$status['message']  = $e->getMessage();
				$status['response'] = null;

				return $status;
			}

			$status['response'] = $response_status;

			if ( empty( $response_status['success'] ) ) {
				$status['success'] = false;
				$status['message'] = __( 'Activation failed', 'directorist' );

				return $status;
			}

			$status['success'] = true;

			return $status;
		}

		// remote_authenticate_user
		public static function remote_authenticate_user( $user_credentials = array() ) {
			$status = array( 'success' => true );

			$url     = 'https://directorist.com/wp-json/directorist/v1/licencing';
			$headers = array(
				'user-agent' => 'Directorist/' . md5( esc_url( home_url() ) ) . ';',
				'Accept'     => 'application/json',
			);

			$config = array(
				'method'      => 'GET',
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'headers'     => $headers,
				'cookies'     => array(),
				'body'        => $user_credentials, // [ 'user' => '', 'password' => '']
			);

			$response_body = array();

			try {
				$response = wp_remote_get( $url, $config );

				if ( is_wp_error( $response ) ) {
					$status['success'] = false;
					$status['message'] = Directorist\Helper::get_first_wp_error_message( $response );
				} else {
					$response_body = is_string( $response['body'] ) ? json_decode( $response['body'], true ) : $response['body'];
				}
			} catch ( Exception $e ) {
				$status['success'] = false;
				$status['message'] = $e->getMessage();
			}

			if ( is_array( $response_body ) ) {
				$status = array_merge( $status, $response_body );
			}

			if ( empty( $response_body['success'] ) ) {
				$status['success'] = false;
			}

			$status['response'] = $response_body;

			return $status;
		}

		// get_file_download_link
		public static function get_file_download_link( $file_item = array(), $product_type = 'plugin' ) {
			if ( ! is_array( $file_item ) ) {
				return '';
			}

			if ( ! isset( $file_item['item_id'] ) ) {
				return '';
			}

			if ( ! isset( $file_item['license'] ) ) {
				return '';
			}

			if ( empty( $file_item['item_id'] ) || empty( $file_item['license'] ) ) {
				return '';
			}

			$activation_url = 'https://directorist.com/wp-json/directorist/v1/get-product-data/';
			$query_args     = array(
				'product_type' => $product_type,
				'license'      => $file_item['license'],
				'item_id'      => $file_item['item_id'],
				'get_info'     => 'download_link',
			);

			try {
				$response = wp_remote_get(
					$activation_url,
					array(
						'timeout'   => 15,
						'sslverify' => false,
						'body'      => $query_args,
					)
				);

				$response = json_decode( $response['body'], true );
			} catch ( Exception $e ) {
				return '';
			}

			$status['response'] = $response;

			if ( empty( $response['success'] ) && empty( $response['data'] ) ) {
				return '';
			}

			return $response['data'];
		}

		// get_purchased_extension_list
		public static function get_purchased_extension_list() {
			$extensions_available_in_subscriptions = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
			$directorist_purchased_extension_list  = apply_filters( 'directorist_purchased_extension_list', $extensions_available_in_subscriptions );

			if ( is_array( $directorist_purchased_extension_list ) ) {
				return $directorist_purchased_extension_list;
			}

			return $extensions_available_in_subscriptions;
		}

		// get_purchased_theme_list
		public static function get_purchased_theme_list() {
			$themes_available_in_subscriptions = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );
			$directorist_purchased_theme_list  = apply_filters( 'directorist_purchased_theme_list', $themes_available_in_subscriptions );

			if ( is_array( $directorist_purchased_theme_list ) ) {
				return $directorist_purchased_theme_list;
			}

			return $themes_available_in_subscriptions;
		}

		// filter_product_name
		public static function filter_product_type( $product_type = '' ) {
			if ( 'plugins' === $product_type ) {
				$product_type = 'plugin';
			}

			if ( 'themes' === $product_type ) {
				$product_type = 'theme';
			}

			return $product_type;
		}

		/**
		 * It Loads Extension view
		 */
		public function show_extension_view() {
			// delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );
			// delete_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion' );

			// Check Sassion
			$has_subscriptions_sassion = get_user_meta( get_current_user_id(), '_atbdp_has_subscriptions_sassion', true );
			$is_logged_in              = ( ! empty( $has_subscriptions_sassion ) ) ? true : false;

			$settings_url = admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-settings#extension_settings__extensions_general' );

			$extensions_overview = $this->get_extensions_overview();
			$themes_overview     = $this->get_themes_overview();

			$hard_logout = apply_filters( 'atbdp_subscriptions_hard_logout', false );
			$hard_logout = ( $hard_logout ) ? 1 : 0;

			$data = array(
				'ATBDP_Extensions'                      => $this,
				'is_logged_in'                          => $is_logged_in,
				'hard_logout'                           => $hard_logout,

				'total_active_extensions'               => $extensions_overview['total_active_extensions'],
				'total_outdated_extensions'             => $extensions_overview['total_outdated_extensions'],
				'outdated_plugin_list'                  => $extensions_overview['outdated_plugin_list'],
				'installed_extension_list'              => $extensions_overview['installed_extension_list'],
				'extensions_available_in_subscriptions' => $extensions_overview['extensions_available_in_subscriptions'],
				'total_available_extensions'            => $extensions_overview['total_available_extensions'],
				'extensions_promo_list'                 => $extensions_overview['extensions_promo_list'],
				'required_extensions_list'              => $extensions_overview['required_extensions'],

				'total_active_themes'                   => $themes_overview['total_active_themes'],               // $my_active_themes,
				'total_outdated_themes'                 => $themes_overview['total_outdated_themes'],             // $my_outdated_themes,
				'installed_theme_list'                  => $themes_overview['installed_theme_list'],              // $installed_theme_list,
				'current_active_theme_info'             => $themes_overview['current_active_theme_info'],         // $active_theme,
				'themes_available_in_subscriptions'     => $themes_overview['themes_available_in_subscriptions'], // $themes_available_in_subscriptions,
				'total_available_themes'                => $themes_overview['total_available_themes'],
				'themes_promo_list'                     => $themes_overview['themes_promo_list'],

				'extension_list'                        => $this->extensions,
				'theme_list'                            => $this->themes,

				'settings_url'                          => $settings_url,
			);

			ATBDP()->load_template( 'admin-templates/theme-extensions/theme-extension', $data );
		}

		private function is_verified_nonce() {
			$nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			return wp_verify_nonce( $nonce, 'atbdp_nonce_action_js' );
		}

		/**
		 * Check the extension is being downloaded from varified source.
		 *
		 * @param  string $extension_url
		 *
		 * @return bool
		 */
		protected function is_varified_host( $extension_url ) {
			$signed_hostnames = array( 'directorist.com' );

			return in_array( parse_url( $extension_url, PHP_URL_HOST ), $signed_hostnames, true );
		}

	}

}
