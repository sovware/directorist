<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
?>

<div class="directorist-form-group directorist-checkbox directorist-form-hide-owner-field"<?php echo $conditional_logic_attr; ?>>

    <input type="checkbox" name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" <?php checked( $data['value'], 'on' ); ?> >

    <label class="directorist-checkbox__label" for="<?php echo esc_attr( $data['field_key'] ); ?>"><?php echo esc_html( $data['label'] ); ?></label>

</div>