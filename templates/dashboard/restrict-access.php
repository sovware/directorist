<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-user-dashboard-access-notice">
	<div class="directorist-alert directorist-alert-warning">
		<span class="fa fa-info-circle" aria-hidden="true"></span>
		<?php printf(__("You need to be logged in to view the content of this page. You can login <a href='%s'>Here</a>. Don't have an account? <a href='%s'>Sign Up</a>", 'directorist'), $login_link, $registration_link ); ?>
	</div>
</div>