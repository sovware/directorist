<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-group directorist-form-address-field">

	<?php $listing_form->field_label_template( $data );?>

	<input type="text" autocomplete="off" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" value="<?php echo esc_attr( $data['value'] ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php $listing_form->required( $data ); ?>>

	<div id="result"><ul></ul></div>

</div>