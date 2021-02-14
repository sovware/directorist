<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="form-group" id="directorist-select-field">

	<?php $listing_form->field_label_template( $data );?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="form-control" <?php $listing_form->required( $data ); ?>>

		<?php foreach( $data['options'] as $key => $value ): ?>

			<option value="<?php echo esc_attr( $value['option_value'] )?>" <?php selected( $value['option_value'], $data['value'] ); ?>><?php echo esc_attr( $value['option_label'] )?></option>
			
		<?php endforeach ?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>
