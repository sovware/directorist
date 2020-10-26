<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" id="directorist-description-field">
	<?php $form->add_listing_label_template( $data );?>

	<?php if ( 'textarea' === $type ) { ?>
		<textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" rows="8" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $required ) ? 'required="required"' : ''; ?> ><?php echo esc_attr( $data['value'] ); ?></textarea>
		<?php
	} else {
		wp_editor(
			! empty( $listing->post_content ) ? wp_kses_post( $listing->post_content ) : '',
			'listing_content',
			apply_filters(
				'atbdp_add_listing_wp_editor_settings',
				array(
					'media_buttons' => false,
					'quicktags'     => true,
					'editor_height' => 200,
				)
			)
		);
	}
	?>
</div>
