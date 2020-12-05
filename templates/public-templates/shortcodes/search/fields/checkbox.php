<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

if ( !empty($data['label']) ): 
?>
	<label><?php echo esc_html( $data['label'] ); ?></label>
<?php endif; ?>

<div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
		<?php if( $original_field['fields']['checkbox']['field_key'] === $data['field_key'] ) {
		$options = $original_field['fields']['checkbox']['options'];
		if( $options ) {
		foreach ( $options as $option ):
			?>
			<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>
			<input <?php checked( $value === $option[ 'option_value' ] ); ?> type="checkbox" class="custom-control-input" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $option['option_value'] ); ?>"><label for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label><br>
		<?php endforeach;
		}	}	
?>
</div>
