<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

$error_message = sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', 'directorist'),
	apply_filters('atbdp_user_dashboard_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Here', 'directorist') . "</a>"),
	apply_filters('atbdp_user_dashboard_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . "</a>")
);
?>
<section class="directory_wrapper single_area">
	<?php ATBDP()->helper->show_login_message($error_message); ?>
</section>