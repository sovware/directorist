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
		return get_directorist_option( 'all_authors_image', true );
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

	public function description_limit() {
		return get_directorist_option( 'all_authors_description_limit', 13 );
	}

	public function display_social_info() {
		return get_directorist_option( 'all_authors_social_info', true );
	}

	public function display_btn() {
		return get_directorist_option( 'all_authors_button', true );
	}

	public function btn_text() {
		return get_directorist_option( 'all_authors_button_text', 'View All Listings' );
	}

	public function get_columns() {
		return floor( 12 / get_directorist_option( 'all_authors_columns', 3 ) );
	}

	public function display_pagination() {
		return get_directorist_option( 'all_authors_pagination', true );
	}

	public function author_list( $alphabet = '' ) {
		$args = array();
		$all_authors_select_role = get_directorist_option( 'all_authors_select_role', 'all' );
		$all_authors_per_page	 = get_directorist_option( 'all_authors_per_page', 9 );

		if( $this->display_pagination() ) {
			$paged					 = ! empty( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : atbdp_get_paged_num();
			$offset 				 = ( $paged - 1 ) * $all_authors_per_page;
			$args['paged'] 			 = $paged;
			$args['offset'] 		 = $offset;
		}

		$args['number'] 		 = $all_authors_per_page;
		
		if( 'all' != $all_authors_select_role ) {
			$args['role__in']	= array( $all_authors_select_role );
		}

		if( ! empty( $_REQUEST['alphabet'] ) ) {
			$letterAfter = chr( ord( $_REQUEST['alphabet'] ) + 1 );

			$args['meta_query'] = array(
				array(
					'key' => 'first_name',
					'value' => array( $_REQUEST['alphabet'], $letterAfter ),
					'compare' => 'BETWEEN'
				)
			);

		}

		return get_users( $args );
	}

	public function author_pagination( $base = '', $paged = '' ) {
		$all_authors_per_page	 = get_directorist_option( 'all_authors_per_page', 9 );
		$query 					 = get_users();
		$paged 					 = ! empty( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : atbdp_get_paged_num();
		$big   					 = 999999999;
		$total_pages 			 = ceil( count( $query ) / $all_authors_per_page );

		$links = paginate_links( array(
			'base'      => ! empty( $_REQUEST['paged'] ) ? 'page/%#%' : str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, $paged ),
			'total'     => $total_pages,
			'prev_text' => '<i class="la la-arrow-left"></i>',
			'next_text' => '<i class="la la-arrow-right"></i>',
		) );

		return $links;
	}

	public function render_shortcode_all_authors() {
		ob_start();
		
		Helper::get_template( 'all-authors', array( 'authors' => $this ) );

		return ob_get_clean();
	}

}