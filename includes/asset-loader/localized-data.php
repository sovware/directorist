<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

use Directorist\Script_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Localized_Data {

	public static function load_localized_data() {
		// Public JS
		wp_localize_script( 'directorist-main-script', 'atbdp_public_data', Script_Helper::get_main_script_data() );
		wp_localize_script( 'directorist-main-script', 'directorist_options', self::directorist_options_data() );
		wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', self::search_form_localized_data() );

		wp_localize_script( 'directorist-range-slider', 'atbdp_range_slider', self::search_listing_localized_data() );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search_listing', self::search_listing_localized_data() );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search', [
			'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
			'ajax_url' => admin_url('admin-ajax.php'),
		]);

		// Admin JS
		wp_localize_script( 'directorist-admin-script', 'atbdp_admin_data', Script_Helper::get_admin_script_data() );
		wp_localize_script( 'directorist-admin-script', 'directorist_options', self::directorist_options_data() );
		wp_localize_script( 'directorist-admin-script', 'atbdp_public_data', Script_Helper::get_main_script_data() );
		wp_localize_script( 'directorist-multi-directory-builder', 'ajax_data', self::admin_ajax_localized_data() );
		wp_localize_script( 'directorist-multi-directory-archive', 'ajax_data', self::admin_ajax_localized_data() );
		wp_localize_script( 'directorist-settings-manager', 'ajax_data', self::admin_ajax_localized_data() );
		wp_localize_script( 'directorist-import-export', 'import_export_data', self::admin_ajax_localized_data() );
	}

	private static function search_listing_localized_data() {
		return Script_Helper::get_search_script_data([
			'directory_type_id' => get_post_meta( '_directory_type', get_the_ID(), true ),
		]);
	}

	private static function search_form_localized_data() {
		$directory_type_id = ( isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : '';
		$data = Script_Helper::get_search_script_data([
			'directory_type_id' => $directory_type_id
		]);
		return $data;
	}

	private static function directorist_options_data() {
		return Script_Helper::get_option_data();
	}

	private static function admin_ajax_localized_data() {
		return [ 'ajax_url' => admin_url('admin-ajax.php') ];
	}
}