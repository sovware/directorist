<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_All_Authors {

	protected static $instance = null;

	private function __construct() {

	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function author_profile_pic( $id, $key ) {
		$pro_pic  			  = get_user_meta( $id, $key, true );
		$u_pro_pic            = ! empty( $pro_pic ) ? wp_get_attachment_image_src( $pro_pic, 'thumbnail' ) : '';
		$author_image_src     = ! empty( $u_pro_pic ) ? $u_pro_pic[0] : get_avatar_url( $id );
		return ! empty( $author_image_src ) ? $author_image_src : '';
	}

	public static function author_meta( $id, $key ) {
		$value  = get_user_meta( $id, $key, true );

		return ! empty( $value ) ? $value : '';
	}

	public function display_sorting() {
		return get_directorist_option( 'all_authors_sorting', true );
	}

	public function display_image() {
		return get_directorist_option( 'all_authors_name', true );
	}

	public function display_name() {
		return get_directorist_option( 'all_authors_name', true );
	}

	public function display_role() {
		return get_directorist_option( 'all_authors_role', true );
	}

	public function display_contact_info() {
		return get_directorist_option( 'all_authors_info', true );
	}

	public function display_description() {
		return get_directorist_option( 'all_authors_description', true );
	}

	public function display_social_info() {
		return get_directorist_option( 'all_authors_social_info', true );
	}

	public function display_btn() {
		return get_directorist_option( 'all_authors_button', true );
	}

	public function get_columns() {
		return floor( 12 / get_directorist_option( 'all_authors_columns', 3 ) );
	}

	public function author_list( $alphabet = '' ) {
		$args = array();
		$all_authors_select_role = get_directorist_option( 'all_authors_select_role', 'all' );
		if( 'all' != $all_authors_select_role ) {
			$args = array( 'role__in' => array( $all_authors_select_role ) );
		}
		return get_users( $args );
	}

	public function render_shortcode_all_authors() {

		$all_authors_role	        =	get_directorist_option( 'all_authors_role', true );
		$all_authors_select_role	=	get_directorist_option( 'all_authors_select_role', 'all' );
		$args = array();
		if( ! empty( $all_authors_role ) && 'all' != $all_authors_select_role ) {
			$args = array( 'role__in' => array( $all_authors_select_role ) );
		}
		$args = array(
			'all_authors' 						=> get_users( $args ),
			'all_authors_columns'				=> get_directorist_option( 'all_authors_columns', 3 ),
			'all_authors_image'					=> get_directorist_option( 'all_authors_image', true ),
			'all_authors_name'					=> get_directorist_option( 'all_authors_name', true ),
			'all_authors_role'					=> $all_authors_role,
			'all_authors_info'					=> get_directorist_option( 'all_authors_info', true ),
			'all_authors_description'			=> get_directorist_option( 'all_authors_description', true ),
			'all_authors_description_limit'		=> get_directorist_option( 'all_authors_description_limit', 13 ),
			'all_authors_social_info'			=> get_directorist_option( 'all_authors_social_info', true ),
			'all_authors_button'				=> get_directorist_option( 'all_authors_button', true ),
			'all_authors_button_text'			=> get_directorist_option( 'all_authors_button_text', 'View All Listings' ),
		);

		Helper::get_template( 'all-authors', array( 'authors' => $this, 'args' => $args ) );
	}

}