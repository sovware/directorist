<?php
/**
 * Archive Action – Phone button.
 *
 * @author  wpWax
 * @since   8.5.5
 * @version 8.5.5
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$phone = get_post_meta( $post_id, '_phone', true );

if ( ! $phone ) {
	return;
}

$phone_args = [
	'number'   => $phone,
	'whatsapp' => $listings->has_whatsapp( $data ),
];

$phone_link = Helper::phone_link( $phone_args );

if ( ! empty( $original['whatsapp'] ) ) : ?>
	<a class="directorist-btn directorist-btn-xs directorist-btn-outline-secondary"
	   href="<?php echo esc_url( $phone_link ); ?>">
		<?php directorist_icon( 'lab la-whatsapp' ); ?>
		<?php esc_html_e( 'WhatsApp', 'directorist' ); ?>
	</a>
<?php else : ?>
	<a class="directorist-btn directorist-btn-xs directorist-btn-primary"
	   href="<?php echo esc_url( $phone_link ); ?>">
		<?php directorist_icon( 'las la-phone' ); ?>
		<?php esc_html_e( 'Call Now', 'directorist' ); ?>
	</a>
<?php endif;

