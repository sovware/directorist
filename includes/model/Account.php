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

	public function render_account( $atts = [] ) {
		if ( is_user_logged_in() ) {
			$error_message = sprintf( __( 'The account page is only accessible to logged-out users.<a href="%s">Go to Dashboard</a>', 'directorist' ), esc_url( ATBDP_Permalink::get_dashboard_page_link() ) );
			ob_start();
			ATBDP()->helper->show_login_message( apply_filters( 'atbdp_registration_page_registered_msg', $error_message ) );
			return ob_get_clean();
		}

		$atts = shortcode_atts( array(
			'enable_user_type'					=> get_directorist_option( 'display_user_type', false ) ? 'yes' : 'no',
			'author_role_label'			  		=> __( 'I am an author', 'directorist' ),
			'user_role_label'			  		=> __( 'I am a user', 'directorist' ),
			'new_user_registration' 			=> get_directorist_option( 'new_user_registration', true ) ? 'yes' : 'no',
			'registration_username'				=> get_directorist_option( 'reg_username', __( 'Username', 'directorist' ) ),
			'enable_registration_password' 		=> get_directorist_option( 'display_password_reg', true ) ? 'yes' : 'no',
			'registration_password_label' 		=> get_directorist_option( 'reg_password', __( 'Password', 'directorist' ) ),
			'registration_password_required' 	=> get_directorist_option( 'require_password_reg', true ) ? 'yes' : 'no',
			'registration_email_label' 			=> get_directorist_option( 'reg_email', __( 'Email ', 'directorist' ) ),
			'enable_registration_website' 		=> get_directorist_option( 'display_website_reg', false ) ? 'yes' : 'no',
			'registration_website_label' 		=> get_directorist_option( 'reg_website', __( 'Website', 'directorist' ) ),
			'registration_website_required' 	=> get_directorist_option( 'require_website_reg', false ) ? 'yes' : 'no',
			'enable_registration_first_name' 	=> get_directorist_option( 'display_fname_reg', false ) ? 'yes' : 'no',
			'registration_first_name_label' 	=> get_directorist_option( 'reg_fname', __( 'First Name', 'directorist' ) ),
			'registration_first_name_required' 	=> get_directorist_option( 'require_fname_reg', false ) ? 'yes' : 'no',
			'enable_registration_last_name' 	=> get_directorist_option( 'display_lname_reg', false ) ? 'yes' : 'no',
			'registration_last_name_label' 		=> get_directorist_option( 'reg_lname', __( 'Last Name', 'directorist' ) ),
			'registration_last_name_required' 	=> get_directorist_option( 'require_lname_reg', false ) ? 'yes' : 'no',
			'enable_registration_bio'          	=> get_directorist_option( 'display_bio_reg', 0 ) ? 'yes' : 'no',
			'registration_bio_label'            => get_directorist_option( 'reg_bio', __( 'About/bio', 'directorist' ) ),
			'registration_bio_required'        	=> get_directorist_option( 'require_bio_reg', 0 ) ? 'yes' : 'no',
			'enable_registration_privacy'       => get_directorist_option( 'registration_privacy', 1 ) ? 'yes' : 'no',
			'registration_privacy_label'		=> get_directorist_option( 'registration_privacy_label', __( 'I agree to the', 'directorist' ) ),
			'registration_privacy_linking_text'	=> get_directorist_option( 'registration_privacy_label_link', __('Privacy & Policy', 'directorist') ),
			'enable_registration_terms'         => get_directorist_option( 'regi_terms_condition', 1 ) ? 'yes' : 'no',
			'registration_terms_label'			=> get_directorist_option( 'regi_terms_label', __( 'I agree with all', 'directorist' ) ),
			'registration_terms_linking_text'   => get_directorist_option( 'regi_terms_label_link', '' ),
			'registration_signup_label'			=> get_directorist_option( 'reg_signup', __( 'Sign Up', 'directorist' ) ),
			'enable_registration_login_message'	=> get_directorist_option( 'display_login', 1 ) ? 'yes' : 'no',
			'registration_login_message'		=> get_directorist_option( 'login_text', __( 'Already have an account? Please login', 'directorist' ) ),
			'registration_login_linking_text' 	=> get_directorist_option( 'log_linkingmsg', __( 'Here', 'directorist' ) ),
			'auto_login_after_registration'	  	=> get_directorist_option( 'auto_login', 0 ) ? 'yes' : 'no',
			'redirection_after_registration'	=> '',
			// login atts
			'login_username_label' 				=> get_directorist_option( 'log_username', __( 'Username or Email Address', 'directorist' ) ),
			'login_password_label' 				=> get_directorist_option( 'log_password', __( 'Password', 'directorist' ) ),
			'enable_login_remember_me' 			=> get_directorist_option( 'display_rememberme', 1 ) ? 'yes' : 'no',
			'login_remember_me_label' 			=> get_directorist_option( 'log_rememberme', __( 'Remember Me', 'directorist' ) ),
			'login_button_label'				=> get_directorist_option( 'log_button', __( 'Log In', 'directorist' ) ),
			'enable_login_signup' 				=> get_directorist_option( 'display_signup', 1 ) ? 'yes' : 'no',
			'login_signup_label' 				=> get_directorist_option( 'reg_text', __( "Don't have an account?", 'directorist' ) ),
			'login_signup_linking_text' 		=> get_directorist_option( 'reg_linktxt', __( 'Sign Up', 'directorist' ) ),
			// recover password atts
			'enable_recovery_password' 			=> get_directorist_option( 'display_recpass', 1 ) ? 'yes' : 'no',
			'recovery_password_label'  			=> get_directorist_option( 'recpass_text', __( 'Forgot Password?', 'directorist' ) ),
			'recovery_password_description'  	=> get_directorist_option( 'recpass_desc', __( 'Lost your password? Please enter your email address. You will receive a link to create a new password via email.', 'directorist' ) ),
			'recovery_password_email_label'  	=> get_directorist_option( 'recpass_username', __( 'E-mail:', 'directorist' ) ),
			'recovery_password_email_placeholder'  => get_directorist_option( 'recpass_placeholder', __( 'eg. mail@example.com', 'directorist' ) ),
			'recovery_password_button_label'  	=> get_directorist_option( 'recpass_button', __( 'Get New Password', 'directorist' ) ),
		), $atts );

		$user_type = ! empty( $atts['user_type'] ) ? $atts['user_type'] : '';
		$user_type = ! empty( $_REQUEST['user_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_type'] ) ) : $user_type;

		$data        = [
			'enable_user_type'					=> $atts['enable_user_type'],
			'user_type' 						=> $user_type,
			'new_user_registration' 			=> $atts['new_user_registration'],
			'enable_registration_password' 		=> $atts['enable_registration_password'],
			'registration_password_required' 	=> $atts['registration_password_required'],
			'registration_username' 			=> $atts['registration_username'],
			'enable_registration_website' 		=> $atts['enable_registration_website'],
			'registration_website_required' 	=> $atts['registration_website_required'],
			'enable_registration_first_name' 	=> $atts['enable_registration_first_name'],
			'registration_first_name_required' 	=> $atts['registration_first_name_required'],
			'enable_registration_last_name' 	=> $atts['enable_registration_last_name'],
			'registration_last_name_required' 	=> $atts['registration_last_name_required'],
			'enable_registration_bio' 			=> $atts['enable_registration_bio'],
			'registration_bio_required' 		=> $atts['registration_bio_required'],
			'enable_registration_privacy'		=> $atts['enable_registration_privacy'],
			'enable_registration_terms'			=> $atts['enable_registration_terms'],
			'auto_login_after_registration'		=> $atts['auto_login_after_registration'],
			'redirection_after_registration'	=> $atts['redirection_after_registration'],
		];
		wp_localize_script( 'jquery', 'account_object', $data );


		$args = [
			'log_username'        => $atts['login_username_label'],
			'log_password'        => $atts['login_password_label'],
			'display_rememberMe'  => $atts['enable_login_remember_me'],
			'log_rememberMe'      => $atts['login_remember_me_label'],
			'log_button'          => $atts['login_button_label'],
			'display_recpass'     => $atts['enable_recovery_password'],
			'recpass_text'        => $atts['recovery_password_label'],
			'recpass_desc'        => $atts['recovery_password_description'],
			'recpass_username'    => $atts['recovery_password_email_label'],
			'recpass_placeholder' => $atts['recovery_password_email_placeholder'],
			'recpass_button'      => $atts['recovery_password_button_label'],
			'reg_text'            => $atts['login_signup_label'],
			'reg_url'             => ATBDP_Permalink::get_registration_page_link(),
			'reg_linktxt'         => $atts['login_signup_linking_text'],
			'display_signup'      => $atts['enable_login_signup'],
			'new_user_registration' => $atts['new_user_registration'],
			'parent'               => 0,
			'container_fluid'      => is_directoria_active() ? 'container' : 'container-fluid',
			'username'             => $atts['registration_username'],
			'password'             => $atts['registration_password_label'],
			'display_password_reg' => $atts['enable_registration_password'],
			'require_password'     => $atts['registration_password_required'],
			'email'                => $atts['registration_email_label'],
			'display_website'      => $atts['enable_registration_website'],
			'website'              => $atts['registration_website_label'],
			'require_website'      => $atts['registration_website_required'],
			'display_fname'        => $atts['enable_registration_first_name'],
			'first_name'           => $atts['registration_first_name_label'],
			'require_fname'        => $atts['registration_first_name_required'],
			'display_lname'        => $atts['enable_registration_last_name'],
			'last_name'            => $atts['registration_last_name_label'],
			'require_lname'        => $atts['registration_last_name_required'],
			'display_bio'          => $atts['enable_registration_bio'],
			'bio'                  => $atts['registration_bio_label'],
			'require_bio'          => $atts['registration_bio_required'],
			'reg_signup'           => $atts['registration_signup_label'],
			'display_login'        => $atts['enable_registration_login_message'],
			'login_text'           => $atts['registration_login_message'],
			'login_url'            => ATBDP_Permalink::get_login_page_link(),
			'log_linkingmsg'       => $atts['registration_login_linking_text'],
			'enable_registration_terms' => $atts['enable_registration_terms'],
			'terms_label'          => $atts['registration_terms_label'],
			'terms_label_link'     => $atts['registration_terms_linking_text'],
			't_C_page_link'        => ATBDP_Permalink::get_terms_and_conditions_page_url(),
			'privacy_page_link'    => ATBDP_Permalink::get_privacy_policy_page_url(),
			'registration_privacy' => $atts['enable_registration_privacy'],
			'privacy_label'        => $atts['registration_privacy_label'],
			'privacy_label_link'   => $atts['registration_privacy_linking_text'],
			'user_type'			   => $user_type,
			'author_checked'	   => ( 'general' != $user_type ) ? 'checked' : '',
			'general_checked'	   => ( 'general' == $user_type ) ? 'checked' : '',
			'enable_user_type'	   => $atts['enable_user_type'],
			'author_role_label'	   => $atts['author_role_label'],
			'user_role_label'	   => $atts['user_role_label'],
		];

		return Helper::get_template_contents( 'account/login-registration-form', $args );
	}

}