<?php
/**
 * @author wpWax
 */

namespace wpWax\Directorist\Shortcode;

use wpWax\Directorist\Model\Listings;
use Directorist\Helper;
use Directorist\Script_Helper;

class Directorist_All_Listing {

	public static $instance = null;

	public function __construct() {
		add_shortcode( 'directorist_all_listing', [ $this, 'render_shortcode' ] );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function render_shortcode( $atts = [] ) {
		$listings = directorist()->listings;
		$listings->init( $atts );

		$script_args = [ 'directory_type_id' => $listings->get_current_listing_type() ];
		Script_Helper::load_search_form_script( $script_args );

		ob_start();

		if (!empty($listings->redirect_page_url())) {
			$redirect = '<script>window.location="' . esc_url($listings->redirect_page_url()) . '"</script>';
			return $redirect;
		}

		if ( $listings->logged_in_user_only() && ! is_user_logged_in() ) {
			return \ATBDP_Helper::guard([ 'type' => 'auth' ]);
		}

		// Load the template
		Helper::get_template( 'archive-contents', array( 'listings' => $listings ), 'listings_archive' );

		return ob_get_clean();
	}

}

Directorist_All_Listing::instance();