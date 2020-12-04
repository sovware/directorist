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

<select name='<?php echo esc_html( $data['field_key'] ); ?>' class="select-basic form-control" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
        <?php if( $original_field['fields']['select']['field_key'] === $data['field_key'] ) :
        $options = $original_field['fields']['select']['options'];
        printf( '<option value="">%s</option>', __( 'Select', 'directorist' ) );
        foreach ( $options as $option ):
            printf('<option value="%s"%s>%s</option>', $option['option_value'], selected( !empty( $_GET[ $data[ 'field_key' ] ] ) && ( $_GET[ $data[ 'field_key' ] ] === $option[ 'option_value' ] ) ), $option['option_label']);
		endforeach;
		endif;
		?>
</select>