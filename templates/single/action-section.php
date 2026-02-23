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

			$phone_icon = ! empty( $action['icon'] ) ? $action['icon'] : 'las la-phone';

			if ( ! empty( $action['form_data']['whatsapp'] ) ) :
				$phone_label = ! empty( $action['form_data']['label'] ) ? $action['form_data']['label'] : __( 'WhatsApp', 'directorist' );
				?>
				<a class="directorist-btn directorist-btn-sm directorist-btn-outline-secondary"
				   href="<?php echo esc_url( $phone_link ); ?>">
					<?php directorist_icon( $phone_icon ); ?>
					<?php echo esc_html( $phone_label ); ?>
				</a>
			<?php else :
				$phone_label = ! empty( $action['form_data']['label'] ) ? $action['form_data']['label'] : __( 'Call Now', 'directorist' );
				?>
				<a class="directorist-btn directorist-btn-sm directorist-btn-primary"
				   href="<?php echo esc_url( $phone_link ); ?>">
					<?php directorist_icon( $phone_icon ); ?>
					<?php echo esc_html( $phone_label ); ?>
				</a>
			<?php endif;
				break;

			// ── Email ────────────────────────────────
			case 'email':
				$email = get_post_meta( $listing_id, '_email', true );
				if ( ! $email ) { break; }
				?>
			<?php
				$email_icon  = ! empty( $action['icon'] ) ? $action['icon'] : 'las la-envelope';
				$email_label = ! empty( $action['form_data']['label'] ) ? $action['form_data']['label'] : __( 'Send Email', 'directorist' );
				?>
			<a class="directorist-btn directorist-btn-sm directorist-btn-default"
			   href="mailto:<?php echo esc_attr( $email ); ?>">
				<?php directorist_icon( $email_icon ); ?>
				<?php echo esc_html( $email_label ); ?>
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

				$button_style = ! empty( $action['form_data']['button_style'] ) ? $action['form_data']['button_style'] : 'default';
				$target       = ! empty( $action['form_data']['open_in_new_tab'] ) ? ' target="_blank" rel="noopener"' : '';

				$btn_class = 'directorist-btn directorist-btn-sm';
				if ( 'primary' === $button_style ) {
					$btn_class .= ' directorist-btn-outline-primary';
				} elseif ( 'secondary' === $button_style ) {
					$btn_class .= ' directorist-btn-outline-secondary';
				} else {
					$btn_class .= ' directorist-btn-default';
				}
				?>
			<?php
				$button_icon  = ! empty( $action['icon'] ) ? $action['icon'] : 'las la-link';
				$button_label = ! empty( $action['form_data']['label'] ) ? $action['form_data']['label'] : $btn_text;
				?>
			<a class="<?php echo esc_attr( $btn_class ); ?>"
			   href="<?php echo esc_url( $btn_url ); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			   <?php echo esc_html( $btn_text ); ?>
			   <?php directorist_icon( $button_icon ); ?>
			</a>
				<?php
				break;

		endswitch;
	endforeach;
	?>
</div>
