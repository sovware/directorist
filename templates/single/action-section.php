<?php
/**
 * Action Section – renders phone, email and button widgets as linked action buttons.
 *
 * @author  wpWax
 * @since   8.5.5
 * @version 8.5.5
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$listing_id = $listing->id;
$has_action = false;

// Pre-check: at least one action widget must have a value.
foreach ( $actions as $action ) {
	$widget = $action['widget_name'] ?? '';

	switch ( $widget ) {
		case 'phone':
			$phone_value = get_post_meta( $listing_id, '_phone', true );
			if ( $phone_value ) { $has_action = true; break 2; }
			break;

		case 'email':
			$email_value = get_post_meta( $listing_id, '_email', true );
			if ( $email_value ) { $has_action = true; break 2; }
			break;

		case 'button':
			$field_key   = ! empty( $action['field_key'] ) ? $action['field_key'] : 'custom-button';
			$btn_raw     = get_post_meta( $listing_id, '_' . $field_key, true );
			$btn_value   = is_array( $btn_raw ) ? $btn_raw : maybe_unserialize( $btn_raw );
			$btn_text    = $btn_value['button_text'] ?? '';
			$btn_url     = $btn_value['button_url_label'] ?? '';
			if ( $btn_text && $btn_url ) { $has_action = true; break 2; }
			break;
	}
}

if ( ! $has_action ) {
	return;
}
?>

<div class="directorist-listing-actions">
	<?php
	foreach ( $actions as $action ) :
		$widget = $action['widget_name'] ?? '';

		switch ( $widget ) :

			// ── Phone / WhatsApp ─────────────────────
			case 'phone':
				$phone = get_post_meta( $listing_id, '_phone', true );
				if ( ! $phone ) { break; }

				$phone_args = [
					'number'   => $phone,
					'whatsapp' => $listing->has_whatsapp( $action ),
				];
				$phone_link = Helper::phone_link( $phone_args );

				if ( ! empty( $action['form_data']['whatsapp'] ) ) : ?>
					<a class="directorist-btn directorist-btn-sm directorist-btn-outline-secondary"
					   href="<?php echo esc_url( $phone_link ); ?>">
						<?php directorist_icon( 'lab la-whatsapp' ); ?>
						<?php esc_html_e( 'WhatsApp', 'directorist' ); ?>
					</a>
				<?php else : ?>
					<a class="directorist-btn directorist-btn-sm directorist-btn-primary"
					   href="<?php echo esc_url( $phone_link ); ?>">
						<?php directorist_icon( 'las la-phone' ); ?>
						<?php esc_html_e( 'Call Now', 'directorist' ); ?>
					</a>
				<?php endif;
				break;

			// ── Email ────────────────────────────────
			case 'email':
				$email = get_post_meta( $listing_id, '_email', true );
				if ( ! $email ) { break; }
				?>
				<a class="directorist-btn directorist-btn-sm directorist-btn-outline-secondary"
				   href="mailto:<?php echo esc_attr( $email ); ?>">
					<?php directorist_icon( 'las la-envelope' ); ?>
					<?php esc_html_e( 'Send Email', 'directorist' ); ?>
				</a>
				<?php
				break;

			// ── Custom Button ────────────────────────
			case 'button':
				$field_key   = ! empty( $action['field_key'] ) ? $action['field_key'] : 'custom-button';
				$btn_raw     = get_post_meta( $listing_id, '_' . $field_key, true );
				$btn_value   = is_array( $btn_raw ) ? $btn_raw : maybe_unserialize( $btn_raw );
				$btn_text    = $btn_value['button_text'] ?? '';
				$btn_url     = $btn_value['button_url_label'] ?? '';

				if ( ! $btn_text || ! $btn_url ) { break; }

				$form_data    = $action['form_data'] ?? $action;
				$button_style = ! empty( $form_data['button_style'] ) ? $form_data['button_style'] : 'default';
				$target       = ! empty( $form_data['open_in_new_tab'] ) ? ' target="_blank" rel="noopener"' : '';

				$btn_class = 'directorist-btn directorist-btn-sm';
				if ( 'primary' === $button_style ) {
					$btn_class .= ' directorist-btn-primary';
				} elseif ( 'secondary' === $button_style ) {
					$btn_class .= ' directorist-btn-outline-secondary';
				} else {
					$btn_class .= ' directorist-btn-default';
				}
				?>
				<a class="<?php echo esc_attr( $btn_class ); ?>"
				   href="<?php echo esc_url( $btn_url ); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php directorist_icon( 'las la-link' ); ?>
					<?php echo esc_html( $btn_text ); ?>
				</a>
				<?php
				break;

		endswitch;
	endforeach;
	?>
</div>
