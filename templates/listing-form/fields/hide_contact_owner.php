<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-group directorist-checkbox directorist-form-hide-owner-field">

	<input type="checkbox" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" <?php checked($data['value'], 'on'); ?> >

	<label class="directorist-checkbox__label" for="<?php echo esc_attr( $data['field_key'] ); ?>"><?php echo esc_html( $data['label'] ); ?></label>

</div>