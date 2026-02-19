<?php
/**
 * Archive Action – Email button.
 *
 * @author  wpWax
 * @since   8.5.5
 * @version 8.5.5
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$email = get_post_meta( $post_id, '_email', true );

if ( ! $email ) {
	return;
}
?>
<a class="directorist-btn directorist-btn-xs directorist-btn-outline-secondary"
   href="mailto:<?php echo esc_attr( $email ); ?>">
	<?php directorist_icon( 'las la-envelope' ); ?>
	<?php esc_html_e( 'Send Email', 'directorist' ); ?>
</a>

