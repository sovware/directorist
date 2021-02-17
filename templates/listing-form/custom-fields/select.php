<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-group directorist-custom-field-select">

	<?php $listing_form->field_label_template( $data );?>

	<select name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" <?php $listing_form->required( $data ); ?>>

		<?php foreach( $data['options'] as $key => $value ): ?>

			<option value="<?php echo esc_attr( $value['option_value'] )?>" <?php selected( $value['option_value'], $data['value'] ); ?>><?php echo esc_attr( $value['option_label'] )?></option>

		<?php endforeach ?>

	</select>

	<?php $listing_form->field_description_template( $data ); ?>

</div>
