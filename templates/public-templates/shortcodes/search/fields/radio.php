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

<div class="custom-control custom-radio radio-outline radio-outline-primary">
		<?php if( $original_field['fields']['radio']['field_key'] === $data['field_key'] ) :
        $options = $original_field['fields']['radio']['options'];
		foreach ( $options as $option ):
			?>
			<?php $uniqid = $option['option_value'] . '-' .wp_rand();  ?>
			<input <?php checked( !empty( $_GET[ $data[ 'field_key' ] ]) && ( $_GET[ $data[ 'field_key' ] ] === $option[ 'option_value' ] ) ); ?> type="radio" class="custom-control-input" id="<?php echo esc_attr( $uniqid ); ?>" name="<?php echo esc_attr( $data['field_key'] ); ?>" value="<?php echo esc_attr( $option['option_value'] ); ?>"><label for="<?php echo esc_attr( $uniqid ); ?>"><?php echo esc_html( $option['option_label'] ); ?></label><br>
		<?php endforeach;
		endif;
		?>
</div>