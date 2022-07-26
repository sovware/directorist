<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-user-dashboard-access-notice">
	<div class="directorist-alert directorist-alert-warning">
		<span class="fa fa-info-circle" aria-hidden="true"></span>
		<?php printf( __( "You need to be logged in to view the content of this page. You can login <a href='%s'>Here</a>. Don't have an account? <a href='%s'>Sign Up</a>", 'directorist' ), esc_url( $login_link ), esc_url( $registration_link ) ); ?>
	</div>
</div>