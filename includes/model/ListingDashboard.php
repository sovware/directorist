<?php
/**
 * @author wpWax
 */

namespace Directorist;

use ATBDP_Permalink;
use Directorist\database\DB;

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
		check_ajax_referer( directorist_get_nonce_key() );

		$data     = array_filter( $_POST, 'sanitize_text_field' ); // sanitization
		$type     = $data['tab'];
		$paged    = $data['paged'];
		$search   = !empty( $data['search'] ) ? $data['search'] : '';
		$task     = !empty( $data['task'] ) ? $data['task'] : '';
		$taskdata = !empty( $data['taskdata'] ) ? $data['taskdata'] : '';

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
		if ( $task === 'delete' ) {
			if ( current_user_can( get_post_type_object( ATBDP_POST_TYPE )->cap->delete_post, $taskdata ) )  {
				wp_delete_post( $taskdata );

				do_action( 'directorist_listing_deleted', $taskdata );
			}
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

		// TODO: Status has been migrated, remove related code.
		// if ( $type === 'publish' ) {
		// 	$args['post_status'] = $type;
		// }
		// if ( $type == 'pending' ) {
		// 	$args['post_status'] = 'pending';
		// }
		// elseif ( $type == 'expired' ) {
		// 	$args['meta_query'] = array(
		// 		array(
		// 			'key'   => '_listing_status',
		// 			'value' => 'expired'
		// 		),
		// 	);
		// }

		if ( $type === 'pending' || $type === 'expired' ) {
			$args['post_status'] = $type;
		} else {
			$args['post_status'] = 'publish';
		}

		if ( $search ) {
			$args['s'] = $search;
		}

		$this->current_listings_query = new \WP_Query( apply_filters( 'directorist_dashboard_query_arguments', $args ) );

		return $this->current_listings_query;
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
		// TODO: Status has been migrated, remove related code.
		// $id = get_the_ID();
		// $date_format = get_option('date_format');
		// $exp_date  = get_post_meta($id, '_expiry_date', true);
		// $never_exp = get_post_meta($id, '_never_expire', true);
		// $status    = get_post_meta($id, '_listing_status', true);
		// $exp_text  = !empty($never_exp) ? __('Never Expires', 'directorist') : date_i18n($date_format, strtotime($exp_date));
		// $exp_html  = ( $status == 'expired' ) ? '<span style="color: red">' . __('Expired', 'directorist') . '</span>' : $exp_text;
		// return $exp_html;

		if ( get_post_status( get_the_ID() ) === 'expired' ) {
			return '<span style="color: red">' . esc_html__( 'Expired', 'directorist' ) . '</span>';
		}

		$never_expire = (bool) get_post_meta( get_the_ID(), '_never_expire', true );
		if ( $never_expire ) {
			return '<span>' . esc_html__( 'Never Expires', 'directorist' ) . '</span>';
		}

		$expiry_date  = strtotime( get_post_meta( get_the_ID(), '_expiry_date', true ) );
		if ( $expiry_date ) {
			return '<span>' . date_i18n( get_option( 'date_format' ), $expiry_date ) . '</span>';
		}

		return '';
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
			'prev_text' => directorist_icon( 'las la-arrow-left', false ),
			'next_text' => directorist_icon( 'las la-arrow-right', false ),
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
		$image_quality     = get_directorist_option('preview_image_quality', 'directorist_preview');
		$listing_prv_img   = get_post_meta($id, '_listing_prv_img', true);
		$listing_img       = get_post_meta($id, '_listing_img', true);

		if ( is_array( $listing_img ) && ! empty( $listing_img[0] ) ) {
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

		$fav_listings = DB::favorite_listings_query();

		if ( $fav_listings->have_posts() ){
			foreach ( $fav_listings->posts as $post ) {
				$listing_type  = get_post_meta( $post->ID, '_directory_type', true );
				$title         = ! empty( $post->post_title ) ? $post->post_title : __( 'Untitled', 'directorist' );
				$cats          = get_the_terms( $post->ID, ATBDP_CATEGORY );
				$category      = get_post_meta( $post->ID, '_admin_category_select', true );
				$category_name = ! empty( $cats ) ? $cats[0]->name : 'Uncategorized';
				$mark_fav_html = atbdp_listings_mark_as_favourite( $post->ID );


				if (!empty($cats)) {
					$cat_icon = get_cat_icon($cats[0]->term_id);
				}
				$cat_icon = !empty($cat_icon) ? $cat_icon : 'las la-tags';
				$icon = directorist_icon( $cat_icon, false );

				$category_link = ! empty( $cats ) ? esc_url( ATBDP_Permalink::atbdp_get_category_page( $cats[0] ) ) : '#';
				$post_link     = esc_url( get_post_permalink( $post->ID ) );

				$listing_img     	= get_post_meta( $post->ID, '_listing_img', true );
				$listing_prv_img 	= get_post_meta( $post->ID, '_listing_prv_img', true );
				$default_image_src 	= Helper::default_preview_image_src( $listing_type );
				$crop_width      	= get_directorist_option( 'crop_width', 360 );
				$crop_height     	= get_directorist_option( 'crop_height', 300 );

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
					$img_src = $default_image_src;
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
				'icon'	    => 'las la-list',
			);
		}

		if ( $my_profile_tab ) {
			$dashboard_tabs['dashboard_profile'] = array(
				'title'     => get_directorist_option('my_profile_tab_text', __('My Profile', 'directorist')),
				'icon'	    => 'las la-user',
				'content'   => Helper::get_template_contents( 'dashboard/tab-profile', [ 'dashboard' => $this ] ),
			);
		}

		if ( $fav_listings_tab ) {
			$dashboard_tabs['dashboard_fav_listings'] = array(
				'title'     => get_directorist_option('fav_listings_tab_text', __('Favorite Listings', 'directorist')),
				'content'   => Helper::get_template_contents( 'dashboard/tab-fav-listings', [ 'dashboard' => $this ] ),
				'icon'		=> 'las la-heart',
			);
		}

		if ( $announcement_tab ) {
			$dashboard_tabs['dashboard_announcement'] = array(
				'title'    => $this->get_announcement_label(),
				'content'  => Helper::get_template_contents( 'dashboard/tab-announcement', [ 'dashboard' => $this ] ),
				'icon'	   => 'las la-bullhorn',
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
			$recepents = ! empty( $recepents ) ? explode( ',', $recepents ) : [];

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
		if ( ! isset( $_GET['notice'] ) ) {
			return;
		}

		$listing_id = isset( $_GET['listing_id'] ) ? absint( $_GET['listing_id'] ) : 0;
		if ( $listing_id && ! directorist_is_listing_post_type( $listing_id ) ) {
			return;
		}

		if ( get_post_status( $listing_id ) === 'publish' ) {
			$message = get_directorist_option(
				'publish_confirmation_msg',
				__( 'Congratulations! Your listing has been approved/published. Now it is publicly available.', 'directorist' )
			);
		} else {
			$message = get_directorist_option(
				'pending_confirmation_msg',
				__( 'Thank you for your submission. Your listing is being reviewed and it may take up to 24 hours to complete the review.', 'directorist' )
			);
		}

		return $message;
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

		if (!is_user_logged_in()) {
			return $this->restrict_access_template();
		}

		return Helper::get_template_contents( 'dashboard-contents', [ 'dashboard' => $this ] );
	}

	public function render_shortcode_login_registration( $atts = [] ) {
		if ( is_user_logged_in() ) {

			do_action( 'atbdp_show_flush_messages' );

			$error_message = sprintf( __( 'Login page is not for logged-in user. <a href="%s">Go to Dashboard</a>', 'directorist' ), esc_url( ATBDP_Permalink::get_dashboard_page_link() ) );
			ob_start();
			ATBDP()->helper->show_login_message( apply_filters( 'atbdp_login_page_loggedIn_msg', $error_message ) );
			return ob_get_clean();
		}

		$redirection = ATBDP_Permalink::get_login_redirection_page_link();
		$data        = [
			'ajax_url'            => admin_url( 'admin-ajax.php' ),
			'redirect_url'        => $redirection ? $redirection : ATBDP_Permalink::get_dashboard_page_link(),
			'loading_message'     => esc_html__( 'Sending user info, please wait...', 'directorist' ),
			'login_error_message' => esc_html__( 'Wrong username or password.', 'directorist' ),
		];
		wp_localize_script( 'directorist-main-script', 'ajax_login_object', $data );

		$atts = shortcode_atts( array(
			'user_type'			  => '',
		), $atts );

		$user_type = ! empty( $atts['user_type'] ) ? $atts['user_type'] : '';
		$user_type = ! empty( $_REQUEST['user_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_type'] ) ) : $user_type;

		$args = [
			'log_username'        => get_directorist_option( 'log_username', __( 'Username or Email Address', 'directorist' ) ),
			'log_password'        => get_directorist_option( 'log_password', __( 'Password', 'directorist' ) ),
			'display_rememberMe'  => get_directorist_option( 'display_rememberme', 1 ),
			'log_rememberMe'      => get_directorist_option( 'log_rememberme', __( 'Remember Me', 'directorist' ) ),
			'log_button'          => get_directorist_option( 'log_button', __( 'Log In', 'directorist' ) ),
			'display_recpass'     => get_directorist_option( 'display_recpass', 1 ),
			'recpass_text'        => get_directorist_option( 'recpass_text', __( 'Recover Password', 'directorist' ) ),
			'recpass_desc'        => get_directorist_option( 'recpass_desc', __( 'Lost your password? Please enter your email address. You will receive a link to create a new password via email.', 'directorist' ) ),
			'recpass_username'    => get_directorist_option( 'recpass_username', __( 'E-mail:', 'directorist' ) ),
			'recpass_placeholder' => get_directorist_option( 'recpass_placeholder', __( 'eg. mail@example.com', 'directorist' ) ),
			'recpass_button'      => get_directorist_option( 'recpass_button', __( 'Get New Password', 'directorist' ) ),
			'reg_text'            => get_directorist_option( 'reg_text', __( "Don't have an account?", 'directorist' ) ),
			'reg_url'             => ATBDP_Permalink::get_registration_page_link(),
			'reg_linktxt'         => get_directorist_option( 'reg_linktxt', __( 'Sign Up', 'directorist' ) ),
			'display_signup'      => get_directorist_option( 'display_signup', 1 ),
			'new_user_registration' => get_directorist_option( 'new_user_registration', true ),
			'parent'               => 0,
			'container_fluid'      => is_directoria_active() ? 'container' : 'container-fluid',
			'username'             => get_directorist_option( 'reg_username', __( 'Username', 'directorist' ) ),
			'password'             => get_directorist_option( 'reg_password', __( 'Password', 'directorist' ) ),
			'display_password_reg' => get_directorist_option( 'display_password_reg', 1 ),
			'require_password'     => get_directorist_option( 'require_password_reg', 1 ),
			'email'                => get_directorist_option( 'reg_email', __( 'Email', 'directorist' ) ),
			'display_website'      => get_directorist_option( 'display_website_reg', 0 ),
			'website'              => get_directorist_option( 'reg_website', __( 'Website', 'directorist' ) ),
			'require_website'      => get_directorist_option( 'require_website_reg', 0 ),
			'display_fname'        => get_directorist_option( 'display_fname_reg', 0 ),
			'first_name'           => get_directorist_option( 'reg_fname', __( 'First Name', 'directorist' ) ),
			'require_fname'        => get_directorist_option( 'require_fname_reg', 0 ),
			'display_lname'        => get_directorist_option( 'display_lname_reg', 0 ),
			'last_name'            => get_directorist_option( 'reg_lname', __( 'Last Name', 'directorist' ) ),
			'require_lname'        => get_directorist_option( 'require_lname_reg', 0 ),
			'display_bio'          => get_directorist_option( 'display_bio_reg', 0 ),
			'bio'                  => get_directorist_option( 'reg_bio', __( 'About/bio', 'directorist' ) ),
			'require_bio'          => get_directorist_option( 'require_bio_reg', 0 ),
			'reg_signup'           => get_directorist_option( 'reg_signup', __( 'Sign Up', 'directorist' ) ),
			'display_login'        => get_directorist_option( 'display_login', 1 ),
			'login_text'           => get_directorist_option( 'login_text', __( 'Already have an account? Please login', 'directorist' ) ),
			'login_url'            => ATBDP_Permalink::get_login_page_link(),
			'log_linkingmsg'       => get_directorist_option( 'log_linkingmsg', __( 'here', 'directorist' ) ),
			'terms_label'          => get_directorist_option( 'regi_terms_label', __( 'I agree with all', 'directorist' ) ),
			'terms_label_link'     => get_directorist_option( 'regi_terms_label_link', __( 'terms & conditions', 'directorist' ) ),
			't_C_page_link'        => ATBDP_Permalink::get_terms_and_conditions_page_url(),
			'privacy_page_link'    => ATBDP_Permalink::get_privacy_policy_page_url(),
			'privacy_label'        => get_directorist_option( 'registration_privacy_label', __( 'I agree to the', 'directorist' ) ),
			'privacy_label_link'   => get_directorist_option( 'registration_privacy_label_link', __( 'Privacy & Policy', 'directorist' ) ),
			'user_type'			   => $user_type,
			'author_checked'	   => ( 'general' != $user_type ) ? 'checked' : '',
			'general_checked'	   => ( 'general' == $user_type ) ? 'checked' : '',
		];

		return Helper::get_template_contents( 'account/login-registration-form', $args );
	}

	public function can_renew() {
		// TODO: Status has been migrated, remove related code.
		// $post_id = get_the_ID();
		// $status  = get_post_meta( $post_id, '_listing_status', true );

		// if ( 'renewal' == $status || 'expired' == $status ) {
		// 	$can_renew = get_directorist_option( 'can_renew_listing' );
		// 	if ( $can_renew ) {
		// 		return true;
		// 	}
		// }

		if ( ! directorist_can_user_renew_listings() ) {
			return false;
		}

		$status = get_post_status( get_the_ID() );

		if ( $status !== 'expired' || ( $status === 'publish' && get_post_meta( get_the_ID(), '_listing_status', true ) !== 'renewal' ) ) {
			return false;
		}

		return true;
	}

	public function can_promote() {
		// TODO: Status has been migrated, remove related code.
		// $post_id = get_the_ID();
		// $status  = get_post_meta( $post_id, '_listing_status', true );
		// $featured_active = get_directorist_option( 'enable_featured_listing' );
		// $featured = get_post_meta( $post_id, '_featured', true );

		// if ( 'renewal' == $status || 'expired' == $status ) {
		// 	return false;
		// }

		if ( ! directorist_is_featured_listing_enabled() ) {
			return false;
		}

		$status = get_post_status( get_the_ID() );

		if ( $status === 'expired' || ( $status === 'publish' && get_post_meta( get_the_ID(), '_listing_status', true ) === 'renewal' ) ) {
			return false;
		}

		$is_featured = (bool) get_post_meta( get_the_ID(), '_featured', true );
		if ( $is_featured ) {
			return false;
		}

		return true;
	}

	public function get_renewal_link( $listing_id ) {
		return directorist_is_monetization_enabled() && directorist_is_featured_listing_enabled() ? ATBDP_Permalink::get_fee_renewal_checkout_page_link( $listing_id ) : ATBDP_Permalink::get_renewal_page_link( $listing_id );
	}

	public function get_action_dropdown_item() {
		$dropdown_items = apply_filters( 'directorist_dashboard_listing_action_items', [], $this );

		$post_id = get_the_ID();

		if ( $this->can_renew() ) {
			$dropdown_items['renew'] = array(
				'class'			    => '',
				'data_attr'			=>	'',
				'link'				=>	add_query_arg( 'renew_from', 'dashboard', esc_url( $this->get_renewal_link( $post_id ) ) ),
				'icon'				=>  directorist_icon( 'las la-hand-holding-usd', false ),
				'label'				=>  __( 'Renew', 'directorist' )
			);
		}

		if ( $this->can_promote() ) {
			$dropdown_items['promote'] = array(
				'class'			    => '',
				'data_attr'			=>	'',
				'link'				=>	ATBDP_Permalink::get_checkout_page_link( $post_id ),
				'icon'				=>  directorist_icon( 'las la-ad', false ),
				'label'				=>  __( 'Promote', 'directorist' )
			);
		}

		$dropdown_items['delete'] = array(
			'class'			    => '',
			'data_attr'			=>	'data-task="delete"',
			'link'				=>	'#',
			'icon'				=>  directorist_icon( 'las la-trash', false ),
			'label'				=>  __( 'Delete Listing', 'directorist' )
		);

		return apply_filters( 'directorist_dashboard_listing_action_items_end', $dropdown_items, $this );
	}
}