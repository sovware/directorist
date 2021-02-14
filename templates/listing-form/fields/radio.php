<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group directorist-radio-field">
	<?php $listing_form->field_label_template( $data );?>

	<div class="form-control">
		<?php if( !empty( $data['options'] ) ) : 
		foreach ( $data['options'] as $option ): ?>
			<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>
			<input type="radio" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php checked( $option['option_value'], $data['value'] ); ?>><label for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label><br>
			<?php endforeach;
		endif;
		?>
	</div>

	<?php $listing_form->field_description_template( $data ); ?>
</div>
