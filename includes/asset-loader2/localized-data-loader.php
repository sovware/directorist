<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Localized_Data_Loader {
	/**
	 * Load public localized data.
	 */
	public function load_localized_data() {
		// Public JS
		wp_localize_script( 'directorist-main-script', 'atbdp_public_data', Script_Helper::get_main_script_data() );
		wp_localize_script( 'directorist-main-script', 'directorist_options', $this->directorist_options_data() );
		wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', $this->search_form_localized_data() );

		wp_localize_script( 'directorist-range-slider', 'atbdp_range_slider', $this->search_listing_localized_data() );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search_listing', $this->search_listing_localized_data() );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search', [
			'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
			'ajax_url' => admin_url('admin-ajax.php'),
		]);

		// Admin JS
		wp_localize_script( 'directorist-admin-script', 'atbdp_admin_data', Script_Helper::get_admin_script_data() );
		wp_localize_script( 'directorist-admin-script', 'directorist_options', $this->directorist_options_data() );
		wp_localize_script( 'directorist-admin-script', 'atbdp_public_data', Script_Helper::get_main_script_data() );
		wp_localize_script( 'directorist-multi-directory-builder', 'ajax_data', $this->admin_ajax_localized_data() );
		wp_localize_script( 'directorist-multi-directory-archive', 'ajax_data', $this->admin_ajax_localized_data() );
		wp_localize_script( 'directorist-settings-manager', 'ajax_data', $this->admin_ajax_localized_data() );
		wp_localize_script( 'directorist-import-export', 'import_export_data', $this->admin_ajax_localized_data() );
	}

	private function search_listing_localized_data() {
		return Script_Helper::get_search_script_data([
			'directory_type_id' => get_post_meta( '_directory_type', get_the_ID(), true ),
		]);
	}

	private function search_form_localized_data() {
		$directory_type_id = ( isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : '';
		$data = Script_Helper::get_search_script_data([
			'directory_type_id' => $directory_type_id
		]);
		return $data;
	}

	private function directorist_options_data() {
		return Script_Helper::get_option_data();
	}

	private function admin_ajax_localized_data() {
		return [ 'ajax_url' => admin_url('admin-ajax.php') ];
	}
}