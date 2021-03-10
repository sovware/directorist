<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$options_value = is_array( $data['value'] ) ? join( ",",$data['value'] ) : $data['value'];

$value = explode( ',', $options_value );
?>

<div class="form-group directorist-checkbox-field">
	<?php $form->add_listing_label_template( $data );?>
	
	<div class="form-control">
		<?php if( !empty( $data['options'] ) ) :
		foreach ( $data['options'] as $option ): 
			?>
			<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>
			<input type="checkbox" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>[]" value="<?php echo esc_attr( $option['option_value'] ); ?>" <?php echo in_array( $option['option_value'], $value ) ? 'checked="checked"' : '' ; ?>><label for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label><br>
		<?php endforeach;
		endif;
		?>
	</div>

	<?php $form->add_listing_description_template( $data ); ?>
</div>
