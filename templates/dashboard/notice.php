<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php if( $renew_token_expired ): ?>
	<div class="directorist-alert directorist-alert-danger directorist-alert-dismissable"><?php directorist_icon( 'las la-info-circle' ); ?><?php esc_html_e( 'Link appears to be invalid.', 'directorist' ); ?></div>
<?php endif; ?>

<?php if( $renew_succeed ): ?>
	<div class="directorist-alert directorist-alert-info directorist-alert-dismissable"><?php directorist_icon( 'las la-info-circle' ); ?><?php esc_html_e( 'Renewed successfully.', 'directorist' ); ?></div>
<?php endif; ?>

<?php if( $dashboard->confirmation_text() ): ?>
	<div class="directorist-alert directorist-alert-info directorist-alert-dismissible">
		<?php echo esc_html( $dashboard->confirmation_text() ); ?>
		<button type="button" class="directorist-alert__close"><span aria-hidden="true">&times;</span></button>
	</div>
<?php endif; ?>