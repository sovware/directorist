<?php
/**
 * Background Updater.
 *
 * @since 7.0.6
 * @package Directorist
 */
namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use ATBDP_Installation;
use Directorist\Multi_Directory\Multi_Directory_Manager;

if ( ! class_exists( __NAMESPACE__ . '\Background_Process', false ) ) {
	include_once ATBDP_INC_DIR . 'classes/class-abstract-background-process.php';
}

/**
 * Background_Updater Class.
 */
class Background_Updater extends Background_Process {

	/**
	 * Initiate new background process.
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'v8_force_migration' ] );

		// Uses unique prefix per blog so each blog has separate queue.
		$this->prefix = 'wp_' . get_current_blog_id();
		$this->action = 'directorist_updater';

		parent::__construct();
	}

	/**
	 * Dispatch updater.
	 *
	 * Updater will still run via cron job if this fails for any reason.
	 */
	public function dispatch() {
		$dispatched = parent::dispatch();
		// $logger     = wc_get_logger();

		// if ( is_wp_error( $dispatched ) ) {
		// $logger->error(
		// sprintf( 'Unable to dispatch WooCommerce updater: %s', $dispatched->get_error_message() ),
		// array( 'source' => 'wc_db_updates' )
		// );
		// }
	}

	/**
	 * Handle cron healthcheck
	 *
	 * Restart the background process if not already running
	 * and data exists in the queue.
	 */
	public function handle_cron_healthcheck() {
		if ( $this->is_process_running() ) {
			// Background process already running.
			return;
		}

		if ( $this->is_queue_empty() ) {
			// No data to process.
			$this->clear_scheduled_event();
			return;
		}

		$this->handle();
	}

	/**
	 * Schedule fallback event.
	 */
	protected function schedule_event() {
		if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
			wp_schedule_event( time() + 10, $this->cron_interval_identifier, $this->cron_hook_identifier );
		}
	}

	/**
	 * Is the updater running?
	 *
	 * @return boolean
	 */
	public function is_updating() {
		return false === $this->is_queue_empty();
	}

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param string $callback Update callback function.
	 * @return string|bool
	 */
	protected function task( $callback ) {
		defined( 'DIRECTORIST_UPDATING' ) || define( 'DIRECTORIST_UPDATING', true );

		// $logger = wc_get_logger();

		include_once ATBDP_INC_DIR . 'update-functions.php';

		$result = false;

		if ( is_callable( $callback ) ) {
			// $logger->info( sprintf( 'Running %s callback', $callback ), array( 'source' => 'wc_db_updates' ) );
			$result = (bool) call_user_func( $callback, $this );

			if ( $result ) {
				// $logger->info( sprintf( '%s callback needs to run again', $callback ), array( 'source' => 'wc_db_updates' ) );
			} else {
				// $logger->info( sprintf( 'Finished running %s callback', $callback ), array( 'source' => 'wc_db_updates' ) );
			}
		} else {
			// $logger->notice( sprintf( 'Could not find %s callback', $callback ), array( 'source' => 'wc_db_updates' ) );
		}

		return $result ? $callback : false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		// $logger = wc_get_logger();
		// $logger->info( 'Data update complete', array( 'source' => 'wc_db_updates' ) );
		// ATBDP_Installation::update_db_version();
		update_option( 'directorist_db_updated', true, 'no' );

		parent::complete();
	}

	/**
	 * See if the batch limit has been exceeded.
	 *
	 * @return bool
	 */
	public function is_memory_exceeded() {
		return $this->memory_exceeded();
	}

	public function v8_force_migration() {

		if( ! get_option( 'directorist_v8_force_migrated' ) ) {

			$this->run_v8_migration();

			update_option( 'directorist_v8_force_migrated', true );
		}
	}

	public function run_v8_migration() {

		$migrated = get_option( 'directorist_builder_header_migrated', false );
	
		if ( ! $migrated ) {
	
			//create account page
			$options = get_option( 'atbdp_option' );
			$account = wp_insert_post(
				array(
					'post_title' 	 => 'Sign In',
					'post_content' 	 => '[directorist_signin_signup]',
					'post_status' 	 => 'publish',
					'post_type' 	 => 'page',
					'comment_status' => 'closed'
				)
			);
		
			if ( $account ) {
				$options['signin_signup_page'] = (int) $account;
				$options['marker_shape_color'] 	= '#444752';
				$options['marker_icon_color'] 	= '#ffffff';
				
				update_option( 'atbdp_option', $options );
			}
		
			$directory_types = get_terms([
				'taxonomy'   => ATBDP_DIRECTORY_TYPE,
				'hide_empty' => false,
			]);
		
			if ( is_wp_error( $directory_types ) || empty( $directory_types ) ) {
				return;
			}
		
			foreach ( $directory_types as $directory_type ) {
		
				$this->search_field_label_migration( $directory_type );

				// backup the builder data
				Multi_Directory_Manager::builder_data_backup( $directory_type->term_id );
				//migrate custom field
				Multi_Directory_Manager::migrate_custom_field( $directory_type->term_id );
				//migrate review settings
				Multi_Directory_Manager::migrate_review_settings( $directory_type->term_id );
				//migrate contact form
				Multi_Directory_Manager::migrate_contact_owner_settings( $directory_type->term_id );
				//migrate related listing settings
				Multi_Directory_Manager::migrate_related_listing_settings( $directory_type->term_id );
				//migrate privacy policy
				Multi_Directory_Manager::migrate_privacy_policy( $directory_type->term_id );
		
				//migrate builder single listing header
				$new_structure   = [];
				$header_contents = get_term_meta( $directory_type->term_id, 'single_listing_header', true );
		
				if ( empty( $header_contents ) ) {
					continue;
				}
		
				$description = ! empty( $header_contents['options']['content_settings']['listing_description']['enable'] ) ? $header_contents['options']['content_settings']['listing_description']['enable'] : false;
				$tagline     = ! empty( $header_contents['options']['content_settings']['listing_title']['enable_tagline'] ) ? $header_contents['options']['content_settings']['listing_title']['enable_tagline'] : false;
				$contents    = get_term_meta( $directory_type->term_id, 'single_listings_contents', true );
		
				if ( $description ) {
		
					$contents['fields']['description'] = [
						"icon" => "las la-tag",
						"widget_group" => "preset_widgets",
						"widget_name" => "description",
						"original_widget_key" => "description",
						"widget_key" => "description"
					];
		
					$details = [
						"type" => "general_group",
						"label" => "Description",
						"fields" => [
							"description"
						],
						"section_id" => "1627188303" . $directory_type->term_id
					];
		
					array_unshift( $contents['groups'], $details );
		
					update_term_meta( $directory_type->term_id, 'single_listings_contents', $contents );
		
				}
		
				if ( empty( $header_contents['listings_header'] ) ) {
					continue;
				}
		
				foreach ( $header_contents['listings_header'] as $section_name => $widgets ) {
		
					if ( 'quick_actions' === $section_name ) {
						$quick_widget = [
							"type" => "placeholder_group",
							"placeholderKey" => "quick-widgets-placeholder",
							"placeholders" => [
								[
									"type" => "placeholder_group",
									"placeholderKey" => "quick-info-placeholder",
									"selectedWidgets" => [
										[
											"type" => "button",
											"label" => "Back",
											"widget_name" => "back",
											"widget_key" => "back"
										]
									]
								],
								[
									"type" => "placeholder_group",
									"placeholderKey" => "quick-action-placeholder",
									"selectedWidgets" => $widgets,
								]
							]
						];
		
						array_push( $new_structure, $quick_widget );
					}
		
		
					if ( 'thumbnail' === $section_name ) {
						$footer_thumbnail = ! empty( $widgets[0]['footer_thumbail'] ) ? $widgets[0]['footer_thumbail'] : true;
						$slider_widget = [
							"type" => "placeholder_item",
							"placeholderKey" => "slider-placeholder",
							"selectedWidgets" => [
								[
									"type" => "thumbnail",
									"label" => "Listing Image/Slider",
									"widget_name" => "slider",
									"widget_key" => "slider",
									'options'  => [
										'title'  => __( 'Listings Slider Settings', 'directorist' ),
										'fields' => [
											'footer_thumbnail' => [
												'type'  => 'toggle',
												'label' => __( 'Enable Footer Thumbnail', 'directorist' ),
												'value' => $footer_thumbnail,
											],
										],
									],
								]
							]
						];
		
						array_push( $new_structure, $slider_widget );
					}
		
					if ( 'quick_info' === $section_name ) {
		
						$title_widget = [
							"type" => "placeholder_item",
							"placeholderKey" => "listing-title-placeholder",
							"selectedWidgets" => [
								[
									"type" => "title",
									"label" => "Listing Title",
									"widget_name" => "title",
									"widget_key" => "title",
									'options' => [
										'title' => __( "Listing Title Settings", "directorist" ),
										'fields' => [
											'enable_tagline' => [
												'type' => "toggle",
												'label' => __( "Show Tagline", "directorist" ),
												'value' => $tagline,
											],
										],
									],
								]
							]
						];
		
						array_push( $new_structure, $title_widget );
		
						$more_widget = [
							"type" => "placeholder_item",
							"placeholderKey" => "more-widgets-placeholder",
							"selectedWidgets" => $widgets,
						];
		
						array_push( $new_structure, $more_widget );
					}
		
				}
		
				$new_structure = apply_filters( 'directorist_single_listing_header_migration_data', $new_structure, $header_contents );
		
				update_term_meta( $directory_type->term_id, 'single_listing_header', $new_structure );
		
			}
		
			update_option( 'directorist_builder_header_migrated', true );

		}

		$path = WP_PLUGIN_DIR . '/addonskit-for-elementor/addonskit-for-elementor.php';

		if ( did_action( 'elementor/loaded' ) && ! file_exists( $path ) ) {
			
			directorist_download_plugin( [ 'url' => 'https://downloads.wordpress.org/plugin/addonskit-for-elementor.zip' ] );
	
			if ( ! is_plugin_active( $path ) ){
				activate_plugin( $path );
			}
		}
	}

	private function search_field_label_migration( $directory_type ) {

		$search_form_fields_data = get_term_meta( $directory_type->term_id, 'search_form_fields', true );
	
		$fields = isset($search_form_fields_data['fields']) && ! empty($search_form_fields_data['fields']) ? $search_form_fields_data['fields'] : [];

		foreach ( $fields as $key => $values ) {

			$placeholder = isset( $values['placeholder'] ) && ! empty( $values['placeholder'] ) ? $values['placeholder'] : '';

			$label = isset( $values['label'] ) && ! empty( $values['label'] ) ? $values['label'] : $placeholder;

			// Update the field data
			$updated_field_data['label'] = $label;

			// Merge the updated data back into the form fields
			$search_form_fields_data['fields'][$key] = array_merge( $fields[$key], $updated_field_data );
		}

		update_term_meta( $directory_type->term_id, 'search_form_fields', $search_form_fields_data );
	}
}
