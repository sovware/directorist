<?php
/**
 * @author wpWax
 */

namespace Directorist;

use \ATBDP_Permalink;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Dashboard {

	protected static $instance = null;
	public static $display_title = false;

	public $id;

	public $current_listings_query;
	public $user_type;
	public $become_author_button;
	public $become_author_button_text;

	private function __construct() {
		$this->id 						 = get_current_user_id();
		$user_type 		  		 		 = get_user_meta( get_current_user_id(), '_user_type', true );
		$this->user_type  		 		 = ! empty( $user_type ) ? $user_type : '';
		$this->become_author_button 	 = get_directorist_option( 'become_author_button', 1);
		$this->become_author_button_text = get_directorist_option( 'become_author_button_text', __( 'Become An Author', 'directorist' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function get_id() {
		return $this->id;
	}

	public function ajax_listing_tab() {
		$data     = array_filter( $_POST, 'sanitize_text_field' ); // sanitization
		$type     = $data['tab'];
		$paged    = $data['paged'];
		$search   = $data['search'];
		$task     = $data['task'];
		$taskdata = $data['taskdata'];

		if ( $task ) {
			$this->listing_task( $task, $taskdata );
		}

		$args = array(
			'dashboard' => $this,
			'query'     => $this->listings_query( $type, $paged, $search ),
		);

		$result = [
			'content'    => Helper::get_template_contents( 'dashboard/listing-row', $args ),
			'pagination' => $this->listing_pagination( 'page/%#%', $paged ),
		];

		wp_send_json_success( $result );

		wp_die();
	}

	public function listing_task( $task, $taskdata ){
		if ( $task == 'delete' ) {
			wp_delete_post( $taskdata );
		}
	}

	public function listings_query( $type = 'all', $paged = 1, $search = '' ) {
		$pagination = get_directorist_option('user_listings_pagination',1);
		$listings_per_page = get_directorist_option('user_listings_per_page',9);

		$args  = array(
			'author'         => get_current_user_id(),
			'post_type'      => ATBDP_POST_TYPE,
			'posts_per_page' => (int) $listings_per_page,
			'order'          => 'DESC',
			'orderby'        => 'date',
			'post_status'    => array('publish', 'pending', 'private'),
		);

		if( ! empty( $pagination) ) {
			$args['paged'] = $paged;
		}
		else{
			$args['no_found_rows'] = false;
		}

		if ( $type == 'publish' ) {
			$args['post_status'] = 'publish';
		}
		if ( $type == 'pending' ) {
			$args['post_status'] = 'pending';
		}
		elseif ( $type == 'expired' ) {
			$args['meta_query'] = array(
				array(
					'key'   => '_listing_status',
					'value' => 'expired'
				),
			);
		}

		if ( $search ) {
			$args['s'] = $search;
		}

		$this->current_listings_query = new \WP_Query( $args );

		return $this->current_listings_query;
	}

	private function enqueue_scripts() {
		// wp_enqueue_script( 'directorist-atmodal' );
		// wp_enqueue_script( 'directorist-ez-media-uploader' );

		// wp_enqueue_script( 'directorist-search-form-listing' );
		// wp_localize_script( 'directorist-search-form-listing', 'atbdp_search', array(
		// 	'ajaxnonce'       => wp_create_nonce( 'bdas_ajax_nonce' ),
		// 	'ajax_url'        => admin_url( 'admin-ajax.php' ),
		// 	'added_favourite' => __( 'Added to favorite', 'directorist' ),
		// 	'please_login'    => __( 'Please login first', 'directorist' ),
		// ));
	}

	public function get_listing_price_html() {
		$id = get_the_ID();
		$price = get_post_meta( $id, '_price', true );
		$price_range = get_post_meta( $id, '_price_range', true );
		$atbd_listing_pricing = get_post_meta( $id, '_atbd_listing_pricing', true );
		if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
			return atbdp_display_price_range( $price_range );
		}
		else {
			return atbdp_display_price( $price );
		}
	}

	public function get_listing_expired_html() {
		$id = get_the_ID();
		$date_format = get_option('date_format');
		$exp_date  = get_post_meta($id, '_expiry_date', true);
		$never_exp = get_post_meta($id, '_never_expire', true);
		$status    = get_post_meta($id, '_listing_status', true);
		$exp_text  = !empty($never_exp) ? __('Never Expires', 'directorist') : date_i18n($date_format, strtotime($exp_date));
		$exp_html  = ( $status == 'expired' ) ? '<span style="color: red">' . __('Expired', 'directorist') . '</span>' : $exp_text;
		return $exp_html;
	}

	public function listing_pagination( $base = '', $paged = '' ) {
		$query = $this->current_listings_query;
		$paged = $paged ? $paged : atbdp_get_paged_num();
		$big   = 999999999;

		$links = paginate_links(array(
			'base'      => $base ? $base : str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'current'   => max(1, $paged),
			'total'     => $query->max_num_pages,
			'prev_text' => '<i class="la la-arrow-left"></i>',
			'next_text' => '<i class="la la-arrow-right"></i>',
		));

		return $links;
	}

	public function get_listing_status_html() {
		$id = get_the_ID();
		$status_label = get_post_status_object( get_post_status( $id ) )->label;
		$html = sprintf('<span class="directorist_badge dashboard-badge directorist_status_%s">%s</span>', strtolower($status_label), $status_label );
		return $html;
	}

	public function get_listing_type() {
		$id   = get_the_ID();
		$type = get_post_meta( $id, '_directory_type', true );
		$term = get_term( $type );
		return !empty( $term->name ) ? $term->name : '';
	}

	public function get_listing_thumbnail() {
		$id                = get_the_ID();
		$type              = get_post_meta( $id, '_directory_type', true );

		$default_image_src = Helper::default_preview_image_src( $type );
		$image_quality     = get_directorist_option('preview_image_quality', 'large');
		$listing_prv_img   = get_post_meta($id, '_listing_prv_img', true);
		$listing_img       = get_post_meta($id, '_listing_img', true);

		if ( is_array( $listing_img ) && ! empty( $listing_img ) ) {
			$thumbnail_img = atbdp_get_image_source( $listing_img[0], $image_quality );
			$thumbnail_id = $listing_img[0];
		}

		if ( ! empty( $listing_prv_img ) ) {
			$thumbnail_img = atbdp_get_image_source( $listing_prv_img, $image_quality );
			$thumbnail_id = $listing_prv_img;
		}

		if ( ! empty( $img_src ) ) {
			$thumbnail_img = $img_src;
			$thumbnail_id = 0;
		}

		if ( empty( $thumbnail_img ) ) {
			$thumbnail_img = $default_image_src;
			$thumbnail_id = 0;
		}

		$image_src    = is_array($thumbnail_img) ? $thumbnail_img['url'] : $thumbnail_img;
		$image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
		$image_alt = ( ! empty( $image_alt ) ) ? esc_attr( $image_alt ) : esc_html( get_the_title( $thumbnail_id ) );
		$image_alt = ( ! empty( $image_alt ) ) ? $image_alt : esc_html( get_the_title() );

		return "<img src='$image_src' alt='$image_alt' />";
	}

	public function fav_listing_items() {
		$fav_listing_items = array();

		$fav_listings = ATBDP()->user->current_user_fav_listings(); //@cache @kowsar

		if ( $fav_listings->have_posts() ){
			foreach ( $fav_listings->posts as $post ) {
				$title         = ! empty( $post->post_title ) ? $post->post_title : __( 'Untitled', 'directorist' );
				$cats          = get_the_terms( $post->ID, ATBDP_CATEGORY );
				$category      = get_post_meta( $post->ID, '_admin_category_select', true );
				$category_name = ! empty( $cats ) ? $cats[0]->name : 'Uncategorized';
				$category_icon = ! empty( $cats ) ? esc_attr( get_cat_icon( $cats[0]->term_id ) ) : atbdp_icon_type() . '-tags';
				$mark_fav_html = atbdp_listings_mark_as_favourite( $post->ID );

				$icon_type     = substr( $category_icon, 0, 2 );
				$icon          = ( 'la' === $icon_type ) ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon;
				$category_link = ! empty( $cats ) ? esc_url( ATBDP_Permalink::atbdp_get_category_page( $cats[0] ) ) : '#';
				$post_link     = esc_url( get_post_permalink( $post->ID ) );

				$listing_img     = get_post_meta( $post->ID, '_listing_img', true );
				$listing_prv_img = get_post_meta( $post->ID, '_listing_prv_img', true );
				$crop_width      = get_directorist_option( 'crop_width', 360 );
				$crop_height     = get_directorist_option( 'crop_height', 300 );

				if ( ! empty( $listing_prv_img ) ) {
					$prv_image = atbdp_get_image_source( $listing_prv_img, 'large' );
				}
				if ( ! empty( $listing_img[0] ) ) {
					$gallery_img = atbdp_get_image_source( $listing_img[0], 'medium' );
				}

				if ( ! empty( $listing_prv_img ) ) {
					$img_src = $prv_image;

				}
				if ( ! empty( $listing_img[0] ) && empty( $listing_prv_img ) ) {
					$img_src = $gallery_img;

				}
				if ( empty( $listing_img[0] ) && empty( $listing_prv_img ) ) {
					$img_src = ATBDP_PUBLIC_ASSETS . 'images/grid.jpg';
				}

				$fav_listing_items[] = array(
					'obj'           => $post,
					'permalink'     => $post_link,
					'img_src'       => $img_src,
					'title'         => $title,
					'category_link' => $category_link,
					'category_name' => $category_name,
					'icon'          => $icon,
					'mark_fav_html' => $mark_fav_html,
				);
			}
		}

		return $fav_listing_items;
	}

	public function user_info( $type ) {
		$id = $this->id;
		$userdata = get_userdata( $id );
		$result = '';

		switch ( $type ) {
			case 'display_name':
			$result = $userdata->display_name;
			break;

			case 'username':
			$result = $userdata->user_login;
			break;

			case 'first_name':
			$result = $userdata->first_name;
			break;

			case 'last_name':
			$result = $userdata->last_name;
			break;

			case 'email':
			$result = $userdata->user_email;
			break;

			case 'phone':
			$result = get_user_meta( $id, 'atbdp_phone', true );
			break;

			case 'website':
			$result = $userdata->user_url;
			break;

			case 'address':
			$result = get_user_meta( $id, 'address', true );
			break;

			case 'facebook':
			$result = get_user_meta( $id, 'atbdp_facebook', true );
			break;

			case 'twitter':
			$result = get_user_meta( $id, 'atbdp_twitter', true );
			break;

			case 'linkedin':
			$result = get_user_meta( $id, 'atbdp_linkedin', true );
			break;

			case 'youtube':
			$result = get_user_meta( $id, 'atbdp_youtube', true );
			break;

			case 'bio':
			$result = get_user_meta( $id, 'description', true );
			break;
		}

		return $result;
	}

	public function dashboard_tabs() {
		// Tabs
		$dashboard_tabs = array();

		$my_listing_tab     = get_directorist_option( 'my_listing_tab', 1 );
		$my_profile_tab     = get_directorist_option( 'my_profile_tab', 1 );
		$fav_listings_tab   = get_directorist_option( 'fav_listings_tab', 1 );
		$announcement_tab 	= get_directorist_option( 'announcement_tab', 1 );

		if ( $my_listing_tab && ( 'general' != $this->user_type && 'become_author' != $this->user_type ) ) {
			$my_listing_tab_text = get_directorist_option( 'my_listing_tab_text', __( 'My Listing', 'directorist' ) );

			$listings   = $this->listings_query();
			$list_found = $listings->found_posts;

			$dashboard_tabs['dashboard_my_listings'] = array(
				'title'     => sprintf(__('%s (%s)', 'directorist'), $my_listing_tab_text, $list_found),
				'content'   => Helper::get_template_contents( 'dashboard/tab-my-listings', [ 'dashboard' => $this ] ),
				'icon'	    => atbdp_icon_type() . '-list',
			);
		}

		if ( $my_profile_tab ) {
			$dashboard_tabs['dashboard_profile'] = array(
				'title'     => get_directorist_option('my_profile_tab_text', __('My Profile', 'directorist')),
				'icon'	    => atbdp_icon_type() . '-user',
				'content'   => Helper::get_template_contents( 'dashboard/tab-profile', [ 'dashboard' => $this ] ),
			);
		}

		if ( $fav_listings_tab ) {
			$dashboard_tabs['dashboard_fav_listings'] = array(
				'title'     => get_directorist_option('fav_listings_tab_text', __('Favorite Listings', 'directorist')),
				'content'   => Helper::get_template_contents( 'dashboard/tab-fav-listings', [ 'dashboard' => $this ] ),
				'icon'		=> atbdp_icon_type() . '-heart-o',
			);
		}

		if ( $announcement_tab ) {
			$dashboard_tabs['dashboard_announcement'] = array(
				'title'    => $this->get_announcement_label(),
				'content'  => Helper::get_template_contents( 'dashboard/tab-announcement', [ 'dashboard' => $this ] ),
				'icon'	   => atbdp_icon_type() . '-bullhorn',
			);
		}

		return apply_filters( 'directorist_dashboard_tabs', $dashboard_tabs );
	}

	public function get_announcement_label() {
		$announcement_label = get_directorist_option( 'announcement_tab_text', __( 'Announcements', 'directorist' ) );
		$new_announcements  = ATBDP()->announcement->get_new_announcement_count();
		if ( $new_announcements > 0 ) {
			$announcement_label = $announcement_label . "<span class='directorist-announcement-count show'>{$new_announcements}</span>";
		}
		return apply_filters( 'directorist_announcement_label', $announcement_label );
	}

	public function get_announcements() {
		$announcements       = [];
		$announcements_query = \ATBDP()->announcement::get_announcement_query_data();
		$current_user_email  = get_the_author_meta( 'user_email', get_current_user_id() );

		foreach ( $announcements_query->posts as $announcement ) {
			$id = $announcement->ID;
			$recepents = get_post_meta( $id, '_recepents', true );

			if ( ! empty( $recepents ) && is_array( $recepents )  ) {
				if ( ! in_array( $current_user_email, $recepents ) ) {
					continue;
				}
			}

			$announcements[$id] = [
				'title'   => get_the_title( $id ),
				'content' => $announcement->post_content,
			];
		}

		return $announcements;
	}

	public function restrict_access_template() {
		$args = array(
			'dashboard'         => $this,
			'login_link'        => ATBDP_Permalink::get_login_page_link(),
			'registration_link' => ATBDP_Permalink::get_registration_page_link(),
		);
		return Helper::get_template_contents( 'dashboard/restrict-access', $args );
	}

	public function profile_pic_template() {
		Helper::get_template( 'dashboard/profile-pic', [ 'dashboard' => $this ] );
	}

	public function notice_template() {
		if ( isset($_GET['renew'] ) ) {
			$renew_token_expired = $_GET['renew'] == 'token_expired' ? true : false;
			$renew_succeed = $_GET['renew'] == 'success' ? true : false;
		}
		else {
			$renew_token_expired = $renew_succeed = false;
		}

		$args = array(
			'dashboard' => $this,
			'renew_token_expired' => $renew_token_expired,
			'renew_succeed' => $renew_succeed,
		);

		Helper::get_template( 'dashboard/notice', $args );
	}

	public function confirmation_text() {
		if( !isset( $_GET['notice'] ) ) {
			return '';
		}

		$edit_listing_status 	= get_directorist_option('edit_listing_status', 'pending' );
		$pending_msg 			= get_directorist_option('pending_confirmation_msg', __( 'Thank you for your submission. Your listing is being reviewed and it may take up to 24 hours to complete the review.', 'directorist' ) );
		$publish_msg 			= get_directorist_option('publish_confirmation_msg', __( 'Congratulations! Your listing has been approved/published. Now it is publicly available.', 'directorist' ) );
		$confirmation_msg = $edit_listing_status === 'publish' ? $publish_msg : $pending_msg;

		return $confirmation_msg;
	}

	public function navigation_template() {
		Helper::get_template( 'dashboard/navigation', [ 'dashboard' => $this ] );
	}

	public function main_contents_template() {
		Helper::get_template( 'dashboard/main-contents', [ 'dashboard' => $this ] );
	}

	public function nav_buttons_template() {
		Helper::get_template( 'dashboard/nav-buttons', [ 'dashboard' => $this ] );
	}

	public function user_can_submit() {
		$display_submit_btn = get_directorist_option( 'submit_listing_button', 1 );

		if ( $display_submit_btn && 'general' != $this->user_type && 'become_author' != $this->user_type ) {
			return true;
		}
		else {
			return false;
		}
	}

	public function listing_row_template() {
		$args = array(
			'dashboard' => $this,
			'query'     => $this->current_listings_query,
		);
		Helper::get_template( 'dashboard/listing-row', $args );
	}

	public function display_title() {
		return self::$display_title;
	}

	public function render_shortcode( $atts ) {
		$atts = shortcode_atts( ['show_title' => ''], $atts );
		self::$display_title = ( $atts['show_title'] == 'yes' ) ? true : false;

		$this->enqueue_scripts();

		if (!atbdp_logged_in_user()) {
			return $this->restrict_access_template();
		}

		ob_start();
		if ( ! empty( $atts['shortcode'] ) ) { Helper::add_shortcode_comment( $atts['shortcode'] ); }
		echo Helper::get_template_contents( 'dashboard-contents', [ 'dashboard' => $this ] );

		return ob_get_clean();
	}

	public function get_action_dropdown_item() {
		 $dropdown_items['delete'] = array(
			'class'			    => '',
			'data_attr'			=>	'data-task="delete"',
			'link'				=>	'#',
			'icon'				=>  sprintf( '<i class="%s-trash"></i>', atbdp_icon_type() ),
			'label'				=>  __( 'Delete Listing', 'directorist' )
		 );

		return apply_filters( 'directorist_dashboard_listing_action_items', $dropdown_items, $this );
	}
}