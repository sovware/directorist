<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-user-dashboard-access-notice">
	<div class="directorist-alert directorist-alert-warning">
		<?php directorist_icon( 'las la-info-circle' ); ?>
		<?php echo wp_kses_post( sprintf(__("You need to be logged in to view the content of this page. You can login <a href='%s'>Here</a>. Don't have an account? <a href='%s'>Sign Up</a>", 'directorist'), $login_link, $registration_link ) ); ?>
	</div>
</div>