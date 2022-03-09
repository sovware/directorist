<?php
/**
 * @author  wpWax
 * @since   7.2
 * @version 7.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', 'directorist'), apply_filters('atbdp_listing_form_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Here', 'directorist') . '</a>'), apply_filters('atbdp_listing_form_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . '</a>'));
?>

<section class="directory_wrapper single_area">
	<div class="notice_wrapper">
		<div class="directorist-alert directorist-alert-warning">
			<span class="fa fa-info-circle" aria-hidden="true"></span> <?php echo $error_message; ?>
		</div>
	</div>
</section>