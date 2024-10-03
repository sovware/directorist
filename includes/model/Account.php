<?php
/**
 * @author wpWax
 */

namespace Directorist;

use \ATBDP_Permalink;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Account {

	protected static $instance = null;

	private function __construct() {

	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function render_shortcode_login( $atts = [] ) {
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

		return Helper::get_template_contents( 'account/login', $args );
	}

	public function render_shortcode_registration( $atts ) {

		$new_user_registration = get_directorist_option( 'new_user_registration', true );
		if( ! $new_user_registration ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			$atts = shortcode_atts( array(
				'user_type'			  => '',
			), $atts );

			$user_type = ! empty( $atts['user_type'] ) ? $atts['user_type'] : '';
			$user_type = ! empty( $_REQUEST['user_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_type'] ) ) : $user_type;

			$args = array(
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
			);

			return Helper::get_template_contents( 'account/registration', $args );
		}
		else {
			$error_message = sprintf( __( 'Registration page is only for unregistered user. <a href="%s">Go to Dashboard</a>', 'directorist' ), esc_url( ATBDP_Permalink::get_dashboard_page_link() ) );
			ob_start();
			ATBDP()->helper->show_login_message( apply_filters( 'atbdp_registration_page_registered_msg', $error_message ) );
			return ob_get_clean();
		}

		return '';
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

		return Helper::get_template_contents( 'account/login', $args );
	}

}