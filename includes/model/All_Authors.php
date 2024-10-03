<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_All_Authors {

	public function __construct() {

	}

	public function render_shortcode_all_authors() {
		return Helper::get_template_contents( 'all-authors', array( 'authors' => $this ) );
	}

	public static function user_image_src( $user ) {
		$id           = $user->data->ID;
		$image_id  	  = get_user_meta( $id, 'pro_pic', true );
		$image_data   = wp_get_attachment_image_src( $image_id, 'full' ); // @kowsar @todo size
		$image_src    = $image_data ? $image_data[0] : get_avatar_url( $id );
		return $image_src ? $image_src : '';
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

	public function contact_info() {
		return get_directorist_option( 'all_authors_contact', array( 'phone', 'address', 'website' ) );
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

	public function author_list( $type = '' ) {
		$args = array();
		$all_authors_select_role = get_directorist_option( 'all_authors_select_role', 'all' );
		$all_authors_per_page	 = get_directorist_option( 'all_authors_per_page', 9 );

		if( $this->display_pagination() ) {
			$paged					 = ! empty( $_REQUEST['paged'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['paged'] ) ) : atbdp_get_paged_num();
			$offset 				 = ( $paged - 1 ) * $all_authors_per_page;
			$args['paged'] 			 = $paged;
			$args['offset'] 		 = $offset;
		}

		if( 'pagination' != $type ) {
			$args['number'] 		 = $all_authors_per_page;
		}

		$args['orderby'] 		 = 'display_name';

		if ( 'author' == $all_authors_select_role ) {
			$meta_query = array(
				array(
					'key'     => '_user_type',
					'value'   => 'author',
					'compare' => '='
				)
			);
			$args['meta_query'] = $meta_query;
		} elseif ( 'all' != $all_authors_select_role ) {
			$args['role__in'] = array( $all_authors_select_role );
		}

		if( ! empty( $_REQUEST['alphabet'] ) && 'ALL' != $_REQUEST['alphabet'] ) {
			$args['search'] 		= sanitize_text_field( wp_unslash( $_REQUEST['alphabet'] ) ) . '*';
			$args['search_columns'] = array('display_name');
		}

		return get_users( $args );
	}

	public function author_pagination( $base = '', $paged = '' ) {
		$all_authors_per_page	 = get_directorist_option( 'all_authors_per_page', 9 );
		$query 					 = $this->author_list('pagination');
		$paged 					 = ! empty( $_REQUEST['paged'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['paged'] ) ) : atbdp_get_paged_num();
		$big   					 = 999999999;
		$total_pages 			 = ceil( count( $query ) / $all_authors_per_page );

		$links = paginate_links( array(
			'base'      => ! empty( $_REQUEST['paged'] ) || ! empty( $_REQUEST['alphabet'] ) ? 'page/%#%' : str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, $paged ),
			'total'     => $total_pages,
			'prev_text' => directorist_icon( 'las la-arrow-left', false ),
			'next_text' => directorist_icon( 'las la-arrow-right', false ),
		) );

		return $links;
	}

}