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

	public function render( $atts = [] ) {
		if ( is_user_logged_in() ) {
			$error_message = sprintf( __( 'The account page is only accessible to logged-out users.<a href="%s">Go to Dashboard</a>', 'directorist' ), esc_url( ATBDP_Permalink::get_dashboard_page_link() ) );
			ob_start();
			ATBDP()->helper->show_login_message( apply_filters( 'atbdp_registration_page_registered_msg', $error_message ) );
			return ob_get_clean();
		}

		$atts = shortcode_atts( array(
			'active_form'          => 'signin',
			'user_role'            => get_directorist_option( 'display_user_type', false ) ? 'yes' : 'no',
			'author_role_label'    => __( 'I am an author', 'directorist' ),
			'user_role_label'      => __( 'I am a user', 'directorist' ),
			'username_label'       => get_directorist_option( 'reg_username', __( 'Username', 'directorist' ) ),
			'password'             => get_directorist_option( 'display_password_reg', true ) ? 'yes' : 'no',
			'password_label'       => get_directorist_option( 'reg_password', __( 'Password', 'directorist' ) ),
			'email_label'          => get_directorist_option( 'reg_email', __( 'Email ', 'directorist' ) ),
			'website'              => get_directorist_option( 'display_website_reg', false ) ? 'yes' : 'no',
			'website_label'        => get_directorist_option( 'reg_website', __( 'Website', 'directorist' ) ),
			'website_required'     => get_directorist_option( 'require_website_reg', false ) ? 'yes' : 'no',
			'firstname'            => get_directorist_option( 'display_fname_reg', false ) ? 'yes' : 'no',
			'firstname_label'      => get_directorist_option( 'reg_fname', __( 'First Name', 'directorist' ) ),
			'firstname_required'   => get_directorist_option( 'require_fname_reg', false ) ? 'yes' : 'no',
			'lastname'             => get_directorist_option( 'display_lname_reg', false ) ? 'yes' : 'no',
			'lastname_label'       => get_directorist_option( 'reg_lname', __( 'Last Name', 'directorist' ) ),
			'lastname_required'    => get_directorist_option( 'require_lname_reg', false ) ? 'yes' : 'no',
			'bio'                  => get_directorist_option( 'display_bio_reg', 0 ) ? 'yes' : 'no',
			'bio_label'            => get_directorist_option( 'reg_bio', __( 'About/bio', 'directorist' ) ),
			'bio_required'         => get_directorist_option( 'require_bio_reg', 0 ) ? 'yes' : 'no',
			'privacy'              => get_directorist_option( 'registration_privacy', 1 ) ? 'yes' : 'no',
			'privacy_label'        => get_directorist_option( 'registration_privacy_label', __( 'I agree to the', 'directorist' ) ),
			'privacy_linking_text' => get_directorist_option( 'registration_privacy_label_link', __('Privacy & Policy', 'directorist') ),
			'terms'                => get_directorist_option( 'regi_terms_condition', 1 ) ? 'yes' : 'no',
			'terms_label'          => get_directorist_option( 'regi_terms_label', __( 'I agree with all', 'directorist' ) ),
			'terms_linking_text'   => get_directorist_option( 'regi_terms_label_link', 'terms & conditions' ),
			'signup_button_label'  => get_directorist_option( 'reg_signup', __( 'Sign Up', 'directorist' ) ),
			'signin_message'       => get_directorist_option( 'login_text', __( 'Already have an account? Please Sign in', 'directorist' ) ),
			'signin_linking_text'  => get_directorist_option( 'log_linkingmsg', __( 'Here', 'directorist' ) ),
			'signin_after_signup'  => get_directorist_option( 'auto_login', 0 ) ? 'yes' : 'no',
			'signup_redirect_url'  => '',
			  // login atts
			'signin_username_label' => get_directorist_option( 'log_username', __( 'Username or Email Address', 'directorist' ) ),
			'signin_button_label'   => get_directorist_option( 'log_button', __( 'Sign In', 'directorist' ) ),
			'signup_label'          => get_directorist_option( 'reg_text', __( "Don't have an account?", 'directorist' ) ),
			'signup_linking_text'   => get_directorist_option( 'reg_linktxt', __( 'Sign Up', 'directorist' ) ),
			  // recover password atts
			'enable_recovery_password'            => get_directorist_option( 'display_recpass', 1 ) ? 'yes' : 'no',
			'recovery_password_label'             => get_directorist_option( 'recpass_text', __( 'Forgot Password?', 'directorist' ) ),
			'recovery_password_description'       => get_directorist_option( 'recpass_desc', __( 'Lost your password? Please enter your email address. You will receive a link to create a new password via email.', 'directorist' ) ),
			'recovery_password_email_label'       => get_directorist_option( 'recpass_username', __( 'E-mail:', 'directorist' ) ),
			'recovery_password_email_placeholder' => get_directorist_option( 'recpass_placeholder', __( 'eg. mail@example.com', 'directorist' ) ),
			'recovery_password_button_label'      => get_directorist_option( 'recpass_button', __( 'Get New Password', 'directorist' ) ),
			'user_type'                           => ''
		), $atts );

		$user_type = ! empty( $_REQUEST['user_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_type'] ) ) : $atts['user_type'];
		$active_form = ( isset( $_GET['signup'] ) && directorist_is_user_registration_enabled() ) ? 'signup' : $atts['active_form'];

		$data = [
			'enable_user_type'                 => $atts['user_role'],
			'user_type'                        => $user_type,
			'enable_registration_password'     => $atts['password'],
			'registration_username'            => $atts['username_label'],
			'enable_registration_website'      => $atts['website'],
			'registration_website_required'    => $atts['website_required'],
			'enable_registration_first_name'   => $atts['firstname'],
			'registration_first_name_required' => $atts['firstname_required'],
			'enable_registration_last_name'    => $atts['lastname'],
			'registration_last_name_required'  => $atts['lastname_required'],
			'enable_registration_bio'          => $atts['bio'],
			'registration_bio_required'        => $atts['bio_required'],
			'enable_registration_privacy'      => $atts['privacy'],
			'enable_registration_terms'        => $atts['terms'],
			'auto_login_after_registration'    => $atts['signin_after_signup'],
			'redirection_after_registration'   => $atts['signup_redirect_url'],
			'active_form'                      => $active_form,
		];
		wp_localize_script( 'directorist-account', 'directorist_signin_signup_params', $data );
		wp_localize_script( 'jquery', 'directorist_signin_signup_params', $data );

		$args = [
			'log_username'              => $atts['signin_username_label'],
			'log_password'              => $atts['password_label'],
			'log_button'                => $atts['signin_button_label'],
			'display_recpass'           => $atts['enable_recovery_password'],
			'recpass_text'              => $atts['recovery_password_label'],
			'recpass_desc'              => $atts['recovery_password_description'],
			'recpass_username'          => $atts['recovery_password_email_label'],
			'recpass_placeholder'       => $atts['recovery_password_email_placeholder'],
			'recpass_button'            => $atts['recovery_password_button_label'],
			'reg_text'                  => $atts['signup_label'],
			'reg_url'                   => ATBDP_Permalink::get_registration_page_link(),
			'reg_linktxt'               => $atts['signup_linking_text'],
			'new_user_registration'     => directorist_is_user_registration_enabled(),
			'parent'                    => 0,
			'container_fluid'           => is_directoria_active() ? 'container' : 'container-fluid',
			'username'                  => $atts['username_label'],
			'password'                  => $atts['password_label'],
			'display_password_reg'      => $atts['password'],
			'email'                     => $atts['email_label'],
			'display_website'           => $atts['website'],
			'website'                   => $atts['website_label'],
			'require_website'           => $atts['website_required'],
			'display_fname'             => $atts['firstname'],
			'first_name'                => $atts['firstname_label'],
			'require_fname'             => $atts['firstname_required'],
			'display_lname'             => $atts['lastname'],
			'last_name'                 => $atts['lastname_label'],
			'require_lname'             => $atts['lastname_required'],
			'display_bio'               => $atts['bio'],
			'bio'                       => $atts['bio_label'],
			'require_bio'               => $atts['bio_required'],
			'reg_signup'                => $atts['signup_button_label'],
			'login_text'                => $atts['signin_message'],
			'login_url'                 => ATBDP_Permalink::get_login_page_link(),
			'log_linkingmsg'            => $atts['signin_linking_text'],
			'enable_registration_terms' => $atts['terms'],
			'terms_label'               => $atts['terms_label'],
			'terms_label_link'          => $atts['terms_linking_text'],
			't_C_page_link'             => ATBDP_Permalink::get_terms_and_conditions_page_url(),
			'privacy_page_link'         => ATBDP_Permalink::get_privacy_policy_page_url(),
			'registration_privacy'      => $atts['privacy'],
			'privacy_label'             => $atts['privacy_label'],
			'privacy_label_link'        => $atts['privacy_linking_text'],
			'user_type'                 => $user_type,
			'author_checked'            => ( 'general' === $user_type ) ? 'checked' : '',
			'general_checked'           => ( 'general' === $user_type ) ? 'checked' : '',
			'enable_user_type'          => $atts['user_role'],
			'author_role_label'         => $atts['author_role_label'],
			'user_role_label'           => $atts['user_role_label'],
			'active_form'               => $active_form,
		];

		return Helper::get_template_contents( 'account/login-registration-form', $args );
	}
}