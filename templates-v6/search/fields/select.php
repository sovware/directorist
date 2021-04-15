<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>
<div class="single_search_field ">
	<?php
	if ( !empty($data['label']) ):
		?>
		<label><?php echo esc_html( $data['label'] ); ?></label>
	<?php endif;

	?>

	<select name='custom_field[<?php echo esc_html( $data['field_key'] ); ?>]' class="select-basic form-control" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
		<?php
		printf( '<option value="">%s</option>', __( 'Select', 'directorist' ) );

		foreach ( $data['options'] as $option ):
			printf('<option value="%s"%s>%s</option>', $option['option_value'], selected(  $value === $option[ 'option_value' ] ), $option['option_label']);
		endforeach;
		?>
	</select>
</div>