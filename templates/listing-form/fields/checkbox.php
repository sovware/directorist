<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */
$value = explode( ',', $data['value'] );
?>

<div class="form-group directorist-checkbox-field">
	<?php $form->field_label_template( $data );?>
	
	<div class="form-control">
		<?php if( !empty( $data['options'] ) ) :
		foreach ( $data['options'] as $option ): 
			?>
			<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>
			<input type="checkbox" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php echo in_array( $option['option_value'], $value ) ? 'checked="checked"' : '' ; ?>><label for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label><br>
		<?php endforeach;
		endif;
		?>
	</div>

	<?php $form->field_description_template( $data ); ?>
</div>
