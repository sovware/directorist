<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-group directorist-custom-filed-date">

	<?php $listing_form->field_label_template( $data );?>

	<input type="date" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" <?php $listing_form->required( $data ); ?>>

	<?php $listing_form->field_description_template( $data );?>

</div>