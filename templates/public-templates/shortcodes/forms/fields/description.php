<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" class="directorist-description-field">
	<?php
	$form->add_listing_label_template( $data );
	
	if ( 'textarea' === $data['type'] ) {
		?>
		<textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" rows="8" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?> ><?php echo esc_attr( $data['value'] ); ?></textarea>
		<?php
	}
	else {
		wp_editor(
			wp_kses_post( $data['value'] ),
			$data['field_key'],
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
	
	$form->add_listing_description_template( $data );
	?>
</div>